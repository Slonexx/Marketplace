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
            if ($status == 'ACCEPTED_BY_MERCHANT') {
                $position = [
                    "quantity" => $entry['quantity'],
                    "price" => $entry['basePrice'] * 100,
                    "assortment" => [
                        "meta" => $this->searchProduct($entry['product'],$apiKey),
                    ],
                    "reserve" => $entry['quantity'],
                ];
            } else {
                $position = [
                    "quantity" => $entry['quantity'],
                    "price" => $entry['basePrice'] * 100,
                    "assortment" => [
                        "meta" => $this->searchProduct($entry['product'],$apiKey),
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
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product?search=".$product->code;
        $client = new ApiClientMC($uri,$apiKey);
        $res = $client->requestGet();

        if($res->meta->size == 0){
            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product?search=".$product->name;
            $client->setRequestUrl($uri);
            $res = $client->requestGet();
        }

        $foundedMeta = null;
        foreach($res->rows as $row){
            $foundedMeta = $row->meta;
            break;
        }
        return [
            "href" =>$foundedMeta->href,
            "type" => $foundedMeta->type,
            "mediaType" =>$foundedMeta->mediaType,
        ];
    }

}
