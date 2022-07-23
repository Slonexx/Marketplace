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

    public function initDocuments($orderEntries,$statusOrder,$metaOrder,$paymentOption,$demandOption,$formattedOrder, $apiKey)
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
              if($paymentOption > 0){
                $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
              }
            break;
            case 'CANCELLED':
                case 'CANCELLING':
              // "Отменен";
              //При отмене Если выбрано не создавать отгрузку, то не создавать и платежный документ
              if($paymentOption > 0 && $demandOption != 0){
                $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
              }

              if($demandOption > 0){
                    
                 $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                 $metaReturn = $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);

                    //При отмене не создается счет-фактура????
                 if($demandOption == 2){
                    $this->createFactureout($apiKey,$metaDemand);
                 }

                if($paymentOption > 0){
                    //Если пользователь решил не создавать платежный документ????
                   $this->createPayOutDocument($apiKey,$metaReturn,$paymentOption,$formattedOrder,$sum);
                }
              }

             
              
            break;
            case 'COMPLETED':
              // "Доставлен";
              if($paymentOption > 0){
                $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
              }
              
              if($demandOption > 0){
                $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                if($demandOption == 2){
                    $this->createFactureout($apiKey,$metaDemand);
                 }
              }

              
            break;
            case 'KASPI_DELIVERY_RETURN_REQUESTED':
                case 'RETURN_ACCEPTED_BY_MERCHANT':
                  case 'RETURNED':
                    // "Возврат";
                    if($paymentOption > 0) {
                        $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
                    }

                    if($demandOption > 0){
                        $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                        $metaReturn = $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);

                        if($demandOption == 2){
                            $this->createFactureout($apiKey,$metaDemand);
                        }

                        if($paymentOption > 0){
                            //Если пользователь решил не создавать платежный документ????
                           $this->createPayOutDocument($apiKey,$metaReturn,$paymentOption,$formattedOrder,$sum);
                        }
                    }
                    
                    
                    break;
        }

    }

    private function createFactureout($apiKey,$metaDemand)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/factureout";
        $client = new ApiClientMC($uri,$apiKey);
        $docBody = [
            "demands" => [
                0 => [
                    "meta" => $metaDemand,
                ],
            ],
        ];
        $client->requestPost($docBody);
    }

    private function createPayInDocument($apiKey,$meta,$isPayment,$formattedOrder,$sum)
    {
        $uri = null;
        if ($isPayment == 2) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/paymentin";
        } elseif($isPayment == 1) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/cashin";
        }
        

        //dd($metaOrder);

        $client = new ApiClientMC($uri, $apiKey);
        $docBody = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
            "rate" => $formattedOrder['rate'],
            "sum" => $sum*100,
            "operations" => [
                0=> [
                    "meta" => $meta,
                ],
            ],
        ];

        if(array_key_exists("salesChannel",$formattedOrder)){
            $docBody["salesChannel"] = $formattedOrder['salesChannel'];
        }

        if(array_key_exists("project",$formattedOrder)){
            $docBody["project"] = $formattedOrder['project'];
        }

        if(array_key_exists("organizationAccount",$formattedOrder)){
            $docBody["organizationAccount"] = $formattedOrder['organizationAccount'];
        }
        

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
            "addInfo" => $formattedOrder['shipmentAddressFull']['addInfo'],
        ];


        if(array_key_exists("salesChannel",$formattedOrder)){
            $docBodyDemand["salesChannel"] = $formattedOrder['salesChannel'];
        }

        if(array_key_exists("project",$formattedOrder)){
            $docBodyDemand["project"] = $formattedOrder['project'];
        }

        if(array_key_exists("organizationAccount",$formattedOrder)){
            $docBodyDemand["organizationAccount"] = $formattedOrder['organizationAccount'];
        }
        


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
            "demand" => [
                "meta" => $metaDemand,
            ],
        ];

        if(array_key_exists("salesChannel",$formattedOrder)){
            $docBodyReturn["salesChannel"] = $formattedOrder['salesChannel'];
        }

        if(array_key_exists("project",$formattedOrder)){
            $docBodyReturn["project"] = $formattedOrder['project'];
        }

        if(array_key_exists("organizationAccount",$formattedOrder)){
            $docBodyReturn["organizationAccount"] = $formattedOrder['organizationAccount'];
        }
        


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
        if ($isPayment == 2) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/paymentout";
        } elseif($isPayment == 1) {
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/cashout";
        }

        $client = new ApiClientMC($uri, $apiKey);
        $docBody = [
            "agent" => $formattedOrder['agent'],
            "organization" => $formattedOrder['organization'],
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


        if(array_key_exists("salesChannel",$formattedOrder)){
            $docBody["salesChannel"] = $formattedOrder['salesChannel'];
        }

        if(array_key_exists("project",$formattedOrder)){
            $docBody["project"] = $formattedOrder['project'];
        }

        if(array_key_exists("organizationAccount",$formattedOrder)){
            $docBody["organizationAccount"] = $formattedOrder['organizationAccount'];
        }
        
        $client->requestPost($docBody);
    }

    public function createDocuments($payments,$demands,$orderEntries,$statusOrder,$metaOrder,$paymentOption,$demandOption,$formattedOrder, $apiKey)
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
              if($paymentOption > 0){
                $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
              }
            break;
            case 'CANCELLED':
                case 'CANCELLING':
                        // "Отменен";
                        $metaDemand = null;
                        $metaReturn = null;
                        if ($payments == null) {
                            if($paymentOption > 0 && $demandOption != 0){
                                $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
                            }
                        } else {
                            if($demandOption == 0){
                                $this->deletePayments($payments,$apiKey);
                            }
                        } 

                        if($demands == null){
                            if($demandOption > 0){
                                $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                             }
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

                        if($demandOption > 0){
                             if($metaReturn == null && $metaDemand != null){
                                 $metaReturn = $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);
                            }

                            if($demandOption == 2 && $metaDemand != null){
                                $this->createFactureout($apiKey,$metaDemand);
                            } 

                            if($metaReturn != null && $paymentOption>0){
                                $this->createPayOutDocument($apiKey,$metaReturn,$paymentOption,$formattedOrder,$sum);
                            }
                            
                        }

                           
              
                break;
            case 'COMPLETED':
              // "Доставлен";
              if ($payments == null && $paymentOption > 0) {
                $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
              } 

              if($demandOption > 0){
                $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                if($demandOption == 2){
                    $this->createFactureout($apiKey,$metaDemand);
                }
              }

              
              
            break;
            case 'KASPI_DELIVERY_RETURN_REQUESTED':
                case 'RETURN_ACCEPTED_BY_MERCHANT':
                  case 'RETURNED':
                    // "Возврат";
                    $metaDemand = null;
                    if ($payments == null && $paymentOption > 0) {
                        $this->createPayInDocument($apiKey,$meta,$paymentOption,$formattedOrder,$sum);
                      } 
        
                      if($demands == null){

                        if($demandOption > 0){
                           $metaDemand = $this->createDenamd($apiKey,$meta,$formattedOrder,$orderEntries);
                        }
                        
                      } else {
                        foreach($demands as $demand){
                            $metaDemand = $demand->meta;
                            break;
                        }
                      }

                      if($demandOption == 2 && $metaDemand != null){
                        $this->createFactureout($apiKey,$metaDemand);
                      } 

                      if($demandOption > 0 && $metaDemand != null){
                        $metaReturn = $this->createReturn($apiKey,$metaDemand,$formattedOrder,$orderEntries);
                        if($paymentOption > 0){
                            $this->createPayOutDocument($apiKey,$metaReturn,$paymentOption,$formattedOrder,$sum);
                        }
                        
                      }
                    
                break;
        }

    }

    private function deletePayments($payments,$apiKey)
    {
        foreach($payments as $payment){
           $client = new ApiClientMC($payment->meta->href,$apiKey);
           $client->requestDelete();
        }
    }

}
