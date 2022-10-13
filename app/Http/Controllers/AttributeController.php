<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Client\HttpClientException;

use Illuminate\Http\Request;

class AttributeController extends Controller
{

    public function createAllAttributes(Request $request)
    {

        $request->validate([
            'tokenMs' => 'required|string',
            'accountId' => 'required|string',
        ]);

        $TokenMoySklad = $request->tokenMs;


        try{
            $this->createProductAttributes($TokenMoySklad);
            $this->createOrderAttributes($TokenMoySklad);
            $this->createAgentAttributes($TokenMoySklad);
        }catch(ClientException $e){
           // dd($e);
        }

        return response([
            "message" => 'Set required attributes!',
            "accountId" => $request->accountId,
        ]);

    }

    public function createProductAttributes($apiKey)
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

    public function createOrderAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        $client = new ApiClientMC($uri, $apiKey);
        $json = $client->requestGet();

        $customEntityMeta = null;
        if (property_exists($json, 'customEntities'))
        foreach($json->customEntities as $customEntity){
            if( $customEntity->name == 'Способ доставки (Kaspi)'){
                $customEntityMeta = $customEntity->meta;
                break;
            }
        }

        if($customEntityMeta == null){
            app(CustomEntityController::class)->createCustomEntity($apiKey,"Способ доставки (Kaspi)",["Доставка","Самовывоз"]);
            $json = $client->requestGet();
        }
            foreach($json->customEntities as $customEntity){
                if( $customEntity->name == 'Способ доставки (Kaspi)'){
                    $customEntityMeta = $customEntity->meta;
                    break;
               }
           }

           if($customEntityMeta != null){
                    $body = [
                    "customEntityMeta" => [
                      "href" => $customEntityMeta->href,
                      "type" => $customEntityMeta->type,
                      "mediaType" => $customEntityMeta->mediaType,
                    ],
                    "name" => "Способ доставки (Kaspi)",
                    "type" => "customentity",
                    "required" => false,
                ];

                $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata/attributes";
                $client->setRequestUrl($uri);
                $json = $client->requestGet();
                $foundedAttrib = false;

                foreach($json->rows as $row){
                    if($row->name == 'Способ доставки (Kaspi)' && $row->type == 'customentity'){
                        $foundedAttrib = true;
                        break;
                    }
                }

                //dd($body);

                if($foundedAttrib == false){
                    $client->requestPost($body);
                }
           }
    }

    public function createAgentAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        $client = new ApiClientMC($uri, $apiKey);
        $json = $client->requestGet();

        $customEntityMeta = null;
        foreach($json->customEntities as $customEntity){
            if( $customEntity->name == 'Государственное учреждение (Kaspi)'){
                $customEntityMeta = $customEntity->meta;
                break;
            }
        }

        if($customEntityMeta == null){
            app(CustomEntityController::class)->createCustomEntity($apiKey,"Государственное учреждение (Kaspi)",["Да","Нет"]);
            $json = $client->requestGet();
        }

         foreach($json->customEntities as $customEntity){
                if( $customEntity->name == 'Государственное учреждение (Kaspi)'){
                    $customEntityMeta = $customEntity->meta;
                    break;
                }
        }

        if($customEntityMeta != null) {
                $body = [
                "customEntityMeta" => [
                    "href" => $customEntityMeta->href,
                    "type" => $customEntityMeta->type,
                    "mediaType" => $customEntityMeta->mediaType,
                ],
                "name" => "Государственное учреждение (Kaspi)",
                "type" => "customentity",
                "required" => false,
            ];

            $uri = "https://online.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes";
            $client->setRequestUrl($uri);
            $json = $client->requestGet();
            $foundedAttrib = false;

            foreach($json->rows as $row){
                if($row->name == 'Государственное учреждение (Kaspi)' && $row->type == 'customentity'){
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
