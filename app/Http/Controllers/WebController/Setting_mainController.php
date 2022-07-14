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
    public function index(){

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

        $url = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $apiKey = "d0a0269f7b388846c9db1dbdc624bd51005efcff";

        $Client = new ApiClientMC($url, $apiKey);
        $Body = $Client->requestGet()->states;
        $setBackground = array();

        foreach ($Body as $item){
            $color = $item->color;
            foreach ($colorMC as $itemcolormc=>$indexcolor){
                if ($color == $itemcolormc) $setBackground[] = $indexcolor;
            }
        }

        // dd($setBackground);
        //dd($Body);



        return view('web.Setting_main',['Body' => $Body,
            "setBackground" => $setBackground]);


//        dd($Body);
        //return view('web.Setting_main');
    }

    public function saveApiKey(Request $request){

        $temp = $request;

        dd($temp);

       /* $url = "https://kaspi.kz/shop/api/products/classification/attributes?c=Master";
        $API_KEY = $request->API_KEY;
        $status = new KaspiApiClient($url,$API_KEY);
        $message = $status->CheckAndSaveApiKey();
        Session::flash('message', $message["API"]);
        if ($message["StatusCode"] == 200 ) {
            Session::flash('alert-class', 'alert-success');
        }
        else {
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::back();*/
           // return view('web.support');


          //  return response($message);

        //return back();
       // dd( $_SESSION["API_KEY"]);
    }

}
