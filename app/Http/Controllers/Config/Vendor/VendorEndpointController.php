<?php

namespace App\Http\Controllers\Config\Vendor;

use App\Http\Controllers\Controller;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Firebase\JWT\JWT;
use JetBrains\PhpStorm\Pure;

class VendorEndpointController extends Controller
{
    public function Activate(){




        $contextKey = $_GET['contextKey'];
        $employee = vendorApi()->context($contextKey);

        $uid = $employee->uid;
        $fio = $employee->shortFio;
        $accountId = $employee->accountId;

        $isAdmin = $employee->permissions->admin->view;
        dd($accountId);





       /* require(public_path().'/Config/'.'vendor-endpoint.php');

        $tmp = new \getInfo();
        $id = $tmp->getAccess_token();
        dd($id);*/
    }




}
