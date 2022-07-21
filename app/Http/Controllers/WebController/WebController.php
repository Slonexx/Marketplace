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

        $cfg = new cfg();

        $contextKey = $request->contextKey;
        dd($request);
        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);

        $appId = $cfg->appId;
        $accountId = $employee->accountId;

        $app = AppInstanceContoller::loadApp($appId, $accountId);
        dd($app);
        $_SESSION['cfg'] = $cfg;

        //dd($app);

        return view('web.index');
    }


}

