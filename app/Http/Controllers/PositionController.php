<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function setPositions($orderId,$entries,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/".$orderId."/positions";
        $client = new ApiClientMC($uri,$apiKey);
        foreach($entries as $entry){
            $position = [
                "quantity" => $entry['quantity'],
                "price" => $entry['basePrice'] * 100,
                "assortment" => [
                    "meta" => $this->searchProduct($entry['product'],$apiKey),
                ],
                "reserve" => $entry['quantity'],
            ];
            $client->requestPost($position);
        }
    }

    private function searchProduct($product,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product?search=".$product->code;
        $client = new ApiClientMC($uri,$apiKey);
        $res = $client->requestGet();
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
