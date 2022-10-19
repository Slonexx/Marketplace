<?php

namespace App\Http\Controllers\getData;

use App\Http\Controllers\Controller;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class getDeviceFirst extends Controller
{
    var $znm;
    var $password;
    var $position;
    var $accountId;

    public function __construct($znm)
    {
        $app = DataBaseService::showDeviceFirst($znm);
        $this->znm = $app['znm'];
        $this->password = $app['password'];
        $this->position = $app['position'];
        $this->accountId = $app['accountId'];

    }
}
