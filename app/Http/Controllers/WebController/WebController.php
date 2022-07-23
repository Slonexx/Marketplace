<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\AttributeController;
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

        $Setting = new getSettingVendorController($accountId);
        $TokenMoySklad = $Setting->TokenMoySklad;

        $att = new AttributeController();
        $att->createProductAttributes($TokenMoySklad);

        return redirect()->route('Index', ['accountId' => $accountId] );

    }

    public function show($accountId){



        return view('web.index', ['accountId' => $accountId] );
    }


}

