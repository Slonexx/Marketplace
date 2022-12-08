<?php

namespace App\Http\Controllers\Web;

use App\Clients\KassClient;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getDeviceFirst;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getSetting;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class changeController extends Controller
{

    public function getChange(Request $request, $accountId): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $isAdmin = $request->isAdmin;

        $Device = new getDevices($accountId);

        return view('main.change', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,

            'kassa' => $Device->devices,
        ]);
    }

    public function getInfoIdShift(Request $request, $accountId): \Illuminate\Http\JsonResponse
    {
        $znm = $request->znm;
        $password = $request->password;

        try {
            $Setting = new getSetting($accountId);

            $clientK = new KassClient($znm, $password, $Setting->apiKey);
            $id = $clientK->getNewJwtToken()->id;

            $idShift = $clientK->get("crs/".$id."/shifts?includeOpen=true&size=1");
            $includeOpen_true = $idShift->_embedded->shifts[0]->shiftNumber;

            $idShift = $clientK->get("crs/".$id."/shifts?includeOpen=false&size=1");
            $includeOpen_false = $idShift->_embedded->shifts[0]->shiftNumber;

            $status = null;
            if ($includeOpen_true == $includeOpen_false){ $status = false; }
            else { $status = true; }


            return response()->json([
                'status' => $status,
                'code' => 200
            ]);
        } catch (BadResponseException $e){
            return response()->json([
                'status' => false,
                'code' => 400,
                'message' => json_decode($e->getResponse()->getBody()->getContents())->message,
            ]);
        }
    }

    public function getXReport(Request $request, $accountId): \Illuminate\Http\JsonResponse
    {

        $znm = $request->znm;
        $password = $request->password;

        $Device = new getDevices($accountId);
        if ($request->znm == null){
            $znm = $Device->devices[0]->znm;
            $password = $Device->devices[0]->password;
        }

        $Setting = new getSetting($accountId);

        $clientK = new KassClient($znm, $password, $Setting->apiKey);
        $id = $clientK->getNewJwtToken()->id;

        $idShift = $clientK->get("crs/".$id."/shifts?includeOpen=true&size=1");
        $idShift = $idShift->_embedded->shifts[0]->shiftNumber;
        $link = 'https://app.rekassa.kz/shifts/'.$id.'/'.$idShift.'/zxreport';

        return response()->json([
            'link' => $link
        ]);
    }

    public function getZReport(Request $request, $accountId): \Illuminate\Http\JsonResponse
    {

        $cash_register_password = $request->pin_code;
        $znm = $request->znm;
        $password = $request->password;

        $Device = new getDevices($accountId);
        if ($request->znm == null){
            $znm = $Device->devices[0]->znm;
            $password = $Device->devices[0]->password;
        }

        $Setting = new getSetting($accountId);
        try {

            $clientK = new KassClient($znm, $password, $Setting->apiKey);
            $id = $clientK->getNewJwtToken()->id;

            $idShift = $clientK->get("crs/".$id."/shifts?includeOpen=true&size=1");
            $idShift = $idShift->_embedded->shifts[0]->shiftNumber;

            $kassClient = new KassClient($znm, $password, $Setting->apiKey);
            $response = $kassClient->postWithHeaders('crs/'.$id.'/shifts/'.$idShift.'/close',[
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$kassClient->getNewJwtToken()->token,
                'cash-register-password' => $cash_register_password,
            ]);

            $link = 'https://app.rekassa.kz/shifts/'.$id.'/'.$idShift.'/zxreport';

            return response()->json([
                'status' => true,
                'link' => $link
            ]);

        } catch (BadResponseException $e){
            return response()->json([
                'status' => false,
                'message' => json_decode($e->getResponse()->getBody()->getContents(), true),
            ]);
        }
    }

    public function postChange(Request $request, $accountId){

        $operations = $request->operations;
        $znm = $request->znm;
        $password = $request->password;
        $sum = $request->sum;

        if ($sum != null and $sum != ''){
            $bills = intval($sum);
            $coins = intval(round(floatval($sum)-intval($sum),2)*100);
        } else {
            $bills = 0;
            $coins = 0;
        }

        $name_operations = "";
        $message_operations = "";

        if ($operations == 1 or $operations == '1'){
            $name_operations = "MONEY_PLACEMENT_DEPOSIT";
            $message_operations = 'Внесение наличных в кассу: ';
        } else if ($operations == 2 or $operations == '2'){
            $name_operations = "MONEY_PLACEMENT_WITHDRAWAL";
            $message_operations = 'Изъятие наличных в кассу: ';
        }

        $Device = new getDevices($accountId);
        if ($request->znm == null){
            $znm = $Device->devices[0]->znm;
            $password = $Device->devices[0]->password;
        }

        $Setting = new getSetting($accountId);

        $mytime = date('Y-m-d H:i:s');
        //dd($mytime);


        try {

            $clientK = new KassClient($znm, $password, $Setting->apiKey);
            $id = $clientK->getNewJwtToken()->id;

            $body = [
                'datetime'=> [
                    'date' => [
                        'year' => (int) date('Y'),
                        'month' => (int) date('m'),
                        'day' => (int) date('d'),
                    ],
                    'time' => [
                        'hour' => (int) date('H')+6,
                        'minute' => (int) date('i'),
                        'second' => (int) date('s'),
                        ],
                ],
                'operation' => $name_operations,
                'sum' => [
                    'bills' => (int) $bills,
                    'coins' => (int) $coins,
                ]
            ];

            $idShift = $clientK->post("crs/".$id."/cash", $body);

            return response()->json([
                'status' => true,
                'message_good' => $message_operations.' '.$sum,
            ]);

        } catch (BadResponseException $e){
            return response()->json([
                'status' => false,
                'message' => json_decode($e->getResponse()->getBody()->getContents(), true),
            ]);
        }
    }

}
