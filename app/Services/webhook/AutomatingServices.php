<?php

namespace App\Services\webhook;

use App\Clients\KassClient;
use App\Clients\MsClient;
use App\Http\Controllers\getData\getDeviceFirst;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getSetting;
use App\Services\MetaServices\MetaHook\AttributeHook;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;


class AutomatingServices
{

    private MsClient $msClient;
    private KassClient $kassClient;
    private getSetting $setting;
    private mixed $settingAutomation;
    private mixed $msOldBodyEntity;
    private AttributeHook $attributeHook;

    /**
     * @throws GuzzleException
     */
    public function initialization(mixed $ObjectBODY, mixed $BDFFirst): array
    {
        $accountId = $BDFFirst['accountId'];
        $this->attributeHook = new AttributeHook();
        $this->setting = new getSetting($BDFFirst['accountId']);
        $this->msClient = new MsClient($this->setting->tokenMs);

        $Device = new getDevices($accountId);
        $znm = $Device->devices[0]->znm;
        $Device = new getDeviceFirst($znm);

        $this->kassClient = new KassClient($Device->znm, $Device->password, $this->setting->apiKey);

        $this->msOldBodyEntity = $ObjectBODY;
        $this->settingAutomation = json_decode(json_encode($BDFFirst));


        return $this->createAutomating();
    }

    /**
     * @throws GuzzleException
     */
    public function createAutomating(): array
    {
        $body = $this->createBody();

        if ($body != []) {
            try {
                $response = $this->kassClient->post("crs/" . $this->kassClient->getNewJwtToken()->id . "/tickets", $body);
            } catch (ClientException $exception) {
                return [
                    "ERROR",
                    "Ошибка при отправки",
                    "==========================================",
                    $exception->getResponse()->getBody()->getContents(),
                    "BODY",
                    "==========================================",
                    $body,
                ];
            }

            try {
                $this->writeToAttrib($response->id);
            } catch (ClientException $exception) {
                return [
                    "ERROR",
                    "Ошибка при сохранении",
                    "==========================================",
                    json_decode($exception->getResponse()->getBody()->getContents()),
                    "BODY",
                    "==========================================",
                    $body,
                    "response",
                    "==========================================",
                    $response,
                ];
            }

            try {
                if ($this->setting->paymentDocument != null ){
                    $this->createPaymentDocument($body['payments']);
                }
            } catch (ClientException $exception) {
                return [
                    "ERROR",
                    "Ошибка при сохранении",
                    "==========================================",
                    json_decode($exception->getResponse()->getBody()->getContents()),
                    "BODY",
                    "==========================================",
                    $body,
                    "response",
                    "==========================================",
                    $response,
                ];
            }



            return [
                "SUCCESS",
                "Успешно отправилось и записалось",
                "==========================================",
                "BODY",
                "==========================================",
                $body,
                "response",
                "==========================================",
                $response,
            ];
        } else return [
            "ERROR",
            "Ошибка при создании тело запроса",
            "==========================================",
            "BODY",
            "==========================================",
            $body,
        ];

    }

    private function createBody(): array
    {
        if ($this->msOldBodyEntity->positions->meta->size === 0) {
            return [];
        }

        $items = $this->items();
        $payments = $this->payments();

        if ($items === null || $payments === null) {
            return [];
        }

        $body = [
            "dateTime" => $this->getNowDateTime(),
            "items" => $items,
            "payments" => $payments,
            "amounts" => $this->amounts(),
            "operation" => $this->operation(),
        ];

        if ($this->getUUH()) {
            $body['extension_options'] = $this->getUUH();
        }

        return $body;
    }

    private function createPaymentDocument(mixed $payments): void
    {
        $entity_type = null;
        match ($this->settingAutomation->entity) {
            0, "0" => $entity_type = 'customerorder',
            1, "1" => $entity_type = 'demand',
            2, "2" => $entity_type = 'salesreturn',
            default => null,
        };

        switch ($this->setting->paymentDocument){
            case "1": {
                $url = 'https://online.moysklad.ru/api/remap/1.2/entity/';
                if ($entity_type != 'salesreturn') {
                    $url = $url . 'cashin';
                } else {
                    //$url = $url . 'cashout';
                    break;
                }
                $body = [
                    'organization' => [  'meta' => [
                        'href' => $this->msOldBodyEntity->organization->meta->href,
                        'type' => $this->msOldBodyEntity->organization->meta->type,
                        'mediaType' => $this->msOldBodyEntity->organization->meta->mediaType,
                    ] ],
                    'agent' => [ 'meta'=> [
                        'href' => $this->msOldBodyEntity->agent->meta->href,
                        'type' => $this->msOldBodyEntity->agent->meta->type,
                        'mediaType' => $this->msOldBodyEntity->agent->meta->mediaType,
                    ] ],
                    'sum' => $this->msOldBodyEntity->sum,
                    'operations' => [
                        0 => [
                            'meta'=> [
                                'href' => $this->msOldBodyEntity->meta->href,
                                'metadataHref' => $this->msOldBodyEntity->meta->metadataHref,
                                'type' => $this->msOldBodyEntity->meta->type,
                                'mediaType' => $this->msOldBodyEntity->meta->mediaType,
                                'uuidHref' => $this->msOldBodyEntity->meta->uuidHref,
                            ],
                            'linkedSum' => $this->msOldBodyEntity->sum
                        ], ]
                ];
                $this->msClient->post($url, $body);
                break;
            }
            case "2": {
                $url = 'https://online.moysklad.ru/api/remap/1.2/entity/';
                if ($entity_type != 'salesreturn') {
                    $url = $url . 'paymentin';
                } else {
                    //$url = $url . 'paymentout';
                    break;
                }

                $rate_body = $this->msClient->get("https://online.moysklad.ru/api/remap/1.2/entity/currency/")->rows;
                $rate = null;
                foreach ($rate_body as $item){
                    if ($item->name == "тенге" or $item->fullName == "Казахстанский тенге"){
                        $rate =
                            ['meta'=> [
                                'href' => $item->meta->href,
                                'metadataHref' => $item->meta->metadataHref,
                                'type' => $item->meta->type,
                                'mediaType' => $item->meta->mediaType,
                            ],
                            ];
                    }
                }

                $body = [
                    'organization' => [  'meta' => [
                        'href' => $this->msOldBodyEntity->organization->meta->href,
                        'type' => $this->msOldBodyEntity->organization->meta->type,
                        'mediaType' => $this->msOldBodyEntity->organization->meta->mediaType,
                    ] ],
                    'agent' => [ 'meta'=> [
                        'href' => $this->msOldBodyEntity->agent->meta->href,
                        'type' => $this->msOldBodyEntity->agent->meta->type,
                        'mediaType' => $this->msOldBodyEntity->agent->meta->mediaType,
                    ] ],
                    'sum' => $this->msOldBodyEntity->sum,
                    'operations' => [
                        0 => [
                            'meta'=> [
                                'href' => $this->msOldBodyEntity->meta->href,
                                'metadataHref' => $this->msOldBodyEntity->meta->metadataHref,
                                'type' => $this->msOldBodyEntity->meta->type,
                                'mediaType' => $this->msOldBodyEntity->meta->mediaType,
                                'uuidHref' => $this->msOldBodyEntity->meta->uuidHref,
                            ],
                            'linkedSum' => $this->msOldBodyEntity->sum
                        ], ],
                    'rate' => $rate
                ];
                if ($body['rate'] == null) unlink($body['rate']);
                $this->msClient->post($url, $body);
                break;
            }
            case "3": {
                $url = 'https://online.moysklad.ru/api/remap/1.2/entity/';
                $url_to_body = null;
                foreach ($payments as $item){
                    $change = 0;
                    if ($item['PaymentType'] == 0){
                        if ($entity_type != 'salesreturn') { $url_to_body = $url . 'cashin'; } else { break; }
                        if (isset($item['change'])) $change = $item['change'];
                    } else {
                        if ($entity_type != 'salesreturn') {
                            $url_to_body = $url . 'paymentin';
                        }
                    }

                    $rate_body =  $this->msClient->get("https://online.moysklad.ru/api/remap/1.2/entity/currency/")->rows;
                    $rate = null;
                    foreach ($rate_body as $item_rate){
                        if ($item_rate->name == "тенге" or $item_rate->fullName == "Казахстанский тенге"){
                            $rate =
                                ['meta'=> [
                                    'href' => $item_rate->meta->href,
                                    'metadataHref' => $item_rate->meta->metadataHref,
                                    'type' => $item_rate->meta->type,
                                    'mediaType' => $item_rate->meta->mediaType,
                                ],
                                ];
                        }
                    }

                    $body = [
                        'organization' => [  'meta' => [
                            'href' => $this->msOldBodyEntity->organization->meta->href,
                            'type' => $this->msOldBodyEntity->organization->meta->type,
                            'mediaType' => $this->msOldBodyEntity->organization->meta->mediaType,
                        ] ],
                        'agent' => [ 'meta'=> [
                            'href' => $this->msOldBodyEntity->agent->meta->href,
                            'type' => $this->msOldBodyEntity->agent->meta->type,
                            'mediaType' => $this->msOldBodyEntity->agent->meta->mediaType,
                        ] ],
                        'sum' => ($item['Sum']-$change) * 100,
                        'operations' => [
                            0 => [
                                'meta'=> [
                                    'href' => $this->msOldBodyEntity->meta->href,
                                    'metadataHref' => $this->msOldBodyEntity->meta->metadataHref,
                                    'type' => $this->msOldBodyEntity->meta->type,
                                    'mediaType' => $this->msOldBodyEntity->meta->mediaType,
                                    'uuidHref' => $this->msOldBodyEntity->meta->uuidHref,
                                ],
                                'linkedSum' => $this->msOldBodyEntity->sum
                            ], ],
                        'rate' => $rate
                    ];
                    if ($body['rate'] == null) unlink($body['rate']);
                    $this->msClient->post($url_to_body, $body);
                }
                break;
            }
            case "4":{
                $url = 'https://online.moysklad.ru/api/remap/1.2/entity/';
                $url_to_body = null;
                foreach ($payments as $item){
                    $change = 0;
                    if ($item['PaymentType'] == 0){
                        if ($entity_type != 'salesreturn') {
                            if ($this->setting->OperationCash == 1) {
                                $url_to_body = $url . 'cashin';
                            }
                            if ($this->setting->OperationCash == 2) {
                                $url_to_body = $url . 'paymentin';
                            }
                            if ($this->setting->OperationCash == 0) {
                                continue;
                            }
                        }
                        if (isset($item['change'])) $change = $item['change'];
                    } else {
                        if ($entity_type != 'salesreturn') {
                            if ( $this->setting->OperationCard == 1) {
                                $url_to_body = $url . 'cashin';
                            }
                            if ($this->setting->OperationCard == 2) {
                                $url_to_body = $url . 'paymentin';
                            }
                            if ($this->setting->OperationCard == 0) {
                                continue;
                            }
                        }
                    }

                    $rate_body = $this->msClient->get("https://online.moysklad.ru/api/remap/1.2/entity/currency/")->rows;
                    $rate = null;
                    foreach ($rate_body as $item_rate){
                        if ($item_rate->name == "тенге" or $item_rate->fullName == "Казахстанский тенге"){
                            $rate =
                                ['meta'=> [
                                    'href' => $item_rate->meta->href,
                                    'metadataHref' => $item_rate->meta->metadataHref,
                                    'type' => $item_rate->meta->type,
                                    'mediaType' => $item_rate->meta->mediaType,
                                ],
                                ];
                        }
                    }

                    $body = [
                        'organization' => [  'meta' => [
                            'href' => $this->msOldBodyEntity->organization->meta->href,
                            'type' => $this->msOldBodyEntity->organization->meta->type,
                            'mediaType' => $this->msOldBodyEntity->organization->meta->mediaType,
                        ] ],
                        'agent' => [ 'meta'=> [
                            'href' => $this->msOldBodyEntity->agent->meta->href,
                            'type' => $this->msOldBodyEntity->agent->meta->type,
                            'mediaType' => $this->msOldBodyEntity->agent->meta->mediaType,
                        ] ],
                        'sum' => ($item['total']-$change) * 100,
                        'operations' => [
                            0 => [
                                'meta'=> [
                                    'href' => $this->msOldBodyEntity->meta->href,
                                    'metadataHref' => $this->msOldBodyEntity->meta->metadataHref,
                                    'type' => $this->msOldBodyEntity->meta->type,
                                    'mediaType' => $this->msOldBodyEntity->meta->mediaType,
                                    'uuidHref' => $this->msOldBodyEntity->meta->uuidHref,
                                ],
                                'linkedSum' => 0
                            ], ],
                        'rate' => $rate
                    ];
                    if ($body['rate'] == null) unset($body['rate']);
                    $this->msClient->post($url_to_body, $body);
                }
                break;
            }
            default:{
                break;
            }
        }

    }


    private function getNowDateTime(): array
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
    private function items(): ?array
    {
        $positions = null;
        $jsonPositions = $this->msClient->get($this->msOldBodyEntity->positions->meta->href);

        foreach ($jsonPositions->rows as $row) {
            $discount = $row->discount;
            $positionPrice = $row->price / 100;
            $sumPrice = $positionPrice - ($positionPrice * ($discount / 100));
            $product = $this->msClient->get($row->assortment->meta->href);

            $position = [
                "type" => "ITEM_TYPE_COMMODITY",
                "commodity" => [
                    "name" => $product->name,
                    "sectionCode" => "0",
                    "quantity" => (int)($row->quantity * 1000),
                    "price" => [
                        "bills" => (int)$positionPrice,
                        "coins" => (int)(($positionPrice - (int)$positionPrice) * 100),
                    ],
                    "sum" => [
                        "bills" => (int)$sumPrice,
                        "coins" => (int)(($sumPrice - (int)$sumPrice) * 100),
                    ],
                    "measureUnitCode" => $this->getUnitCode($product),
                ],
            ];

            if (property_exists($row, 'trackingCodes')) {
                for ($i = 1; $i <= $row->quantity; $i++) {
                    $position["commodity"]["excise_stamp"] = $row->trackingCodes[$i - 1]->cis;
                    $positions[] = $position;
                }
            } else {
                $SumBills = (int)($sumPrice) * $row->quantity;
                $SumCoins = (int)(round($sumPrice - (int)$sumPrice, 2) * 100) * $row->quantity;
                if ($SumCoins >= 100) {
                    $SumBills += (int)($SumCoins / 100);
                    $SumCoins -= (int)($SumCoins / 100) * 100;
                }

                $position["commodity"]["sum"]["bills"] = (string)$SumBills;
                $position["commodity"]["sum"]["coins"] = (string)$SumCoins;

                if (property_exists($row, 'vat') && property_exists($this->msOldBodyEntity, 'vatIncluded')) {
                    if ($this->msOldBodyEntity->vatIncluded) {
                        $sumVat = $sumPrice * ($row->vat / (100 + $row->vat));
                    } else {
                        $sumVat = $sumPrice * ($row->vat / 100);
                    }

                    if ($row->vat != 0) {
                        $TaxesSumBills = (int)$sumVat;
                        $TaxesSumCoins = (int)(round($sumVat - (int)$sumVat, 2) * 100);
                        if ($TaxesSumCoins >= 100) {
                            $TaxesSumBills += (int)($TaxesSumCoins / 100);
                            $TaxesSumCoins -= (int)($TaxesSumCoins / 100) * 100;
                        }
                        $position["commodity"]["taxes"] = [
                            0 => [
                                "sum" => [
                                    "bills" => (string)$TaxesSumBills,
                                    "coins" => (string)$TaxesSumCoins,
                                ],
                                "percent" => $row->vat * 1000,
                                "taxType" => 100,
                                "isInTotalSum" => $this->msOldBodyEntity->vatIncluded,
                                "taxationType" => 100,
                            ],
                        ];
                    }
                }
                $positions[] = $position;
            }
        }

        return $positions;
    }
    private function payments(): ?array
    {
        $Bills = $this->msOldBodyEntity->sum / 100;
        $Coins = floatval(round(floatval($this->msOldBodyEntity->sum) - intval($this->msOldBodyEntity->sum), 2) * 100);
        if ($Coins >= 100) {
            $Bills = $Bills + (intval($Coins / 100));
            $Coins = $Coins - (intval($Coins / 100) * 100);
        }

        $type = $this->getMoneyType($this->settingAutomation->payment);
        if ($type == "") {
            return null;
        }

        $payments[] = [
            "type" => $type,
            "sum" => [
                "bills" => $Bills,
                "coins" => $Coins,
            ],
        ];


        return $payments;
    }
    private function amounts(): array
    {
        $sum = (float)$this->msOldBodyEntity->sum / 100;
        $Bills = $sum;
        $Coins = (($sum - $Bills) * 100);
        if ($Coins >= 100) {
            $Bills = $Bills + (intval($Coins / 100));
            $Coins = $Coins - (intval($Coins / 100) * 100);
        }
        return [
            "total" => [
                "bills" => "" . $Bills,
                "coins" => "" . $Coins,
            ],
            "taken" => [
                "bills" => "0",
                "coins" => "0",
            ],
            "change" => [
                "bills" => "0",
                "coins" => "0",
            ],
        ];
    }
    private function operation(): string
    {
        return match ($this->settingAutomation->entity) {
            0, "0", 1, "1" => "OPERATION_SELL",
            2, "2" => "OPERATION_SELL_RETURN",
            default => "",
        };
    }


    private function getMoneyType($moneyType): string
    {

        switch ($moneyType) {
            case "Наличные":
            case "0" :
                return "PAYMENT_CASH";
            case "Картой":
            case "1" :
                return "PAYMENT_CARD";
            case "Мобильная":
            case "2" :
                return "PAYMENT_MOBILE";
            case "3" :
            {
                $attributes = null;
                if (property_exists($this->msOldBodyEntity, 'attributes')) {
                    foreach ($this->msOldBodyEntity->attributes as $id => $item) {
                        if ($item->name == 'Тип оплаты (Онлайн ККМ)') $attributes = $id;
                    }
                }

                if ($attributes == null) {
                    $description = 'Сбой автоматизации, проблема в отсутствии типа оплаты.';
                    if (property_exists($this->msOldBodyEntity, 'description')) $description = $description . ' ' . $this->msOldBodyEntity->description;
                    $this->msClient->put($this->msOldBodyEntity->meta->href, ['description' => $description]);
                } else {
                    return $this->getMoneyType($this->msOldBodyEntity->attributes[$attributes]->value->name);
                }

            }
            default:
                return "";
        }
    }


    private function getUUH(): array
    {

        $agent = $this->msClient->get($this->msOldBodyEntity->agent->meta->href);
        $result = [];

        if (property_exists($agent, 'email')) {
            $result['customer_email'] = $agent->email;
        }
        if (property_exists($agent, 'phone')) {
            $result['customer_phone'] = $agent->phone;
        }
        if (property_exists($agent, 'inn')) {
            $result['customer_iin_or_bin'] = $agent->inn;
        }

        return $result;
    }

    private function getUnitCode(mixed $product)
    {
        $uomCode = 796;

        if (property_exists($product, 'uom')) {
            $uom = $this->msClient->get($product->uom->meta->href);
            if (isset($uom->code) && isset($uom->name)) {
                $uomCode = $uom->code;
            }
        } else {
            if (property_exists($product, 'characteristics')) {
                $checkUom = $this->msClient->get($product->product->meta->href);
                if (property_exists($checkUom, 'uom')) {
                    $uom = $this->msClient->get($checkUom->uom->meta->href);
                    $uomCode = $uom->code;
                }
            }
        }

        return $uomCode;
    }


    public function writeToAttrib($id_ticket)
    {

        if (is_null($id_ticket)) {
            $flag = false;
        } else {
            $flag = true;
        }

        $metaIdTicket = $this->getMeta("id-билета (ReKassa)");
        $metaTicketFlag = $this->getMeta("Фискализация (ReKassa)");
        $body = [
            "attributes" => [
                0 => [
                    "meta" => $metaIdTicket,
                    "value" => "" . $id_ticket,
                ],
                1 => [
                    "meta" => $metaTicketFlag,
                    "value" => $flag,
                ],
            ],
        ];

        return $this->msClient->put($this->msOldBodyEntity->meta->href, $body);
    }


    private function getMeta($attribName)
    {
        return match ($this->settingAutomation->entity) {
            0, "0" => $this->attributeHook->getOrderAttribute($attribName, $this->setting->tokenMs),
            1, "1" => $this->attributeHook->getDemandAttribute($attribName, $this->setting->tokenMs),
            2, "2" => $this->attributeHook->getSalesReturnAttribute($attribName, $this->setting->tokenMs),
            default => null,
        };
    }
}
