<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getDevices;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class postWorkerController extends Controller
{
    public function postWorker(Request $request, $accountId){
        $isAdmin = $request->isAdmin;
        $allRequest = $request->request;
        $devices = new getDevices($accountId);
        $devices = $devices->devices;

        $workers = [];
        foreach ($allRequest as $id=>$item){
            if ($id == '_token') continue;
            if ($item == "0") $access = false;
            else $access = true;
            $workers[] = [
                'id' => $id,
                'znm' => $devices[0]->znm,
                'access' => $access,
            ];
        }

        foreach ($workers as $item){
            $First = DataBaseService::showWorkerFirst($item['id']);
            if ($First['znm'] == null) DataBaseService::createWorker($item['id'], $item['znm'], $item['access']);
            else DataBaseService::updateWorker($item['id'], $item['znm'], $item['access']);
        }
        $message = [
            'alert' => ' alert alert-success alert-dismissible fade show in text-center ',
            'message' => ' Настройки сохранились ',
        ];
        return redirect()->route('getWorker', [ 'accountId' => $accountId, 'isAdmin' => $isAdmin, 'message'=>$message ]);
    }
}
