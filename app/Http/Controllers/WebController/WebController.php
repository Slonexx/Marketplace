<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\AppConfigController;
use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use function dd;
use function public_path;
use function view;


class WebController extends Controller
{
    public function index(Request $request){

        $cfg = new cfg();

        $contextKey = $request->contextKey;

        $START = new getSettingVendorController();
        $START->setAll($contextKey);

        return redirect()->route('Index', ['id' => $contextKey] );

        //return view('web.index', ['id' => $contextKey ]);
    }

    public function show($id){

        $START = app(getSettingVendorController::class);
        $appId = $START->appId;
        $accountId = $START->accountId;

        return view('web.index', ['id' => $id,  'appId'=> $appId, 'accountId'=> $accountId]);
    }


}

