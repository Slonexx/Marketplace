<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getMainSetting;
use Illuminate\Http\Request;

class mainController extends Controller
{
    public function index(Request $request, $accountId){
        $isAdmin = $request->isAdmin;
        $mainSetting = new getMainSetting($accountId);

        if (isset($request->message)) $message = $request->message;
        else $message = null;

        return view('setting.main',[
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
            'TokenKaspi' => $mainSetting->TokenKaspi,

            'message' => $message
        ]);

    }
}
