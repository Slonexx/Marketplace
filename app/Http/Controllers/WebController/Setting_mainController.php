<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KaspiApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

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
        Session::flash('message', $message["API"]);
        if ($message["StatusCode"] == 200 ) {
            Session::flash('alert-class', 'alert-success');
        }
        else {
            Session::flash('alert-class', 'alert-danger');
        }
        return Redirect::back();
           // return view('web.support');


          //  return response($message);

        //return back();
       // dd( $_SESSION["API_KEY"]);
    }

}
