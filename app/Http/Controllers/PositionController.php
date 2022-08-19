<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function setPositions($orderId,$status,$entries,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/".$orderId."/positions";
        $client = new ApiClientMC($uri,$apiKey);
        foreach($entries as $entry){

            $productMeta = $this->searchProduct($entry['product'],$apiKey);

            if($productMeta == null){
                $productMeta = $this->createProduct($entry,$apiKey);
            }

            if ($status == 'ACCEPTED_BY_MERCHANT') {

                $position = [
                    "quantity" => $entry['quantity'],
                    "price" => $entry['basePrice'] * 100,
                    "assortment" => [
                        "meta" => $productMeta,
                    ],
                    "reserve" => $entry['quantity'],
                ];
            } else {
                $position = [
                    "quantity" => $entry['quantity'],
                    "price" => $entry['basePrice'] * 100,
                    "assortment" => [
                        "meta" => $productMeta,
                    ],
                ];
            }
            
            $client->requestPost($position);
        }
    }

    public function setPositionReserve($orderId, $positionId, $quantityReserve,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/".$orderId."/positions"."/".$positionId;
        $client = new ApiClientMC($uri,$apiKey);
        $bodyReserve = [
            "reserve" => $quantityReserve,
        ];
        $client->requestPut($bodyReserve);
    }

    public function searchProduct($product,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product?filter=article=".$product->code;
        $client = new ApiClientMC($uri,$apiKey);
        $res = $client->requestGet();

        if($res->meta->size == 0){
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product?filter=name=".urlencode($product->name);
            $client->setRequestUrl($uri);
            $res = $client->requestGet();
        }

        $foundedMeta = null;
        foreach($res->rows as $row){
            $foundedMeta = $row->meta;
            break;
        }
        return $foundedMeta;
    }

    private function createProduct($entry,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product";
        $client = new ApiClientMC($uri,$apiKey);
        $product['name'] = $entry['product']->name;
        $product['article'] = $entry['product']->code;
        $product['attributes'] = [
                        0 => [
                            "meta" => app(ProductAttributesController::class)->getAttribute("brand (KASPI)",$apiKey),
                            "name" => "brand (KASPI)",
                            "value" => $entry['product']->manufacturer,
                        ],
                        1 => [
                            "meta" => app(ProductAttributesController::class)->getAttribute("Добавлять товар на Kaspi",$apiKey),
                            "name" => "Добавлять товар на Kaspi",
                            "value" => false,
                        ],
                        2 => [
                            "meta" => app(ProductAttributesController::class)->getAttribute("Опубликован на Kaspi",$apiKey),
                            "name" => "Опубликован на Kaspi",
                            "value" => true,
                        ]
                    ];

                    $product['salePrices'] = [
                        0 => [
                            "value" => $entry['basePrice'] * 100,
                            "currency" => app(CurrencyController::class)->getKzCurrency($apiKey),
                            "priceType" => app(PriceTypeController::class)->getPriceType($apiKey),
                        ],
                    ];
        $product['uom'] = app(UomController::class)->getUom($apiKey);
       return $client->requestPost($product)->meta;
    }

}
