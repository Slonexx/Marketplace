<?php


namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;

class GlobalVariables extends Controller
{
    public $cfg;
    public $vendorApi;

    public function __construct($cfg, $vendorApi)
    {
        $this->cfg = $cfg;
        $this->vendorApi = $vendorApi;
    }

    public function getCfg()
    {
        return $this->cfg;
    }

    public function getVendorApi()
    {
        return $this->vendorApi;
    }





}
