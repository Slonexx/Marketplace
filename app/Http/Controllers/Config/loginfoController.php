<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class loginfoController extends Controller
{

    public function __construct($name, $msg)
    {
        global $dirRoot;
        $logDir = $dirRoot . 'logs';
        @mkdir($logDir);
        file_put_contents($logDir . '/log.txt', date(DATE_W3C) . ' [' . $name . '] '. $msg . "\n", FILE_APPEND);
    }

}
