<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorEndpointController extends Controller
{
    public function Activate(){
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['PATH_INFO'];

        dd();

    }
}
