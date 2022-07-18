<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;

require_once 'jwt.lib.php';


class libController extends Controller
{

    public function index(){

        $Class = app(GlobalVariables::class);
        $path = $Class->getPath();
        dd($path);



    }

    public function newCFG(){
        $cfg = new AppConfig(require(public_path() . '/Config/' . 'config.php'));
    }

    public function newVendorApi(){
        $vendorApi = new VendorApi();
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

class VendorApi {

    function context(string $contextKey) {
        return $this->request('POST', '/context/' . $contextKey);
    }

    function updateAppStatus(string $appId, string $accountId, string $status) {
        return $this->request('PUT',
            "/apps/$appId/$accountId/status",
            "{\"status\": \"$status\"}");
    }

    private function request(string $method, $path, $body = null) {
        return makeHttpRequest(
            $method,
            cfg()->moyskladVendorApiEndpointUrl . $path,
            buildJWT(),
            $body);
    }

}


