<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiClientMC;

class CurrencyController extends Controller
{
    public function getKzCurrency($apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/currency?seacrh=тенге";
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
            return $this->createCurrency($apiKey);
        } else return $foundedMeta;
    }

    public function createCurrency($apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/currency";
        $client = new ApiClientMC($uri,$apiKey);
        $currency = [
            "system" => true,
            "isoCode" => "KZT",
        ];
        $createdMeta = $client->requestPost($currency)->meta;

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
