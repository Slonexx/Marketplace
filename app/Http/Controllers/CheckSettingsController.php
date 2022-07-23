<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckSettingsController extends Controller
{
    public function haveSettings()
    {
        //Проверка есть ли необходимые найстройки
        return true;
    }
}
