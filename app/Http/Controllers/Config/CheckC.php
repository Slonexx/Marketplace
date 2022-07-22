<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckC extends Controller
{
    public function index($accountId){
        $cfg = new cfg();

        $appId = $cfg->appId;

        $app = AppInstanceContoller::loadApp($appId, $accountId);
        dd($app);
    }
}
