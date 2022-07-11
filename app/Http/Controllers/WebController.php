<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use App\Http\Controllers\Config\Vendor\VendorEndpointController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WebController extends Controller
{
    public function index(){

        $contextName = 'IFRAME';

        $Vendor = app(VendorEndpointController::class);

        $Vendor->VendorActive();

        dd();


        //return view('web.index');
    }


}
