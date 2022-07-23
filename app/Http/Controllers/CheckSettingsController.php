<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckSettingsController extends Controller
{
    public function haveSettings()
    {
        $usersSettings = app(SettingController::class)->getSettings();
        $usersCheckSettings = [];
        foreach($usersSettings as $userSetting){
            $userCheckSetting = null;
            $userCheckSetting['check'] = $userSetting->TokenKaspi != null && $userSetting->Organization != null;
            $userCheckSetting['settings'] = $userSetting;
            array_push($usersCheckSettings,$userCheckSetting);
        }

       return $usersCheckSettings;

    }


}
