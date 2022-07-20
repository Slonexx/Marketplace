<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\Lib\AppConfigController;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Config\SessionController;
use Illuminate\Http\Request;


class WebController extends Controller
{
    public function index(Request $request){
        session_start();
        $cfg = $this->newCfg();
        $_SESSION['cfg'] = $cfg;


        $contextKey = $request->contextKey;
        //dd($request->contextKey);
        $employee = new VendorApiController();
        dd($employee->context($contextKey));


        return view('web.index');
    }


    public function newCfg(){
        return new AppConfigController( require(public_path().'/Config'.'/config.php') );
    }

}

