<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomEntityController;
use Illuminate\Http\Request;

class CreateAgentAttController extends Controller
{
    public function createAttributes($apiKey)
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/context/companysettings/metadata";
        $client = new ApiClientMC($uri, $apiKey);
        $client->setRequestUrl($uri);
        $json = $client->requestGet();

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
        $client->setRequestUrl($uri);
        $json = $client->requestGet();
        $foundedAttrib = false;

        foreach($json->rows as $row){
            if($row->name == 'Государственное учреждение' && $row->type == 'customentity'){
                $foundedAttrib = true;
                break;
            }
        }

        if($foundedAttrib == false){
            $client->requestPost($body);
        }
    }
}
