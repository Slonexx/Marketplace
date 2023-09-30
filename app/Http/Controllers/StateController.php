<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\StatusController;

class StateController extends Controller
{

    public function getState($accountId,$statusKaspi,$apiKey){

        $status = app(StatusController::class)->getStatusName($accountId,$statusKaspi);

        if($status == null){
            return null;
        } else {
            $uri = "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
            $client = new ApiClientMC($uri,$apiKey);
            $jsonStates = $client->requestGet();
            $foundedState = null;
            foreach($jsonStates->states as $state){
                if($state->name == $status){
                    $foundedState = $state->meta;
                    break;
                }
            }
            return $foundedState;
        }
    }
}
