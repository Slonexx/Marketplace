<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Config\loginfoController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorEndpointController extends Controller
{
    public $path;

    /**
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function Activate(){
        dd($this->path);
    }
}
