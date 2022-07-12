<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class user_context_loader_inc_Controller extends Controller
{
    public function userContextLoader(){

        $contextKey = $_GET['contextKey'];
        $app = app(libController::class);
        $employee = $app->vendorApi()->context($contextKey);

        $uid = $employee->uid;
        $fio = $employee->shortFio;
        $accountId = $employee->accountId;
        $isAdmin = $employee->permissions->admin->view;

        $res = ["uid" => $uid,
                "fio" => $fio,
                "accountId" => $accountId,
                "isAdmin" => $isAdmin,
            ];

        dd($res);
    }
}
