<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function init(){
        $apiKey = "730702f863b502da47e8aca4dfa26fce798f86f2";
       $res = app(SessionController::class)->SessionInitialization($apiKey);

        return session_encode();
    }
}
