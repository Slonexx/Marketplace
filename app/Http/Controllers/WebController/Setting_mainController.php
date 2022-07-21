<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\ApiClientMC;
use App\Http\Controllers\Controller;
use App\Http\Controllers\KaspiApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Setting_mainController extends Controller
{
    public function index($id){

        $url = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $apiKey = "8eb0e2e3fc1f31effe56829d5fdf60444d2e3d3f";


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
            $Client = new ApiClientMC($url_organization, $apiKey);
            $Body_organization = $Client->requestGet()->rows;

        return view('web.Setting_main',['Body' => $Body,
            "setBackground" => $setBackground,
            "Body_organization" => $Body_organization,
            'id' => $id,
        ]);


//        dd($Body);
        //return view('web.Setting_main');
    }

    public function postFormSetting(Request $request, $id){

        /*$date = $request->document;
        dd($date);*/


        $API_KEY = $request->API_KEY;
        $message = $this->saveApiKey($API_KEY);


        Session::flash('message', $message["API"]);
        if ($message["StatusCode"] == 200 ) {
            Session::flash('alert-class', 'alert-success');
        }
        else {
            Session::flash('alert-class', 'alert-danger');
        }
        $check = $request->request;
        //dd($check);
        Session::flash('error', 'Error message here');


        return Redirect::back();
    }


    public function saveApiKey(string $API_KEY){
        $url = "https://kaspi.kz/shop/api/products/classification/attributes?c=Master";
        $status = new KaspiApiClient($url,$API_KEY);
        $message = $status->CheckAndSaveApiKey();
        return $message;
    }
}
