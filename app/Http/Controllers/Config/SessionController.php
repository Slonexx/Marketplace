<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function SessionInitialization($ApiKey){
        session_start();

        $store = app(StoreController::class)->getKaspiStore($ApiKey);

        $result = $_SESSION["store"] = $store;


        /*$Organization = app(OrganizationController::class)->getKaspiOrganization($ApiKey);
        $result = session(["Store" => $store, "Organization" => $Organization, ]);
        session()->save();*/

        return $result;
    }

    public function getCookie($name_cookie){
        session_start();

        if(isset($_SESSION[$name_cookie])) {
            echo ($_SESSION["API_KEY"]);
        }

        return $_SESSION[$name_cookie];
    }



}
