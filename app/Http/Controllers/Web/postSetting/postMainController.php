<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getMainSetting;
use App\Http\Controllers\KaspiApiClient;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Exception;

class postMainController extends Controller
{
    public function postMainSetting(Request $request, $accountId): \Illuminate\Http\RedirectResponse
    {
        $isAdmin = $request->isAdmin;
        $mainSetting = new getMainSetting($accountId);
        $Setting = new getSettingVendorController($accountId);

        $TokenKASPI = $request->TokenKaspi;

        if ($this->checkTokentKaspi($TokenKASPI)) {
            if ($mainSetting->TokenMoySklad == null){
                DataBaseService::createMainSetting($accountId, $Setting->TokenMoySklad, $TokenKASPI);
            } else {
                DataBaseService::updateMainSetting($accountId, $TokenKASPI);
            }
            $cfg = new cfg();
            $appId = $cfg->appId;
            $app = AppInstanceContoller::loadApp($appId, $accountId);
            $app->TokenKaspi = $TokenKASPI;
            $app->persist();
        } else {
            $message = [
                'alert' => ' alert alert-danger alert-dismissible fade show in text-center ',
                'message' => ' Токен Kaspi не правильный ! ',
            ];
            return redirect()->route('mainSetting', [
                'accountId' => $accountId,
                'isAdmin' => $isAdmin,
                'message' => $message,
            ]);
        }
        return redirect()->route('orderSetting', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function checkTokentKaspi(string $API_KEY){
        $url = "https://kaspi.kz/shop/api/products/import/schema";
        try {
            $Client = new KaspiApiClient($url,$API_KEY);
            $Client->CheckAndSaveApiKey(false);
            $return = true;
        } catch (\Throwable $e) {
            $return = false;
        }
        return $return;
    }
}
