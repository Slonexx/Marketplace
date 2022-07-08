<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KaspiApiClient;
use Illuminate\Http\Request;

class Setting_mainController extends Controller
{
    public function index(){
        $sessi = session()->all();
        dd($sessi);
        //return view('web.Setting_main');
    }

    public function saveApiKey(Request $request){
        session_start();
        $url = "https://kaspi.kz/shop/api/products/classification/attributes?c=Master";

        $status = new KaspiApiClient($url,$request->API_KEY);
        $statusCode = $status->getStatus(false);

        if ($statusCode == 200){
            $res = "API ключ верный";
            $_SESSION["API_KEY"] = $request->API_KEY;
        } else {
            $res = "API ключ не верный";
        }
            return response($res);

        //return back();
       // dd( $_SESSION["API_KEY"]);
    }
}
