<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttributeController extends Controller
{

    public function createAllAttributes(Request $request)
    {
        $request->validate([
            'tokenMs' => 'required|string',
        ]);

        $this->createProductAttributes($request->tokenMs);
        $this->createOrderAttributes($request->tokenMs);
        $this->createAgentAttributes($request->tokenMs);
    }

    private function createProductAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/product/metadata/attributes";
        $client = new ApiClientMC($uri, $apiKey);
        $body = [
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
        $client->requestPost($body);
    }

    private function createOrderAttributes($apiKey)
    {
        app(CustomEntityController::class)->createCustomEntity($apiKey,"Способ доставки",["Доставка","Самовывоз"]);
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        $client = new ApiClientMC($uri, $apiKey);
        $json = $client->requestGet();

        $customEntityMeta = null;
        foreach($json->customEntities as $customEntity){
            if( $customEntity->name == 'Способ доставки'){
                $customEntityMeta = $customEntity->meta;
                break;
            }
        }
        
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes";
        $client->setRequestUrl($uri);
        $body = [
            "customEntityMeta" => $customEntityMeta,
            "name" => "Способ доставки",
            "type" => "customentity",
            "required" => false,
        ];
        $client->requestPost($body);
    }

    private function createAgentAttributes($apiKey)
    {
        app(CustomEntityController::class)->createCustomEntity($apiKey,"Государственное учреждение",["Да","Нет"]);
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        $client = new ApiClientMC($uri, $apiKey);
        $json = $client->requestGet();

        $customEntityMeta = null;
        foreach($json->customEntities as $customEntity){
            if( $customEntity->name == 'Государственное учреждение'){
                $customEntityMeta = $customEntity->meta;
                break;
            }
        }

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes";
        $client = new ApiClientMC($uri, $apiKey);
        $body = [
            "customEntityMeta" => $customEntityMeta,
            "name" => "Государственное учреждение",
            "type" => "customentity",
            "required" => true,
        ];
        $client->requestPost($body);
    }

}
