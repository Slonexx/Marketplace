<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckSettingsController extends Controller
{
    public function haveSettings()
    {
        $usersSettings = app(SettingController::class)->getSettings();
        foreach($usersSettings as $userSetting){
            
        }
    }


}
