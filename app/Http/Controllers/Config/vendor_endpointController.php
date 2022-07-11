<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class vendor_endpointController extends Controller
{

    public function VendorActive(){
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['PATH_INFO'];

        dd($method);

    }

}
