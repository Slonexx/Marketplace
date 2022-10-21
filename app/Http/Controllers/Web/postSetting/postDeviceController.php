<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Clients\KassClient;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getDeviceFirst;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getSetting;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class postDeviceController extends Controller
{
    public function postDevice(Request $request, $accountId){ $isAdmin = $request->isAdmin;
        $this->createBDAccess($accountId);
        $Setting = new getSetting($accountId);
        $apiKey = '6784dad7-6679-4950-b257-2711ff63f9bb';
        $ZHM_1 = $request->ZHM_1;
        $PASSWORD_1 = $request->PASSWORD_1;

        if ($ZHM_1 != null and $PASSWORD_1 != null) {
            try {
                $Client = new KassClient($ZHM_1, $PASSWORD_1, $apiKey);
                $StatusCode = $Client->getStatusCode();
                if ($StatusCode == 200 ){
                    $Device = new getDeviceFirst($ZHM_1);
                    if ($Device->accountId == null) DataBaseService::createDevice($ZHM_1, $PASSWORD_1, 1, $accountId);
                    else DataBaseService::updateDevice($ZHM_1, $PASSWORD_1, 1, $accountId);
                } else {
                    $message = [
                        'alert' => ' alert alert-danger alert-dismissible fade show in text-center ',
                        'message' => ' Заводской номер кассового аппарата или паролем не правильные ! ',
                    ];
                    $Devices = new getDevices($accountId);
                    return view('setting.device', [
                        'accountId' => $accountId,
                        'isAdmin' => $isAdmin,
                        'devices' => $Devices->devices,
                        'message' => $message,
                    ]);
                }
            } catch (\Throwable $e) {

            }
        }

        return redirect()->route('getDocument', ['accountId' => $accountId, 'isAdmin' => $isAdmin]);
    }

    private function createBDAccess($accountId){
        $apiKey = 'f5ac6559-b5cd-4e0e-89e5-7fd32a6d60a5';
        $Setting = new getSettingVendorController($accountId);
        $app = new getSetting($accountId);
        $paymentDocument = $app->paymentDocument;
        try {
            if ($app->tokenMs == null){
                DataBaseService::createSetting($accountId, $Setting->TokenMoySklad, $apiKey,
                    $paymentDocument, null,null);
            } else {
                DataBaseService::updateSetting($accountId, $Setting->TokenMoySklad, $apiKey,
                    $paymentDocument,null,null);
            }
        } catch (\Throwable $e){

        }
    }

}
