<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\AgentAttributesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OrderAttributesController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PriceTypeController;
use App\Http\Controllers\ProductAttributesController;
use App\Http\Controllers\SalesChannelController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UomController;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function SessionInitialization($ApiKey){
        session_start();
        
        $_SESSION["store"] = app(StoreController::class)->getKaspiStore($ApiKey);
        $_SESSION["pricetype"] = app(PriceTypeController::class)->getPriceType($ApiKey);
        $_SESSION["uom"] = app(UomController::class)->getUom($ApiKey);
        $_SESSION["salechannel"] = app(SalesChannelController::class)->getSaleChannel($ApiKey);
        $_SESSION["organization"] = app(OrganizationController::class)->getKaspiOrganization($ApiKey);
        $_SESSION["currency"] = app(CurrencyController::class)->getKzCurrency($ApiKey);
        $_SESSION["gos_attribute"] = app(AgentAttributesController::class)->getAttributeGos($ApiKey);
        $_SESSION["brand"] = app(ProductAttributesController::class)->getAttribute('brand (KASPI)',$ApiKey);
        $_SESSION["export"] = app(ProductAttributesController::class)->getAttribute('Добавлять товар на Kaspi',$ApiKey);

        /*$Organization = app(OrganizationController::class)->getKaspiOrganization($ApiKey);
        $result = session(["Store" => $store, "Organization" => $Organization, ]);
        session()->save();*/

        //return $result;
    }

    public function getCookie($name_cookie){
        session_start();

        if(isset($_SESSION[$name_cookie])) {
           return $_SESSION[$name_cookie];
        } else {

        }

    }



}
