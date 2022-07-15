<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use AppInstance;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Firebase\JWT\JWT;
use JetBrains\PhpStorm\Pure;

class VendorEndpointController extends Controller
{
    public function Activate()
    {

        require(public_path() . '/Config/' . 'lib.php');
        $contextName = 'IFRAME';
        require(public_path() . '/Config/' . 'user-context-loader.inc.php');
        $app = AppInstance::loadApp($accountId);


        dd($app);


    }
}
