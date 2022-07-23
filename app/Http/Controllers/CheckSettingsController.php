<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CheckSettingsController extends Controller
{
    public function haveSettings()
    {
        //Проверка есть ли необходимые найстройки

        $directory = public_path().'/Config/data';

        $filesInFolder = File::files($directory);

        $usersSettings = [];

        foreach($filesInFolder as $file) { 
            //$file = pathinfo($path);
            if(str_ends_with($file->getFilename(),'.json')){
                //$data = file_get_contents($path);
                //$unser = json_encode( unserialize($data) );
                $setting =  $this->getContentJson($file->getFilename());
                array_push($usersSettings,$setting);
            }
        } 

        dd($usersSettings);

        //return true;
    }


    private function getContentJson($filename) {
        $path = public_path().'/Config/data/'.$filename;
        return json_decode(file_get_contents($path),true);
    }
}
