<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Clients\MsClient;
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
        $ClientMs = new MsClient($TokenMoySklad);

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

        $url = "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $url_organization = "https://api.moysklad.ru/api/remap/1.2/entity/organization";
        $url_store = "https://api.moysklad.ru/api/remap/1.2/entity/store";

        if($Organization != null){
            $urlCheck = $url_organization . "/" . $Organization;
            $Body = $ClientMs->get($url)->states;
            $body_organization = $ClientMs->get($url_organization)->rows;
            $Body_store = $ClientMs->get($url_store)->rows;
            $Organization = $ClientMs->get($urlCheck);
        } else {
            $Organization = "0";
            $Body = $ClientMs->get($url)->states;
            $body_organization = $ClientMs->get($url_organization)->rows;
            $Body_store = $ClientMs->get($url_store)->rows;
        }

        return view('setting.order',[
            'Body' => $Body,
            "Body_organization" => $body_organization,
            "Body_store" => $Body_store,

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
