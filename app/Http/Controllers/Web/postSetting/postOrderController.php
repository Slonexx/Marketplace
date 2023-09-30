<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getMainSetting;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class postOrderController extends Controller
{
    public function postOrderSetting(Request $request, $accountId){
        $isAdmin = $request->isAdmin;
        $mainSetting = new getMainSetting($accountId);
        $Setting = new getSettingVendorController($accountId);
        $cfg = new cfg();
        $appId = $cfg->appId;
        $app = AppInstanceContoller::loadApp($appId, $accountId);

        $Organization = $request->Organization;
        if ('Нет расчетного счёта' != $request->$Organization){ $PaymentAccount = $request->$Organization;
        } else $PaymentAccount = null;

        $TokenMoySklad = $Setting->TokenMoySklad;
        $url = "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $url_organization = "https://api.moysklad.ru/api/remap/1.2/entity/organization";

        $urlCheck = $url_organization . "/" . $request->Organization;
        $responses = Http::withToken($TokenMoySklad)->pool(fn (Pool $pool) => [
            $pool->as('body')->withToken($TokenMoySklad)->get($url),
            $pool->as('organization')->withToken($TokenMoySklad)->get($urlCheck),
            $pool->as('body_organization')->withToken($TokenMoySklad)->get($url_organization),
        ]);
        $Organization = $responses['organization']->object();

        try {
            DataBaseService::createOrderSetting($accountId, $request->Organization, $request->Document,
                $request->PaymentDocument, $PaymentAccount,  $request->CheckCreatProduct, $request->Store);

            $app->Organization = $request->Organization;
            $app->Document = $request->Document;
            $app->PaymentDocument = $request->PaymentDocument;
            $app->CheckCreatProduct = $request->CheckCreatProduct;
            $app->Store = $request->Store;
            if ($request->PaymentDocument == "2") $app->PaymentAccount = $PaymentAccount;
            else {$app->PaymentAccount = null; $PaymentAccount = null;}
            $app->persist();

        } catch (\Throwable $e){

            $app->creatDocument = null;
            $app->Organization = null;
            $app->Document = null;
            $app->PaymentDocument = null;
            $app->PaymentAccount = null;

            $app->persist();
        }

        return redirect()->route('addSetting', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
        ]);
    }
}
