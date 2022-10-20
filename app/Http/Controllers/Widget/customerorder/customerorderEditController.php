<?php

namespace App\Http\Controllers\Widget\customerorder;

use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getDeviceFirst;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getMainSetting;
use App\Http\Controllers\getData\getSetting;
use App\Http\Controllers\getData\getWorkerID;
use App\Http\Controllers\getData\getWorkers;
use App\Models\mainSetting;
use Illuminate\Http\Request;

class customerorderEditController extends Controller
{
    public function customerorder(Request $request){

        //$contextKey = $request->contextKey;
        $vendorAPI = new VendorApiController();
        //$employee = $vendorAPI->context($contextKey);
        //$accountId = $employee->accountId;
        $accountId = "1dd5bd55-d141-11ec-0a80-055600047495";

        $Devices = new getDevices($accountId);
        if ($Devices->devices) $tmp = "yes";
        else $tmp = "no";
        dd($tmp);


        $entity = 'counterparty';

        return view( 'widget.customerorder', [
            'accountId' => $accountId,
            'entity' => $entity,
        ] );
    }
}
