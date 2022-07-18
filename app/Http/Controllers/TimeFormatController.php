<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;

class TimeFormatController extends Controller
{
    public function getMilliseconds($date_string)
    {
        $date = DateTime::createFromFormat('Y-m-d',$date_string);
        $f_date = $date->format('Y-m-d H:i:s');
        $stamp = strtotime($f_date);
        return $stamp*1000;
    }
}
