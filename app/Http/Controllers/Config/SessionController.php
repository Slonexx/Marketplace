<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function _SessionConstructor(){

    }

    public function SessionInitialization(){

        $result = session(['id' => "Sergei"]);

        return $result;
    }




}
