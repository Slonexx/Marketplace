<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StateController extends Controller
{
    # APPROVED_BY_BANK – одобрен банком
    #CANCELLED – отменён
    #CANCELLING – ожидает отмены
    #ACCEPTED_BY_MERCHANT– принят на обработку продавцом
    #COMPLETED – завершён
    public function getState($statusOrder,$apiKey){

        $status = null;

        switch ($statusOrder) {
            case 'APPROVED_BY_BANK':
              $status = "Новый";
            break;
            case 'ACCEPTED_BY_MERCHANT':
              $status = "Подтвержден";
            break;
            case 'CANCELLED':
                case 'CANCELLING':
              $status = "Отменен";
            break;
            case 'COMPLETED':
              $status = "Доставлен";
            break;
            default:
              $status = "Новый";
            break;
        }

        $uri = "https://online.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
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
