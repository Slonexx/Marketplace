<?php

namespace App\Http\Controllers\Widget;

use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getDevices;
use App\Http\Controllers\getData\getWorkerID;
use Illuminate\Http\Request;

class salesreturnEditController extends Controller
{
    public function salesreturn(Request $request){
        $contextKey = $request->contextKey;
        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);
        $accountId = $employee->accountId;

        //$Workers = new getWorkerID($employee->id);
        $Devices = new getDevices($accountId);
        if (!$Devices->devices) {
            return view( 'widget.errorCustomOrder', [
                'accountId' => $accountId,
            ] );
        }
        $entity = 'salesreturn';



        return view( 'widget.salesreturn', [
            'accountId' => $accountId,
            'entity' => $entity,
            //'worker' => $Workers->access,
        ] );
    }
}
