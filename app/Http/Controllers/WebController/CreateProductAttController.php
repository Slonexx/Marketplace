<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CreateProductAttController extends Controller
{
    public function createAttributes($apiKey)
    {

        $bodyAttributes = [
            0 => [
                "name" => "brand (KASPI)",
                "type" => "string",
                "required" => false,
                "description" => "Наименование бренда (Kaspi)",
            ],
            1 => [
                "name" => "Добавлять товар на Kaspi",
                "type" => "boolean",
                "required" => false,
                "description" => "При отметки данного типа товар будет добавляться в excel файл",
            ],
            2 => [
                "name" => "Опубликован на Kaspi",
                "type" => "boolean",
                "required" => false,
                "description" => "Является ли товар опубликованным на Kaspi",
            ],
        ];


        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes";
        $client = new ApiClientMC($uri, $apiKey);
        $json = $client->requestGet();

        foreach($bodyAttributes as $body){
            $foundedAttrib = false;
            foreach($json->rows as $row){
                if($body["name"] == $row->name){
                    $foundedAttrib = true;
                    break;
                }
            }
            if($foundedAttrib == false){
                $client->requestPost($body);
            }
        }


    }
}
