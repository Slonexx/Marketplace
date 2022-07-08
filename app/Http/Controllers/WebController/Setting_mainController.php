<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KaspiApiClient;
use Illuminate\Http\Request;

class Setting_mainController extends Controller
{
    public function index(){
        return view('web.Setting_main');
    }

    public function saveApiKey(Request $request){

        $url = "https://kaspi.kz/shop/api/products/classification/attributes?c=Master";
        $API_KEY = $request->API_KEY;
        $status = new KaspiApiClient($url,$API_KEY);
        $message = $status->CheckAndSaveApiKey();

            return response($message);

        //return back();
       // dd( $_SESSION["API_KEY"]);
    }

}
