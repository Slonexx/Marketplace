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
use App\Services\AdditionalServices\AttributeService;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class postDeviceController extends Controller
{
    public function postDevice(Request $request, $accountId): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $isAdmin = $request->isAdmin;
        $this->createBDAccess($accountId);
        $Setting = new getSetting($accountId);
        $getSettingVendorController = new getSettingVendorController($accountId);

        $ZHM_1 = $request->ZHM_1;
        $PASSWORD_1 = $request->PASSWORD_1;

        if ($ZHM_1 != null and $PASSWORD_1 != null) {
            try {

                //ПРОВЕРКА НА КЛИЕНТА ААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААААА
                $Client = new KassClient($ZHM_1, $PASSWORD_1, $Setting->apiKey);
                $StatusCode = $Client->getStatusCode();
                //dd($StatusCode);

                if ($StatusCode == 200 ){

                    if ($Setting->tokenMs == null){
                        DataBaseService::createSetting($accountId, $getSettingVendorController->TokenMoySklad, null,
                            null, null,null, null, null, null);
                    } else {
                        DataBaseService::updateSetting($accountId, $getSettingVendorController->TokenMoySklad,null,
                            null,null,null, null, null, null);
                    }

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

        $cfg = new cfg();
        $app = AppInstanceContoller::loadApp($cfg->appId, $accountId);
        $app->status = AppInstanceContoller::ACTIVATED;
        $vendorAPI = new VendorApiController();
        $vendorAPI->updateAppStatus($cfg->appId, $accountId, $app->getStatusName());
        $app->persist();

        $data = ['tokenMs'=> $getSettingVendorController->TokenMoySklad, 'accountId'=>$accountId];
        (new AttributeService())->setAllAttributesMs($data);

        return redirect()->route('getDocument', ['accountId' => $accountId, 'isAdmin' => $isAdmin]);
    }

    private function createBDAccess($accountId){
        $Setting = new getSettingVendorController($accountId);
        $app = new getSetting($accountId);
        $paymentDocument = $app->paymentDocument;
        try {
            if ($app->tokenMs == null){
                DataBaseService::createSetting($accountId, $Setting->TokenMoySklad, $Setting->payment_type,
                    $paymentDocument, null,null,null, null, null);
            } else {
                DataBaseService::updateSetting($accountId, $Setting->TokenMoySklad, $Setting->payment_type,
                    $paymentDocument,null,null, null, null, null);
            }
        } catch (\Throwable $e){

        }
    }

}
