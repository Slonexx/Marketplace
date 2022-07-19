<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{


    # APPROVED_BY_BANK – одобрен банком
    #ACCEPTED_BY_MERCHANT– принят на обработку продавцом

    #CANCELLED – отменён
    #CANCELLING – ожидает отмены

    #COMPLETED – завершён
    
    #KASPI_DELIVERY_RETURN_REQUESTED – ожидает возврата
    #RETURN_ACCEPTED_BY_MERCHANT – ожидает решения по возврату
    #RETURNED – возвращён

    public function initDocuments($orderEntries,$statusOrder,$metaOrder,$isPayment,$formattedOrder, $apiKey)
    {
        // $meta  = [
        //     "href" => $metaOrder->href,
        //     "metadataHref" =>$metaOrder->metadataHref,
        //     "type" => $metaOrder->type,
        //     "mediaType" => $metaOrder->mediaType,
        //     "uuidHref" => $metaOrder->uuidHref,
        // ];
        $meta = $metaOrder;

        $sum = 0;
        foreach($orderEntries as $entry){
            $sum+=$entry['totalPrice'];
        }

        switch ($statusOrder) {
            case 'APPROVED_BY_BANK':
              //"Новый";
            break;
            case 'ACCEPTED_BY_MERCHANT':
              //"Подтвержден";
              $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
            break;
            case 'CANCELLED':
                case 'CANCELLING':
              // "Отменен";
              $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
              $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
              $metaReturn = $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);
              $this->createPayOutDocument($apiKey,$metaReturn,$isPayment,$formattedOrder,$sum);
            break;
            case 'COMPLETED':
              // "Доставлен";
              $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
              $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
            break;
            case 'KASPI_DELIVERY_RETURN_REQUESTED':
                case 'RETURN_ACCEPTED_BY_MERCHANT':
                  case 'RETURNED':
                    // "Возврат";
                    $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
                    $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                    $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);
                    break;
        }

    }

    private function createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum)
    {
        $uri = null;
        if ($isPayment == true) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/paymentin";
        } else {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/cashin";
        }
        

        //dd($metaOrder);

        $client = new ApiClientMC($uri, $apiKey);
        $docBody = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "rate" => $formattedOrder['rate'],
            "salesChannel" => $formattedOrder['salesChannel'],
            "sum" => $sum*100,
            "operations" => [
                0=> [
                    "meta" => $meta,
                ],
            ],
        ];
        $client->requestPost($docBody);
    }

    private function createDenamd($apiKey, $meta, $formattedOrder, $entries)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/demand";
        $client = new ApiClientMC($uri,$apiKey);
        $docBodyDemand = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "rate" => $formattedOrder['rate'],
            "store" => $formattedOrder['store'],
            "salesChannel" => $formattedOrder['salesChannel'],
            "addInfo" => $formattedOrder['shipmentAddressFull']['addInfo'],
        ];
        $createdDemand = $client->requestPost($docBodyDemand);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/demand"."/".$createdDemand->id."/positions";
        $client->setRequestUrl($uri);
        foreach($entries as $entry) {
            $bodyDemandPositions = [
                "quantity" => $entry['quantity'],
                "price" => $entry['basePrice']* 100,
                "assortment" => [
                    "meta" => app(PositionController::class)->searchProduct($entry['product'],$apiKey)
                ],
            ];
            $client->requestPost($bodyDemandPositions);
        }

        $uri = 'https://online.moysklad.ru/api/remap/1.2/entity/demand'.'/'.$createdDemand->id;
        $client->setRequestUrl($uri);
        $bodyOrder = [
            "customerOrder" => [
                "meta" => $meta,
            ],
        ];
        //dd($bodyOrder);
        return $client->requestPut($bodyOrder)->meta;
    }

    private function createReturn($apiKey,$metaDemand,$formattedOrder,$entries)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/salesreturn";
        $client = new ApiClientMC($uri,$apiKey);
        $docBodyReturn = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "store" => $formattedOrder['store'],
            "salesChannel" => $formattedOrder['salesChannel'],
            "demand" => [
                "meta" => $metaDemand,
            ],
        ];
        $createdReturn = $client->requestPost($docBodyReturn);

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/salesreturn"."/".$createdReturn->id."/positions";
        $client->setRequestUrl($uri);
        foreach($entries as $entry) {
            $bodyReturnPositions = [
                0 => [
                    "quantity" => $entry['quantity'],
                    "price" => $entry['basePrice']* 100,
                    "assortment" => [
                        "meta" => app(PositionController::class)->searchProduct($entry['product'],$apiKey)
                    ],
                ],
            ];
            $client->requestPost($bodyReturnPositions);
        }
        return $createdReturn->meta;
    }

    private function createPayOutDocument($apiKey,$metaReturn,$isPayment,$formattedOrder,$sum)
    {
        $uri = null;
        if ($isPayment == true) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/paymentout";
        } else {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/cashout";
        }

        $client = new ApiClientMC($uri, $apiKey);
        $docBody = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "salesChannel" => $formattedOrder['salesChannel'],
            "expenseItem" => [
                "meta" => app(ExpenseItemController::class)->getExpenseItem('Возврат',$apiKey),
            ],
            "sum" => $sum*100,
            "operations" => [
                0=> [
                    "meta" => $metaReturn,
                ],
            ],
        ];
        $client->requestPost($docBody);
    }

    public function createDocuments($payments,$demands,$orderEntries,$statusOrder,$metaOrder,$isPayment,$formattedOrder, $apiKey)
    {
        // if($payments == null && $demands == null){
        //     $this->initDocuments($orderEntries,$statusOrder,$metaOrder,$isPayment, $formattedOrder, $apiKey);
        // } 
        
        $meta = $metaOrder;

        $sum = 0;
        foreach($orderEntries as $entry){
            $sum+=$entry['totalPrice'];
        }

        switch ($statusOrder) {
            case 'APPROVED_BY_BANK':
              //"Новый";
            break;
            case 'ACCEPTED_BY_MERCHANT':
              //"Подтвержден";
              $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
            break;
            case 'CANCELLED':
                case 'CANCELLING':
                        // "Отменен";
                        $metaDemand = null;
                        $metaReturn = null;
                        if ($payments == null) {
                            $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
                        } 

                        if($demands == null){
                            $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                        } else {
                            foreach($demands as $demand){
                                $metaDemand = $demand->meta;
                                $client = new ApiClientMC($metaDemand->href,$apiKey);
                                $jsonDemand = $client->requestGet();
                                if(property_exists($jsonDemand, 'returns') == true){
                                    foreach($jsonDemand->returns as $return){
                                        $metaReturn = $return->meta;
                                        break;
                                    }
                                }
                                break;
                            }
                        }

                        if($metaReturn == null){
                            $metaReturn = $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);
                        }
                            
                        $this->createPayOutDocument($apiKey,$metaReturn,$isPayment,$formattedOrder,$sum);   
              
                break;
            case 'COMPLETED':
              // "Доставлен";
              if ($payments == null) {
                $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
              } 
              $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
            break;
            case 'KASPI_DELIVERY_RETURN_REQUESTED':
                case 'RETURN_ACCEPTED_BY_MERCHANT':
                  case 'RETURNED':
                    // "Возврат";
                    $metaDemand = null;
                    if ($payments == null) {
                        $this->createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum);
                      } 
        
                      if($demands == null){
                        $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                      } else {
                        foreach($demands as $demand){
                            $metaDemand = $demand->meta;
                            break;
                        }
                      }
                      $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);
                break;
        }

    }

}
