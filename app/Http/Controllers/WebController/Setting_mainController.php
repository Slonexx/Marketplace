<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KaspiApiClient;
use Illuminate\Http\Request;

class Setting_mainController extends Controller
{
    public function index(){
        return view('web.setting_main');
    }

    public function saveApiKey(Request $request){
        session_start();
        $url = "https://kaspi.kz/shop/api/products/classification/attributes?c=Master";
/*
        $headers = [
            'Accept' => "application/json",
            'X-Auth-Token' => $request->API_KEY,
        ];

        $opts = array(
            'http' => array(
                'method' => 'GET',
                'header' => $headers,
                'content' => "",
                'ignore_errors' => true
            )
        );

        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);

        preg_match('/([0-9])\d+/',$http_response_header[0],$matches);
        $statusCode = intval($matches[0]);*/


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
