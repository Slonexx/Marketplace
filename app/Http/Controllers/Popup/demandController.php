<?php

namespace App\Http\Controllers\Popup;

use App\Clients\MsClient;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;

use App\Services\ticket\TicketService;
use Illuminate\Http\Request;

class demandController extends Controller
{
    public function demandPopup(Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {

        return view( 'popup.demand', [] );
    }

    public function ShowDemandPopup(Request $request): \Illuminate\Http\JsonResponse
    {
        $object_Id = $request->object_Id;
        $accountId = $request->accountId;
        $Setting = new getSetting($accountId);

        $json = $this->info_object_Id($object_Id, $Setting);

        $payment_type = $Setting->payment_type;
        if ($payment_type == null or $payment_type == '') $payment_type = 1;

        $json['application']['payment_type'] = (int) $payment_type ;

        return response()->json($json);
    }

    public function info_object_Id($object_Id, $Setting){
        $url = "https://online.moysklad.ru/api/remap/1.2/entity/demand/".$object_Id;
        $Client = new MsClient($Setting->tokenMs);
        $Body = $Client->get($url);
        $attributes = null;
        if (property_exists($Body, 'attributes')){
            $attributes = [
                'ticket_id' => null,
            ];
            foreach ($Body->attributes as $item){
                if ($item->name == 'id-билета (ReKassa)'){
                    $attributes['ticket_id'] = $item->value;
                    break;
                }
            }
        }
        $vatEnabled = $Body->vatEnabled;
        $vat = null;
        $products = [];
        $positions = $Client->get($Body->positions->meta->href)->rows;

        foreach ($positions as $id=>$item){
            $final = $item->price / 100 * $item->quantity;

            if ($vatEnabled == true) {if ($Body->vatIncluded == false) {
                $final = $item->price / 100 * $item->quantity;
                $final = $final + ( $final * ($item->vat/100) );
            }}
            $uom_body = $Client->get($item->assortment->meta->href);

            if (property_exists($uom_body, 'uom')){
                $propety_uom = true;
                $uom = $Client->get($uom_body->uom->meta->href);
                $uom = ['id' => $uom->code, 'name' => $uom->name];
            } else {

                if (property_exists($uom_body, 'characteristics')){
                    $check_uom = $Client->get($uom_body->product->meta->href);

                    if ( property_exists($check_uom, 'uom') ) {
                        $propety_uom = true;
                        $uom = $Client->get($check_uom->uom->meta->href);
                        $uom = ['id' => $uom->code, 'name' => $uom->name];
                    } else {
                        $propety_uom = false;
                        $uom = ['id' => 796, 'name' => 'шт'];
                    }
                } else {
                    $propety_uom = false;
                    $uom = ['id' => 796, 'name' => 'шт'];
                }
            }

            $trackingCodes = false;
            if (property_exists($item,'trackingCodes')){
                $trackingCodes = true;
            }

            $products[$id] = [
                'position' => $item->id,
                'propety' => $propety_uom,
                'name' => $Client->get($item->assortment->meta->href)->name,
                'quantity' => $item->quantity,
                'uom' => $uom,
                'trackingCodes' => $trackingCodes,
                'price' => round($item->price / 100, 2) ?: 0,
                'vatEnabled' => $item->vatEnabled,
                'vat' => $item->vat,
                'discount' => round($item->discount, 2),
                'final' => round($final - ( $final * ($item->discount/100) ), 2),
            ];
        }

        if ($vatEnabled == true) {
            $vat = [
                'vatEnabled' => $Body->vatEnabled,
                'vatIncluded' => $Body->vatIncluded,
                'vatSum' => $Body->vatSum / 100 ,
            ];
        };
        return [
            'id' => $Body->id,
            'name' => $Body->name,
            'sum' => $Body->sum / 100,
            'vat' => $vat,
            'attributes' => $attributes,
            'products' => $products,
        ];
    }


    public function SendDemandPopup(Request $request): \Illuminate\Http\JsonResponse
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

        try {

            $res = app(TicketService::class)->createTicket($data);
            return response()->json($res);

        } catch (\Throwable $e){
            return response()->json($e->getMessage());
        }
    }

}
