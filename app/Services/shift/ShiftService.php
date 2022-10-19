<?php

namespace App\Services\shift;

use App\Clients\KassClient;
use Illuminate\Support\Str;

class ShiftService
{

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function closeShift($data){
        $accountId = $data['accountId'];
        //$positionDevice = $data['position'];
        $pincode = $data['pincode'];

        //take settings by accountId
        $apiKeyMs = "f59a9e8d8011257f92f13ac0ad12a2d25c1e668f";
        $apiKey = "f5ac6559-b5cd-4e0e-89e5-7fd32a6d60a5";
        $numKassa = "VTH5DEV4-AQM";
        $password = "Qi1_CS0y5weXk09Lg3erA4*72dMuqYFM";

        $openedShift = $this->getOpenedShift($numKassa,$password,$apiKey);

        if (!is_null($openedShift)){
           $shiftNumber = $openedShift->shiftNumber;
           $cashRegister = $openedShift->cashRegister;

           $kassClient = new KassClient($numKassa,$password,$apiKey);
           $response = $kassClient->postWithHeaders('crs/'.$cashRegister.'/shifts/'.$shiftNumber.'/close',[
               'Accept' => 'application/json',
               'Authorization' => 'Bearer '.$kassClient->getNewJwtToken()->token,
               'cash-register-password' => $pincode,
           ]);
           return [
               "res" => $response,
               "code" => 200,
           ];
        }
        return [
            "res" => "Don't have opened shift!",
            "code" => 200,
        ];
    }


    private function getOpenedShift($kassNum, $password, $apiKey){
        $client = new KassClient($kassNum,$password,$apiKey);
        $id = $client->getNewJwtToken()->id;
        $jsonShifts = $client->get('crs/'.$id.'/shifts?includeOpen=true');

        $openedShift = null;
        foreach ($jsonShifts->_embedded->shifts as $shift){
            if ($shift->open){
                $openedShift = $shift;
                break;
            }
        }
        return $openedShift;
    }

}
