<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use App\Http\Controllers\Config\Vendor\VendorEndpointController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WebController extends Controller
{
    public function index(Request $request){

        $contextKey = $request->contextKey;
        //dd($request->contextKey);
        $employee = new VendorApi();
        dd($employee->context($contextKey));


        return view('web.index');
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
