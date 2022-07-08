<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiClientMC;

class StoreController extends Controller
{
    public function getKaspiStore($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/store?search=KASPI";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json->rows as $row){
            $foundedMeta = [
                "meta" => [
                    "href" => $row->meta->href,
                    "metadataHref" =>$row->meta->metadataHref,
                    "type" => $row->meta->type,
                    "mediaType" => $row->meta->mediaType,
                    "uuidHref" => $row->meta->uuidHref,
                ],
            ];
            break;
        }
        if (is_null($foundedMeta) == true){
            return $this->createStore($apiKey);
        } else return $foundedMeta;
    }

    public function createStore($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/store";
        $client = new ApiClientMC($uri,$apiKey);
        $store = [
            "name" => "KASPI",
        ];
        $createdMeta = $client->requestPost($store)->meta;

        return [
            "meta" => [
                "href" => $createdMeta->href,
                "metadataHref" =>$createdMeta->metadataHref,
                "type" => $createdMeta->type,
                "mediaType" => $createdMeta->mediaType,
                "uuidHref" => $createdMeta->uuidHref,
            ],
        ];
    }
}
