<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KaspiApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Setting_mainController extends Controller
{
    public function index(Request $request, $accountId){



       if($request->has('error')) {
           if( $request->error != "0" ) $error = $request->error;
           else $error = "0" ;
       }
        else $error = "0" ;

        $url = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $url_organization = "https://online.moysklad.ru/api/remap/1.2/entity/organization";
        $url_saleschannel = "https://online.moysklad.ru/api/remap/1.2/entity/saleschannel";
        $url_project = "https://online.moysklad.ru/api/remap/1.2/entity/project";

        $Setting = new getSettingVendorController($accountId);
        $TokenMoySklad = $Setting->TokenMoySklad;
        $TokenKaspi = $Setting->TokenKaspi;

        $Client = new ApiClientMC($url, $TokenMoySklad);
        $Body = $Client->requestGet()->states;

        $Organization = $Setting->Organization;
        if ($Organization != null) {
            $urlCheck = $url_organization . "/" . $Organization;
            $Client->setRequestUrl($urlCheck);
            $Organization = $Client->requestGet();
        } else ($Organization = "0");

        $PaymentDocument = $Setting->PaymentDocument;
        if ($PaymentDocument == null) $PaymentDocument = "0";

        $Document = $Setting->Document;
        if ($Document == null) $Document = "0";

        $PaymentAccount = $Setting->PaymentAccount;
        if ($PaymentAccount == null) $PaymentAccount = "0";

        $Saleschannel = $Setting->Saleschannel;
        if ($Saleschannel == null) $Saleschannel = "0";

        $Project = $Setting->Project;
        if ($Project == null) $Project = "0";

        $CheckCreatProduct = $Setting->CheckCreatProduct;
        if ($CheckCreatProduct == null) $CheckCreatProduct = "1";

        $APPROVED_BY_BANK = $Setting->APPROVED_BY_BANK;
        $ACCEPTED_BY_MERCHANT = $Setting->ACCEPTED_BY_MERCHANT;
        $COMPLETED = $Setting->COMPLETED;
        $CANCELLED = $Setting->CANCELLED;
        $RETURNED = $Setting->RETURNED;


        $Client->setRequestUrl($url_organization);
        $Body_organization = $Client->requestGet()->rows;



        $Client->setRequestUrl($url_saleschannel);
        $Body_saleschannel = $Client->requestGet()->rows;



        $Client->setRequestUrl($url_project);
        $Body_project = $Client->requestGet()->rows;


        return view('web.Setting_main',['Body' => $Body,
            "Body_organization" => $Body_organization,
            "Body_saleschannel" => $Body_saleschannel,
            "Body_project" => $Body_project,
            "TokenKaspi" => $TokenKaspi,
            "Organization" => $Organization,
            "PaymentDocument" => $PaymentDocument,
            "Document" => $Document,
            "PaymentAccount" => $PaymentAccount,
            "Saleschannel" => $Saleschannel,
            "Project" => $Project,
            "CheckCreatProduct" => $CheckCreatProduct,
            "APPROVED_BY_BANK" => $APPROVED_BY_BANK,
            "ACCEPTED_BY_MERCHANT" => $ACCEPTED_BY_MERCHANT,
            "COMPLETED" => $COMPLETED,
            "CANCELLED" => $CANCELLED,
            "RETURNED" => $RETURNED,
            "apiKey" => $TokenMoySklad,
            'accountId' => $accountId,

            'error' => $error,
        ]);

    }

    public function postFormSetting(Request $request, $accountId){
        $Setting = $request;
        $TokenKaspi = $request->TokenKaspi;
        $MessageKaspi = $this->saveApiKey($TokenKaspi);
        if ($MessageKaspi["StatusCode"] == 200 ) {
            $request->request->add(["error"=>"0"]);
            $message = $this->updateSetting($accountId, $Setting);
            return redirect()->route("Setting_Main", ["accountId" => $accountId, "error"=>"0" ]);
        } else {
            /*$_SESSION["error"] = $MessageKaspi["API"];
            return Redirect::back()->with('error', $MessageKaspi["API"]);*/
            $request->request->add(["error"=>$MessageKaspi["API"]]);
            return redirect()->route("Setting_Main", ["accountId" => $accountId, "error"=>$MessageKaspi["API"] ]);
        }
/*->withErrors(['password' => ['Invalid Username or Password']]);*/


    }

    public function updateSetting($accountId ,$Setting){
        $cfg = new cfg();
        $appId = $cfg->appId;
        $app = AppInstanceContoller::loadApp($appId, $accountId);
        $app->TokenKaspi = $Setting->TokenKaspi;
        $app->Organization = $Setting->Organization;
        $app->PaymentDocument = $Setting->PaymentDocument;
        $app->Document = $Setting->Document;
        if ($Setting->PaymentDocument == "2") $app->PaymentAccount = $Setting->PaymentAccount;
        else $app->PaymentAccount = null;

        if ($Setting->Saleschannel == 0) $app->Saleschannel = null;
        else $app->Saleschannel = $Setting->Saleschannel;

        if ($Setting->Project == 0) $app->Project = null;
        else $app->Project = $Setting->Project;

        $app->CheckCreatProduct = $Setting->CheckCreatProduct;

        if ($Setting->APPROVED_BY_BANK == "Статус МойСклад") $app->APPROVED_BY_BANK = null;
        else $app->APPROVED_BY_BANK = $Setting->APPROVED_BY_BANK;

        if ($Setting->ACCEPTED_BY_MERCHANT == "Статус МойСклад") $app->ACCEPTED_BY_MERCHANT = null;
        else $app->ACCEPTED_BY_MERCHANT = $Setting->ACCEPTED_BY_MERCHANT;

        if ($Setting->APPROVED_BY_BANK == "Статус МойСклад") $app->APPROVED_BY_BANK = null;
        else $app->APPROVED_BY_BANK = $Setting->APPROVED_BY_BANK;

        if ($Setting->COMPLETED == "Статус МойСклад") $app->COMPLETED = null;
        else $app->COMPLETED = $Setting->COMPLETED;

        if ($Setting->CANCELLED == "Статус МойСклад") $app->CANCELLED = null;
        else $app->CANCELLED = $Setting->CANCELLED;

        if ($Setting->RETURNED == "Статус МойСклад") $app->RETURNED = null;
        else $app->RETURNED = $Setting->RETURNED;

        $app->status = AppInstanceContoller::ACTIVATED;

        $vendorAPI = new VendorApiController();
        $vendorAPI->updateAppStatus($appId, $accountId, $app->getStatusName());

        $app->persist();
        $message = "Настройки сохранились";
        return $message;
    }

    public function saveApiKey(string $API_KEY){
        $url = "https://kaspi.kz/shop/api/v2/orders?filter[orders][code]=21";
        $status = new KaspiApiClient($url,$API_KEY);
        $message = $status->CheckAndSaveApiKey();
        return $message;
    }
}
