<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function view;


class WebController extends Controller
{
    public function index(Request $request){

        $contextKey = $request->contextKey;
        $START = new getSettingVendorController();
        $START->setAll($contextKey);
        return redirect()->route('Index', ['id' => $contextKey, 'START'=> $START] );

    }

    public function show(Request $request, $id){
        dd($request);
        $START = app(getSettingVendorController::class);
        $appId = $START->appId;
        $accountId = $START->accountId;



        return view('web.index', ['id' => $id,  'appId'=> $appId, 'accountId'=> $accountId]);
    }


}

