<?php

namespace App\Http\Controllers\Widget\customerorder;

use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getWorkerID;
use App\Http\Controllers\getData\getWorkers;
use Illuminate\Http\Request;

class customerorderEditController extends Controller
{
    public function customerorder(Request $request){

        $contextKey = $request->contextKey;
        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);
        $accountId = $employee->accountId;

        $entity = 'counterparty';

        return view( 'widget.customerorder', [
            'accountId' => $accountId,
            'entity' => $entity,
        ] );
    }
}
