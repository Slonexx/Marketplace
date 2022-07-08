<?php

namespace App\Http\Controllers;

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
        $agent = [
            'name' => 'Kaspi client '.$customer->name,
            "legalLastName" => $customer->lastName,
            "legalFirstName" => $customer->firstName,
            "actualAddress" => $address,
            "phone" => $customer->cellPhone,
            "companyType" => "individual",
            "attributes" => [
                0 => [
                    "meta" => $this->getContentJson('gos'),
                    "name" => "Государственное учреждение",
                    "value" => $this->getContentJson('gos_val'),
                ],
            ],
        ];
        return $client->requestPost($agent)->meta;
    }

    private function getContentJson($filename) {
        $path = public_path().'/json'.'/'.$filename.'.json';
        return json_decode(file_get_contents($path),true);
    }

}
