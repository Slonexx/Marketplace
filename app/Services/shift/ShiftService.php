<?php

namespace App\Services\shift;

use App\Clients\KassClient;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getSetting;
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
        $setting  = new getSettingVendorController($accountId);
        $settingDevice = new getDevices($accountId);
        $settingDevice = $settingDevice->devices;
        $apiKeyMs = $setting->TokenMoySklad;
        $apiKey = "6784dad7-6679-4950-b257-2711ff63f9bb";
        $numKassa = $settingDevice->znm;
        $password = $settingDevice->password;

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
