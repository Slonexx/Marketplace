<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function view;


class WebController extends Controller
{
    public function index(Request $request){
        session_start();

        $contextKey = $request->contextKey;
        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);
        $accountId = $employee->accountId;

        return redirect()->route('Index', ['id' => $contextKey, 'accountId' => $accountId] );

    }

    public function show(Request $request, $id){
        $accountId = $request->accountId;
        dd($accountId);


        return view('web.index', ['id' => $id, 'accountId' => $accountId]);
    }


}

