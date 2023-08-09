<?php

namespace App\Http\Controllers\Popup;

use App\Clients\MsClient;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;

use App\Services\ticket\DevTicketService;
use App\Services\ticket\TicketService;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function SendRequestPopup(Request $request): \Illuminate\Http\JsonResponse
    {
        $accountId = $request->accountId;
        $object_Id = $request->object_Id;
        $entity_type = $request->entity_type;
        if ($request->money_card === null) $money_card = 0;
        else $money_card = $request->money_card;

        if ($request->money_cash === null) $money_cash = 0;
        else $money_cash = $request->money_cash;

        if ($request->money_mobile === null) $money_mobile = 0;
        else $money_mobile = $request->money_mobile;

        if ($request->total === null) $total = 0;
        else $total = $request->total;


        $pay_type = $request->pay_type;

        $position = json_decode($request->position);

        $positions = [];
        foreach ($position as $item){
            if ($item != null){
                $positions[] = $item;
            }
        }




        $data = [
            'accountId' => $accountId,
            'id_entity' => $object_Id,
            'entity_type' => $entity_type,

            'money_card' => $money_card,
            'money_cash' => $money_cash,
            'money_mobile' => $money_mobile,

            'total' => $total,
            'pay_type' => $pay_type,

            'positions' => $positions,
        ];

        //dd($data);

        return response()->json(app(DevTicketService::class)->createTicket($data));

    }

}
