<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use Firebase\JWT\JWT;

class VendorEndpointController extends Controller
{
    public function Activate(){

        $this->cfg();

    }

    public function cfg(){
        $cfg = new AppConfig(require(public_path().'/Config/'.'config.php'));
        dd($cfg);
    }


}

class AppConfig {

    var $appId = 'APP-ID';
    var $appUid = 'APP-UID';
    var $secretKey = 'SECRET-KEY';

    var $appBaseUrl = 'APP-BASE-URL';

    var $moyskladVendorApiEndpointUrl = 'https://online.moysklad.ru/api/vendor/1.0';
    var $moyskladJsonApiEndpointUrl = 'https://online.moysklad.ru/api/remap/1.2';

    public function __construct(array $cfg)
    {
        foreach ($cfg as $k => $v) {
            $this->$k = $v;
        }
    }
}


