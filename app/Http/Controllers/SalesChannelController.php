<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesChannelController extends Controller
{
    public function getSaleChannel($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/saleschannel?search=Kaspi Shop";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json->rows as $row){
            if ($row->name == 'Kaspi Shop'){
                 $foundedMeta = [
                    "meta" => $row->meta,
                ];
                break;
            }
        }
        if (is_null($foundedMeta) == true){
            return $this->createSaleChannel($apiKey);
        } else return $foundedMeta;
    }

    public function createSaleChannel($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/saleschannel";
        $client = new ApiClientMC($uri,$apiKey);
        $saleChannel = [
            "name" => "Kaspi Shop",
            "description" => "Marketplace Kaspi-shop",
            "type" => "MARKETPLACE",
        ];
        $createdMeta = $client->requestPost($saleChannel)->meta;

        return [
            "meta" => $createdMeta,
        ];
    }

}
