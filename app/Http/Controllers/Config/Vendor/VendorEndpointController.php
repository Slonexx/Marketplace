<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use AppInstance;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Firebase\JWT\JWT;
use JetBrains\PhpStorm\Pure;

class VendorEndpointController extends Controller
{
    public function Activate(){
        require(public_path().'/Config/'.'lib.php');
        $contextKey = $_GET['contextKey'];

        //$employee = vendorApi()->context($contextKey);



        dd($contextKey);


        /*require(public_path().'/Config/'.'lib.php');
        $contextName = 'IFRAME';
        require(public_path().'/Config/'.'user-context-loader.inc.php');
        $app = AppInstance::loadApp($accountId);
        dd($accountId);*/

       /* require(public_path().'/Config/'.'vendor-endpoint.php');

        $tmp = new \getInfo();
        $id = $tmp->getAccess_token();
        dd($id);*/
    }



}
