<?php

namespace App\Http\Controllers\Config\Lib;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class cfg extends Controller
{
    public $appId;
    public $appUid;
    public $secretKey;
    public $appBaseUrl;
    public $moyskladVendorApiEndpointUrl;
    public $moyskladJsonApiEndpointUrl;


    public function __construct()
    {
        $this->appId = 'ac217934-edaf-4975-91ae-a0ea408727de';
        $this->appUid = 'kaspi.smartinnovations';
        $this->secretKey = "MfMotwWe278gFKnpspgxLeWyNkr8eAc4upnz9MJtCIyX9N41rOcKkuYJnIR8WHguSSh5kPUzkWbsFoVh62UzamYYIbJHHQGFuGUSTETgVw304VGvDHKB54TtxsRG6l0U";
        $this->appBaseUrl = 'https://smartkaspi.kz/';
        $this->moyskladVendorApiEndpointUrl = 'https://apps-api.moysklad.ru/api/vendor/1.0';
        $this->moyskladJsonApiEndpointUrl = 'https://api.moysklad.ru/api/remap/1.2';
    }


}
