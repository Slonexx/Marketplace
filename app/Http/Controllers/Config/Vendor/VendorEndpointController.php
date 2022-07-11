<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorEndpointController extends Controller
{
    public function Activate(){

        require_once 'lib.php';

        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['PATH_INFO'];

        loginfo("MOYSKLAD => APP", "Received: method=$method, path=$path");

        dd();

    }
}
