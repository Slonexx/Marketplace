<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckC extends Controller
{
    public function index(Request $request, $id){
        $cfg = new cfg();
        $_SESSION['cfg'] = $cfg;

        $contextKey = $id;

        $vendorAPI = new VendorApiController();
        $employee = $vendorAPI->context($contextKey);

        $appId = $cfg->appId;
        $accountId = $employee->accountId;

        $app = AppInstanceContoller::loadApp($appId, $accountId);
        dd($app);
    }
}
