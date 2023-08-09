<?php

namespace App\Services\ticket;

use App\Clients\KassClient;
use App\Clients\MsClient;
use App\Http\Controllers\getData\getDeviceFirst;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getSetting;
use App\Services\AdditionalServices\DocumentService;
use App\Services\MetaServices\MetaHook\AttributeHook;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use JetBrains\PhpStorm\ArrayShape;
use function PHPUnit\Framework\isNull;

class DevTicketService
{

    private AttributeHook $attributeHook;
    private DocumentService $documentService;

    /**
     * @param AttributeHook $attributeHook
     * @param DocumentService $documentService
     */
    public function __construct(AttributeHook $attributeHook, DocumentService $documentService)
    {
        $this->attributeHook = $attributeHook;
        $this->documentService = $documentService;
    }

    // Create ticket
    public function createTicket($data): array
    {
        $accountId = $data['accountId'];
        $id_entity = $data['id_entity'];
        $entity_type = $data['entity_type'];


        $money_card = $data['money_card'];
        $money_cash = $data['money_cash'];
        $money_mobile = $data['money_mobile'];

        $total = $data['total'];
        $payType = $data['pay_type'];

        $positions = $data['positions'];

        $tookSum = $total;

        if (is_null($money_card)) $money_card = 0;
        if (is_null($money_cash)) $money_cash = 0;
        if (is_null($money_mobile)) $money_mobile = 0;

        if ($money_card <= 0 && $money_cash <= 0 && $money_mobile <= 0) return [
                "res" => [
                    "message" => "Please enter money!",
                ],
                "code" => 400
            ];


        $Setting = new getSetting($accountId);

        $apiKeyMs = $Setting->tokenMs;
        $paymentOption = $Setting->paymentDocument;

        $apiKey = $Setting->apiKey;

        $Device = new getDevices($accountId);
        $znm = $Device->devices[0]->znm;
        $Device = new getDeviceFirst($znm);

        $numKassa = $Device->znm;
        $password = $Device->password;

        //take positions from entity
        $urlEntity = $this->getUrlEntity($entity_type,$id_entity);
        $client = new MsClient($apiKeyMs);
        $jsonEntity = $client->get($urlEntity);

        //dd($jsonEntity);

        if (property_exists($jsonEntity,'positions')){

            $totalSum = $this->getTotalSum($positions, $urlEntity, $jsonEntity, $apiKeyMs);

            if ($tookSum < $totalSum){
                return [
                    "res" => [
                        "message" => "Don't have enough money to complete the transaction",
                    ],
                    "code" => 400
                ];
            }

            $change = $tookSum - $totalSum;

            $items = $this->getItemsByHrefPositions($jsonEntity->positions->meta->href,$positions,$jsonEntity,$apiKeyMs);

            if (count($items) > 0 ){

                $payments = [];
                $tempSum = $totalSum;

                if (intval($money_cash) > 0 && $tempSum > 0){
                    if ($tempSum > $money_cash){
                        $pay = $money_cash;
                        $tempSum -= $pay;
                    } else {
                        $pay = $tempSum;
                        $tempSum = 0;
                    }
                    $payments [] = [
                        "type" => $this->getMoneyType("Наличные"),
                        "sum" => [
                            "bills" => "".intval($pay),
                            "coins" => "".intval(round(floatval($pay)-intval($pay),2)*100),
                        ],
                    ];
                }
                if (intval($money_card) > 0 && $tempSum > 0){
                    if ($tempSum > $money_card){
                        $pay = $money_card;
                        $tempSum -= $pay;
                    } else {
                        $pay = $tempSum;
                        $tempSum = 0;
                    }

                    //$tempSum = 0;

                    $payments[] =  [
                        "type" => $this->getMoneyType("Банковская карта"),
                        "sum" => [
                            "bills" => "".intval($pay),
                            "coins" => "".intval(round(floatval($pay)-intval($pay),2)*100),
                        ],
                    ];
                }
                if (intval($money_mobile) > 0 && $tempSum > 0){
                    if ($tempSum > $money_mobile){
                        $pay = $money_mobile;
                    } else {
                        $pay = $tempSum;
                    }

                    $payments[] =  [
                        "type" => $this->getMoneyType("Мобильные"),
                        "sum" => [
                            "bills" => "".intval($pay),
                            "coins" => "".intval(round(floatval($pay)-intval($pay),2)*100),
                        ],
                    ];

                }

                $taken = 0;
                if ($payType != 'return') $taken = $money_cash;

                $amounts = [
                    "total" => [
                        "bills" => "".intval($totalSum),
                        "coins" => "".intval(round(floatval($totalSum)-intval($totalSum),2)*100),
                        ],
                    "taken" => [
                            "bills" => "".intval($taken),
                            "coins" => "".intval(round(floatval($taken)-intval($taken),2)*100),
                    ],
                    "change" => [
                            "bills" => "".intval($change),
                            "coins" => "".intval(round(floatval($change)-intval($change),2)*100),
                    ],
                ];

                $clientK = new KassClient($numKassa,$password,$apiKey);
                $id = $clientK->getNewJwtToken()->id;
                $body = [
                    "dateTime" => $this->getNowDateTime(),
                    "items" => $items,
                    "payments" => $payments,
                    "amounts" => $amounts,
                ];

                $isPayIn = null;
                if ($payType == "sell"){
                    $body["operation"] = "OPERATION_SELL";
                    $isPayIn = true;
                }
                elseif($payType == "return") {
                    $body["operation"] = "OPERATION_SELL_RETURN";
                    $isPayIn = false;
                }

                $ExtensionOptions = $this->getUUH($Setting, $id_entity, $entity_type);
                if ($ExtensionOptions){ $body = $body + ['extension_options' => $ExtensionOptions]; }









                dd($body, json_encode($body));

               // try {
                    $response = $clientK->post("crs/".$id."/tickets",$body);
                    dd($response, $body);

               /* } catch (ClientException $exception){
                    return [
                        "res" => [
                            "message" => "Ticket not created!",
                            "error" => json_decode($exception->getResponse()->getBody()),
                        ],
                        "code" => 400,
                    ];
                    //dd($exception->getMessage());
                }*/
            }
        } else {
            return [
                "res" => [
                    "message" => "Entity haven't got positions!",
                ],
                "code" => 400,
            ];
        }

        return [
            "res" => [
                "message" => "Some error",
            ],
            "code" => 400,
        ];
    }

    private function getItemsByHrefPositions($href,$positionsEntity,$jsonEntity,$apiKeyMs): array
    {
        //dd($href,$positionsEntity,$jsonEntity,$apiKeyMs);
        $positions = [];
        $client = new MsClient($apiKeyMs);
        $jsonPositions = $client->get($href);
        //$count = 1;

        foreach ($jsonPositions->rows as $row){

            foreach ($positionsEntity as $item){
                if ($row->id == $item->id){

                    $discount = $row->discount;
                    $positionPrice = $row->price / 100;
                    $sumPrice = $positionPrice - ( $positionPrice * ($discount/100) ) ;
                    $product = $this->getProductByAssortMeta($row->assortment->meta->href,$apiKeyMs);

                    if (property_exists($product, 'characteristics')){
                        $check_uom = $client->get($product->product->meta->href);
                        $ProductByUOM = $this->getProductByUOM($check_uom->uom->meta->href,$apiKeyMs);
                    } else  $ProductByUOM = $this->getProductByUOM($product->uom->meta->href,$apiKeyMs);


                   if (!property_exists($row, 'trackingCodes')){
                       $SumBills = intval($sumPrice) * $item->quantity;
                       $SumCoins = intval(round(floatval($sumPrice)-intval($sumPrice),2)*100) * $item->quantity;
                       if ($SumCoins > 100) {
                           $SumBills = $SumBills + ( intval($SumCoins / 100));
                           $SumCoins = $SumCoins - (intval($SumCoins / 100) * 100);
                       }



                       $position["type"] = "ITEM_TYPE_COMMODITY";
                       $position["commodity"] = [
                           "name" => $product->name,
                           "sectionCode" => "0",
                           "quantity" => (integer)($item->quantity * 1000),
                           "price" => [
                               "bills" => "".intval($positionPrice),
                               "coins" => "".intval(round(floatval($positionPrice)-intval($positionPrice),2)*100),
                           ],
                           "sum" => [
                               "bills" => "".$SumBills,
                               "coins" => "".$SumCoins,
                           ],
                           "measureUnitCode" => null,
                       ];
                       if (property_exists($product, 'characteristics')){
                           $check_uom = $client->get($product->product->meta->href);
                           $position["commodity"]['measureUnitCode'] = $this->getUomCode($check_uom->uom->meta->href,$apiKeyMs);
                       } else  $position["commodity"]['measureUnitCode'] = $this->getUomCode($product->uom->meta->href,$apiKeyMs);

                       if (property_exists($row,'vat') && property_exists($jsonEntity,'vatIncluded')){

                           if ($jsonEntity->vatIncluded){
                               $sumVat = $sumPrice * ( $row->vat / (100+$row->vat) ); //Цена включает НДС
                           }else {
                               $sumVat = $sumPrice * ($row->vat / 100); //Цена выключает НДС
                           }
                           if ($row->vat != 0)
                               $position["commodity"]["taxes"] = [
                                   0 => [
                                       "sum" => [
                                           "bills" => "".intval($sumVat),
                                           "coins" => "".intval(round(floatval($sumVat)-intval($sumVat),2)*100),
                                       ],
                                       "percent" => $row->vat * 1000,
                                       "taxType" => 100,
                                       "isInTotalSum" => $jsonEntity->vatIncluded,
                                       "taxationType" => 100,
                                   ],
                               ];
                       }

                       $positions [] = $position;
                   }
                   else {
                       for ($i = 1; $i <= $row->quantity; $i++){
                           $position["type"] = "ITEM_TYPE_COMMODITY";
                           $position["commodity"] = [
                               "name" => $product->name,
                               "sectionCode" => "0",
                               "quantity" => 1000,
                               "price" => [
                                   "bills" => "".intval($positionPrice),
                                   "coins" => "".intval(round(floatval($positionPrice)-intval($positionPrice),2)*100),
                               ],
                               "sum" => [
                                   "bills" => "".intval($sumPrice),
                                   "coins" => "".intval(round(floatval($sumPrice)-intval($sumPrice),2)*100),
                               ],
                               "measureUnitCode" => null,
                           ];

                           if (property_exists($product, 'characteristics')){
                               $check_uom = $client->get($product->product->meta->href);
                               $position["commodity"]['measureUnitCode'] = $this->getUomCode($check_uom->uom->meta->href,$apiKeyMs);
                           } else  $position["commodity"]['measureUnitCode'] = $this->getUomCode($product->uom->meta->href,$apiKeyMs);

                           if (property_exists($row,'trackingCodes')){
                               $position["commodity"]["excise_stamp"] = $row->trackingCodes[$i-1]->cis;
                           }

                           if (property_exists($row,'vat') && property_exists($jsonEntity,'vatIncluded')){
                               if ($jsonEntity->vatIncluded){
                                   $sumVat = $sumPrice * ( $row->vat / (100+$row->vat) ); //Цена включает НДС
                               }else {
                                   $sumVat = $sumPrice * ($row->vat / 100); //Цена выключает НДС
                               }
                               if ($row->vat != 0)
                                   $position["commodity"]["taxes"] = [
                                       0 => [
                                           "sum" => [
                                               "bills" => "".intval($sumVat),
                                               "coins" => "".intval(round(floatval($sumVat)-intval($sumVat),2)*100),
                                           ],
                                           "percent" => $row->vat * 1000,
                                           "taxType" => 100,
                                           "isInTotalSum" => $jsonEntity->vatIncluded,
                                           "taxationType" => 100,
                                       ],
                                   ];
                           }

                           $positions [] = $position;
                       }
                   }


                }
                else continue;
            }

        }

        return $positions;
    }

    private function getUrlEntity($enType,$enId){
        $url = null;
        switch ($enType){
            case "customerorder":
                $url = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/".$enId;
            break;
            case "demand":
                $url = "https://online.moysklad.ru/api/remap/1.2/entity/demand/".$enId;
            break;
            case "salesreturn":
                $url = "https://online.moysklad.ru/api/remap/1.2/entity/salesreturn/".$enId;
            break;
        }
        return $url;
    }

    private function getProductByAssortMeta($href,$apiKeyMs){
        $client = new MsClient($apiKeyMs);
        return $client->get($href);
    }

    private function getProductByUOM($href,$apiKeyMs){
        $client = new MsClient($apiKeyMs);
        return $client->get($href);
    }

    private function getUomCode($href,$apiKeyMs){
        $client = new MsClient($apiKeyMs);
        return $client->get($href)->code;
    }

    #[ArrayShape(["date" => "array", "time" => "array"])] private function getNowDateTime(): array
    {
        $now = Carbon::now();
        return [
            "date" => [
                "year" => $now->year,
                "month" => $now->month,
                "day" => $now->day,
            ],
            "time" => [
                "hour" => $now->hour,
                "minute" => $now->minute,
                "second" => $now->second,
            ],
        ];
    }

    public function writeToAttrib($id_ticket, $urlEntity, $entityType, $apiKeyMs, $positions)
    {
        $client = new MsClient($apiKeyMs);

        if (is_null($id_ticket)){ $flag = false;
        } else { $flag = true; }

       $metaPositions = $this->getmetaPostionos($urlEntity, $entityType, $apiKeyMs, $positions);

        $metaIdTicket = $this->getMeta("id-билета (ReKassa)",$entityType,$apiKeyMs);
        $metaTicketFlag = $this->getMeta("Фискализация (ReKassa)",$entityType,$apiKeyMs);
        $body = [
            "attributes" => [
                0 => [
                    "meta" => $metaIdTicket,
                    "value" => "".$id_ticket,
                ],
                1 => [
                    "meta" => $metaTicketFlag,
                    "value" => $flag,
                ],
            ],
            'positions' => $metaPositions,
        ];

        $putBody = $client->put($urlEntity,$body);
        return $putBody;
    }

    private function getMeta($attribName,$entityType,$apiKeyMs){
        return match ($entityType) {
            "customerorder" => $this->attributeHook->getOrderAttribute($attribName, $apiKeyMs),
            "demand" => $this->attributeHook->getDemandAttribute($attribName, $apiKeyMs),
            "salesreturn" => $this->attributeHook->getSalesReturnAttribute($attribName, $apiKeyMs),
            default => null,
        };
    }

    private function getMoneyType($moneyType): string
    {
        return match ($moneyType) {
            "Наличные" => "PAYMENT_CASH",
            "Банковская карта" => "PAYMENT_CARD",
            "Мобильные" => "PAYMENT_MOBILE",
            default => "",
        };
    }

    private function getTotalSum($positions, $urlEntity, $jsonEntity,$apiKeyMs): float|int
    {
        $total = 0;
        $urlEntityWithPositions = $urlEntity.'/positions';
        $client = new MsClient($apiKeyMs);
        $jsonPositions = $client->get($urlEntityWithPositions);

        foreach ($jsonPositions->rows as $position){

            foreach ($positions as $item){
                if ($position->id == $item->id){
                    $href = $position->assortment->meta->href;
                    $product = $client->get($href);


                    if (property_exists($product, 'characteristics')){
                        $check_uom = $client->get($product->product->meta->href);
                        $checkUOM = $this->getProductByUOM($check_uom->uom->meta->href,$apiKeyMs);
                    }
                    else  $checkUOM = $this->getProductByUOM($product->uom->meta->href,$apiKeyMs);


                    if ($checkUOM->name == "шт"){
                        $discount = $position->discount;
                        $positionPrice = $item->quantity * $position->price / 100;
                        $sumPrice = $positionPrice - ( $positionPrice * ($discount/100) ) ;
                    } else {
                        $discount = $position->discount;
                        $positionPrice = $item->quantity *  $position->price / 100;
                        $sumPrice = $positionPrice - ( $positionPrice * ($discount/100) ) ;
                    }


                    if (property_exists($jsonEntity,'vatIncluded')){
                        if ($jsonEntity->vatIncluded){
                            $sumVat = $sumPrice * ( $position->vat / (100+$position->vat) ); //Цена включает НДС
                        }else {
                            $sumVat = $sumPrice * ($position->vat / 100); //Цена выключает НДС
                            $sumPrice += $sumVat;
                        }
                    }

                    $total += $sumPrice;
                }
            }
        }
        return $total;
    }

    public function showTicket($data): string
    {
        $accountId = $data['accountId'];
        $idTicket = $data['id_ticket'];


        $Setting = new getSetting($accountId);
        $apiKey = $Setting->apiKey;

        $Device = new getDevices($accountId);

        $znm = $Device->devices[0]->znm;
        $Device = new getDeviceFirst($znm);

        $numKassa = $Device->znm;
        $password = $Device->password;

        $client = new KassClient($numKassa,$password,$apiKey);

        $idKassa = $client->getNewJwtToken()->id;

        return "print/".$idKassa."/".$idTicket;
    }



    private function getmetaPostionos($urlEntity, $entityType, $apiKeyMs, $positions)
    {
        $client = new MsClient($apiKeyMs);

        $body = $client->get($urlEntity.'/positions')->rows;
        $index = 0;
        foreach ($body as $item){
            foreach ($positions as $pos){
                if ($item->id == $pos->id){

                    $data[$index] =  [
                        'id' => $item->id,
                        'quantity' =>(int) $pos->quantity,
                        'price' => $item->price,
                        'discount' => $item->discount,
                        'vat' => null,
                        'assortment' => [
                            'meta' => [
                                'href' => $item->assortment->meta->href,
                                'type' => $item->assortment->meta->type,
                                'mediaType' => $item->assortment->meta->mediaType,
                            ]
                        ],
                        'reserve' => 0,
                    ];
                    if (property_exists($item, 'vat')) $data[$index]['vat'] = $item->vat;
                    else $data[$index]['vat'] = 0;
                    $index++;

                } else continue;
            }
       }
        return $data;
    }

    private function getUUH(getSetting $Setting, mixed $id_entity, mixed $entity_type): array
    {
        $Client = new MsClient($Setting->tokenMs);
        $body = $Client->get('https://online.moysklad.ru/api/remap/1.2/entity/'.$entity_type.'/'.$id_entity);
        $agent = $Client->get($body->agent->meta->href);
        $result = [];

        if (property_exists($agent, 'email')) { $result['customer_email'] = $agent->email; }
        if (property_exists($agent, 'phone')) { $result['customer_phone'] = $agent->phone; }
        if (property_exists($agent, 'inn')) { $result['customer_iin_or_bin'] = $agent->inn; }

        return $result;
    }

}
