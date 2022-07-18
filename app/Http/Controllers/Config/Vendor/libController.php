<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;

require_once 'jwt.lib.php';


class libController extends Controller
{

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

$cfg = new AppConfig(require(public_path() . '/Config/' . 'config.php'));

function cfg(): AppConfig {
    return $GLOBALS['cfg'];
}
