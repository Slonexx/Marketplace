<?php

namespace App\Http\Controllers\WebController;

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

        session_start();
        $cfg = new cfg();
        $_SESSION['cfg'] = $cfg;

        $contextKey = $request->contextKey;
        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);

        $appId = $cfg->appId;
        $accountId = $employee->accountId;

        $app = AppInstanceContoller::loadApp($appId, $accountId);
        $_SESSION['cfg'] = $cfg;

        //dd("Все прошло успешно");

        return view('web.index');
    }


    public function newCfg(){
        return new AppConfigController( require(public_path().'/Config'.'/config.php') );
    }

}

