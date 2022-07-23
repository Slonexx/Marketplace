<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttributeController extends Controller
{

    private ApiClientMC $client;

    public function createAllAttributes($TokenMoySklad)
    {
        $this->client = new ApiClientMC("",$TokenMoySklad);

        try{
            $this->createOrderAttributes($TokenMoySklad);
            $this->createProductAttributes($TokenMoySklad);
            $this->createAgentAttributes($TokenMoySklad);
        } catch(ClientException $e){
            dd($e);
        }
    }

    private function createProductAttributes($apiKey)
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
        //$client = new ApiClientMC($uri, $apiKey);
        $this->client->setRequestUrl($uri);
        $json = $this->client->requestGet();

        foreach($bodyAttributes as $body){
            $foundedAttrib = false;
            foreach($json->rows as $row){
                if($body["name"] == $row->name){
                    $foundedAttrib = true;
                    break;
                }
            }
            if($foundedAttrib == false){
                $this->client->requestPost($body);
            }
        }


    }

    private function createOrderAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        //$client = new ApiClientMC($uri, $apiKey);
        $this->client->setRequestUrl($uri);
        $json = $this->client->requestGet();

        $customEntityMeta = null;
        foreach($json->customEntities as $customEntity){
            if( $customEntity->name == 'Способ доставки'){
                $customEntityMeta = $customEntity->meta;
                break;
            }
        }

        if($customEntityMeta == null){
            app(CustomEntityController::class)->createCustomEntity($apiKey,"Способ доставки",["Доставка","Самовывоз"]);
            foreach($json->customEntities as $customEntity){
                if( $customEntity->name == 'Способ доставки'){
                    $customEntityMeta = $customEntity->meta;
                    break;
                }
            }
        }

        $body = [
            "customEntityMeta" => $customEntityMeta,
            "name" => "Способ доставки",
            "type" => "customentity",
            "required" => false,
        ];

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes";
        $this->client->setRequestUrl($uri);
        $json = $this->client->requestGet();
        $foundedAttrib = false;

        foreach($json->rows as $row){
            if($row->name == 'Способ доставки' && $row->type == 'customentity'){
                $foundedAttrib = true;
                break;
            }
        }

        if($foundedAttrib == false){
            $this->client->requestPost($body);
        }
    }

    private function createAgentAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        //$client = new ApiClientMC($uri, $apiKey);
        $this->client->setRequestUrl($uri);
        $json = $this->client->requestGet();

        $customEntityMeta = null;
        foreach($json->customEntities as $customEntity){
            if( $customEntity->name == 'Государственное учреждение'){
                $customEntityMeta = $customEntity->meta;
                break;
            }
        }

        if($customEntityMeta == null){
            app(CustomEntityController::class)->createCustomEntity($apiKey,"Государственное учреждение",["Да","Нет"]);
            foreach($json->customEntities as $customEntity){
                if( $customEntity->name == 'Государственное учреждение'){
                    $customEntityMeta = $customEntity->meta;
                    break;
                }
            }
        }

        $body = [
            "customEntityMeta" => $customEntityMeta,
            "name" => "Государственное учреждение",
            "type" => "customentity",
            "required" => true,
        ];

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes";
        $this->client->setRequestUrl($uri);
        $json = $this->client->requestGet();
        $foundedAttrib = false;

        foreach($json->rows as $row){
            if($row->name == 'Государственное учреждение' && $row->type == 'customentity'){
                $foundedAttrib = true;
                break;
            }
        }

        if($foundedAttrib == false){
            $this->client->requestPost($body);
        }
    }

}
