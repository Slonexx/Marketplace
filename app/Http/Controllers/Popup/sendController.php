<?php

namespace App\Http\Controllers\Popup;

use App\Clients\KassClient;
use App\Http\Controllers\BD\getMainSettingBD;
use App\Http\Controllers\Controller;
use App\Http\Controllers\globalObjectController;
use App\Http\Controllers\TicketController;
use App\Models\zHtmlResponce;
use App\Services\ticket\dev_CreateTicketService;
use App\Services\ticket\dev_TicketService;
use App\Services\ticket\TestTicketService;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class sendController extends Controller
{


    public function DevRequest(Request $request){
        $accountId = $request->accountId;
        $id_entity = $request->id_entity;
        $entity_type = $request->entity_type;
        if ($request->money_card === null) $money_card = 0;
        else $money_card = $request->money_card;

        if ($request->money_cash === null) $money_cash = 0;
        else $money_cash = $request->money_cash;

        if ($request->money_mobile === null) $money_mobile = 0;
        else $money_mobile = $request->money_mobile;

        if ($request->total === null) $total = 0;
        else $total = $request->total;

        $total = $request->total;
        $pay_type = $request->pay_type;

        $position = json_decode(($request->positions));

        $body = [
            'accountId' => $accountId,
            'id_entity' => $id_entity,
            'entity_type' => $entity_type,

            'money_card' => $money_card,
            'money_cash' => $money_cash,
            'money_mobile' => $money_mobile,
            'pay_type' => $pay_type,

            'total' => $total,

            'positions' => $position,
        ];

            $ticket = json_decode(json_encode((app(TestTicketService::class)->createTicket($body))));
    }


}
