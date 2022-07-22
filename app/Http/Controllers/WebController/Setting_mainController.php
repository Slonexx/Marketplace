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
    public function index($id){

        $url = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $Setting = new getSettingVendorController($id);
        $apiKey = $Setting->TokenMoySklad;



        $colorMC = [
            10066329 => "gray",
            15280409 => "red",
            15106326 => "orange",
            6900510 => "saddlebrown",
            12430848 => "darkolivegreen",
            10667543 => "lawngreen",
            8825440 => "darkseagreen",
            34617 => "green",
            8767198 => "cadetblue",
            40931 => "deepskyblue",
            4354177 => "rgb(66, 112, 129)",
            18842 => "blue",
            15491487 => "rgb(236, 97, 159)",
            10774205 => "rgb(164, 102, 189)",
            9245744 => "rgb(141, 20, 48)",
            0 => "black",
        ];

        $Client = new ApiClientMC($url, $apiKey);
        $Body = $Client->requestGet()->states;
        $setBackground = array();

        foreach ($Body as $item){
            $color = $item->color;
            foreach ($colorMC as $itemcolormc=>$indexcolor){
                if ($color == $itemcolormc) $setBackground[] = $indexcolor;
            }
        }

        $url_organization = "https://online.moysklad.ru/api/remap/1.2/entity/organization";
            $Client->setRequestUrl($url_organization);
            $Body_organization = $Client->requestGet()->rows;


        $url_saleschannel = "https://online.moysklad.ru/api/remap/1.2/entity/saleschannel";
            $Client->setRequestUrl($url_saleschannel);
            $Body_saleschannel = $Client->requestGet()->rows;


        $url_project = "https://online.moysklad.ru/api/remap/1.2/entity/project";
            $Client->setRequestUrl($url_project);
            $Body_project = $Client->requestGet()->rows;

        return view('web.Setting_main',['Body' => $Body,
            "setBackground" => $setBackground,
            "Body_organization" => $Body_organization,
            "Body_saleschannel" => $Body_saleschannel,
            "Body_project" => $Body_project,
            "apiKey" => $apiKey,
            'id' => $id,
        ]);

    }

    public function postFormSetting(Request $request, $id){


        $check = $request->request;
        $API_KEY = $request->TokenKaspi;
        dd($check);

        $message = $this->saveApiKey($API_KEY);


        Session::flash('message', $message["API"]);
        if ($message["StatusCode"] == 200 ) {
            Session::flash('alert-class', 'alert-success');
        }
        else {
            Session::flash('alert-class', 'alert-danger');
        }

        //dd($check);
        Session::flash('error', 'Error message here');

        $this->updateSetting($id);

        return Redirect::back();

    }

    public function updateSetting($contextKey){
        $cfg = new cfg();
        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);

        $appId = $cfg->appId;
        $accountId = $employee->accountId;

        $app = AppInstanceContoller::loadApp($appId, $accountId);
        $app->infoMessage = "Hello world";

        $notify = $app->status != AppInstanceContoller::ACTIVATED;
        $app->status = AppInstanceContoller::ACTIVATED;
        $vendorAPI->updateAppStatus($appId, $accountId, $app->getStatusName());

        $app->persist();

    }

    public function saveApiKey(string $API_KEY){
        $url = "https://kaspi.kz/shop/api/products/classification/attributes?c=Master";
        $status = new KaspiApiClient($url,$API_KEY);
        $message = $status->CheckAndSaveApiKey();
        return $message;
    }
}
