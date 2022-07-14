<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function getAgent($customer,$address,$apiKey) 
    {
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/counterparty?search=".$customer->cellPhone;
        $client = new ApiClientMC($uri,$apiKey);
        $jsonRes = $client->requestGet();
        $resultMeta = null;
        foreach ($jsonRes->rows as $key => $row){
            $resultMeta = $row->meta;
            break;
        }

        if(is_null($resultMeta) == true){
            return $this->createAgent($customer,$address,$apiKey);
        } else return $resultMeta;
    }

    public function createAgent($customer,$address,$apiKey){
        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/counterparty";
        $client = new ApiClientMC($uri,$apiKey);
        $attributes = app(AgentAttributesController::class)->getAttributeGos($apiKey);
        //$attributes = app(SessionController::class)->getCookie('gos_attribute');
        $agent = [
            'name' => 'Kaspi client '.$customer->name,
            "legalLastName" => $customer->lastName,
            "legalFirstName" => $customer->firstName,
            "actualAddress" => $address,
            "phone" => $customer->cellPhone,
            "companyType" => "individual",
            "attributes" => [
                0 => [
                    "meta" => $attributes["meta"],
                    "name" => "Государственное учреждение",
                    "value" => $attributes["value"],
                ],
            ],
        ];
        return $client->requestPost($agent)->meta;
    }

    // private function getContentJson($filename) {
    //     $path = public_path().'/json'.'/'.$filename.'.json';
    //     return json_decode(file_get_contents($path),true);
    // }

}
