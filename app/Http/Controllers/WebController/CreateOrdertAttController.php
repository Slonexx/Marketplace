<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomEntityController;
use Illuminate\Http\Request;

class CreateOrdertAttController extends Controller
{
    public function createAttributes($apiKey)
    {
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
        $client->setRequestUrl($uri);
        $json = $client->requestGet();
        $foundedAttrib = false;

        foreach($json->rows as $row){
            if($row->name == 'Способ доставки' && $row->type == 'customentity'){
                $foundedAttrib = true;
                break;
            }
        }

        if($foundedAttrib == false){
            $client->requestPost($body);
        }
    }
}
