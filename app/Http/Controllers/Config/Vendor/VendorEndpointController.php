<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Config\loginfoController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorEndpointController extends Controller
{
    public function Activate(){
        return view('vendor-endpoint');
    }

}
