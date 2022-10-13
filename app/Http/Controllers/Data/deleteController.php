<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class deleteController extends Controller
{
    public function delete($accountId)
    {
        $cfg = new cfg();
        try {
            $path = public_path().'/Config/data/'.$cfg->appId.".".$accountId.'.json';
            unlink($path);
        } catch (\Exception $e) {

        }
    }
}
