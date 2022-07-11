<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductAttributesController extends Controller
{
    public function getAttribute($nameAttribute,$apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes";
        $client = new ApiClientMC($uri,$apiKey);
        $json = $client->requestGet();
        $foundedMeta = null;
        foreach($json->rows as $row){
            if($row->name == $nameAttribute){
                $foundedMeta = $row->meta;
                break;
            }
        }
        return $foundedMeta;
    }
}
