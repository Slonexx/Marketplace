<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UomController extends Controller
{
    public function getUom($apiKey)
    {
        $uri = "https://api.moysklad.ru/api/remap/1.2/entity/uom";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json->rows as $row){
            if($row->name == "шт"){
                $foundedMeta = $row->meta;
                break;
            }
        }
        return [
            "meta" => $foundedMeta,
        ];
    }
}
