<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getMainSetting;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class orderController extends Controller
{
    public function index(Request $request, $accountId){
        $isAdmin = $request->isAdmin;
        $Setting = new getSettingVendorController($accountId);
        $mainSetting = new getMainSetting($accountId);
        $TokenMoySklad = $Setting->TokenMoySklad;

        $Organization = $Setting->Organization;
        $PaymentDocument = $Setting->PaymentDocument;
        $Document = $Setting->Document;
        $PaymentAccount = $Setting->PaymentAccount;
        $CheckCreatProduct = $Setting->CheckCreatProduct;
        $Store = $Setting->Store;

        if ($PaymentDocument == null) $PaymentDocument = "0";
        if ($Document == null) $Document = "0";
        if ($PaymentAccount == null) $PaymentAccount = "0";
        if ($CheckCreatProduct == null) $CheckCreatProduct = "1";
        if ($Store == null) $Store = "0";

        $url = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $url_organization = "https://online.moysklad.ru/api/remap/1.2/entity/organization";
        $url_store = "https://online.moysklad.ru/api/remap/1.2/entity/store";

        if($Organization != null){
            $urlCheck = $url_organization . "/" . $Organization;
            $responses = Http::withToken($TokenMoySklad)->pool(fn (Pool $pool) => [
                $pool->as('body')->withToken($TokenMoySklad)->get($url),
                $pool->as('organization')->withToken($TokenMoySklad)->get($urlCheck),
                $pool->as('body_organization')->withToken($TokenMoySklad)->get($url_organization),
                $pool->as('body_store')->withToken($TokenMoySklad)->get($url_store),
            ]);
            $Organization = $responses['organization']->object();
        } else {
            $Organization = "0";
            $responses = Http::withToken($TokenMoySklad)->pool(fn (Pool $pool) => [
                $pool->as('body')->withToken($TokenMoySklad)->get($url),
                $pool->as('body_organization')->withToken($TokenMoySklad)->get($url_organization),
                $pool->as('body_store')->withToken($TokenMoySklad)->get($url_store),
            ]);
        }

        return view('setting.order',[
            'Body' => $responses['body']->object()->states,
            "Body_organization" => $responses['body_organization']->object()->rows,
            "Body_store" => $responses['body_store']->object()->rows,

            "Organization" => $Organization,
            "PaymentDocument" => $PaymentDocument,
            "Document" => $Document,
            "PaymentAccount" => $PaymentAccount,
            "CheckCreatProduct" => $CheckCreatProduct,
            "Store" => $Store,

            "apiKey" => $TokenMoySklad,

            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
        ]);

    }
}
