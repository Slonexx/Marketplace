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

        return redirect()->route('Index', ['id' => $contextKey] );

    }

    public function show(Request $request, $id){

        $START = new getSettingVendorController($id);
        $appId = $START->appId;
        $accountId = $START->accountId;
        dd($START);


        return view('web.index', ['id' => $id,  'appId'=> $appId, 'accountId'=> $accountId]);
    }


}

