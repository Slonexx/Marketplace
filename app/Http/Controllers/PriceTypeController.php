<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceTypeController extends Controller
{
    public function getPriceType($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/pricetype";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json as $price){
            if($price->name == "Цена продажи"){
                $foundedMeta = $price->meta;
            }
        }
        return [
            "meta" => $foundedMeta,
        ];
    }
}
