<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Clients\MsClient;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getMainSetting;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class addController extends Controller
{
    public function index(Request $request, $accountId){
        $isAdmin = $request->isAdmin;
        $Setting = new getSettingVendorController($accountId);
        $mainSetting = new getMainSetting($accountId);
        $TokenMoySklad = $Setting->TokenMoySklad;
        $ClientMs = new MsClient($TokenMoySklad);


        $url_customerorder = "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $url_saleschannel = "https://api.moysklad.ru/api/remap/1.2/entity/saleschannel";
        $url_project = "https://api.moysklad.ru/api/remap/1.2/entity/project";

        $Body_customerorder = $ClientMs->get($url_customerorder)->states;
        $body_saleschannel = $ClientMs->get($url_saleschannel)->rows;
        $body_project = $ClientMs->get($url_project)->rows;

        $Saleschannel = $Setting->Saleschannel; $Project = $Setting->Project;
        if ($Saleschannel == null) $Saleschannel = "0"; if ($Project == null) $Project = "0";

        return view('setting.add',[

            "Body_customerorder" => $Body_customerorder,
            "Body_saleschannel" => $body_saleschannel,
            "Body_project" => $body_project,

            "Saleschannel" => $Saleschannel,  "Project" => $Project,

            "APPROVED_BY_BANK" => $Setting->APPROVED_BY_BANK,
            "ACCEPTED_BY_MERCHANT" => $Setting->ACCEPTED_BY_MERCHANT,
            "COMPLETED" => $Setting->COMPLETED,
            "CANCELLED" => $Setting->CANCELLED,
            "RETURNED" => $Setting->RETURNED,

            "accountId"=> $accountId,
            'isAdmin' => $isAdmin,
        ]);
    }
}
