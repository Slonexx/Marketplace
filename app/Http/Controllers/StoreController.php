<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiClientMC;

class StoreController extends Controller
{
    public function getKaspiStore($storeName,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/store?search=".$storeName;
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
            return $this->createStore($storeName,$apiKey);
        } else return $foundedMeta;
    }

    public function createStore($storeName,$apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/store";
        $client = new ApiClientMC($uri,$apiKey);
        $store = [
            "name" => $storeName,
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
