<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use App\Http\Controllers\Config\Vendor\VendorEndpointController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WebController extends Controller
{
    public function index(){
        $path = $_SERVER['PATH_INFO'];
        dd($path);

        return view('web.index');
    }


}
