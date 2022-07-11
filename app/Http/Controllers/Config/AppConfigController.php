<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;


class AppConfigController extends Controller
{

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

    public function newCFG(){
        $cfg = new AppConfigController(require('config.php'));
        return $cfg;
    }
}
