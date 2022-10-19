<?php

namespace App\Observers;

use App\Http\Controllers\getData\getDevices;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class deviceObserver
{
    public function created(Device $model)
    {

        $accountIds = Device::all('accountId');


        foreach($accountIds as $accountId){
            $Position_1 = Device::query()
                ->where('position', '=', 1)
                ->where('accountId', '=', $accountId->accountId)
                ->get();
            if (count($Position_1) > 1 ){
                Device::query()
                    ->where('position', '=', 1)
                    ->where('accountId', '=', $accountId->accountId)
                    ->orderBy('created_at', 'ASC')
                    ->limit(1)
                    ->delete();
            }
            $Position_2 = Device::query()
                ->where('position', '=', 2)
                ->where('accountId', '=', $accountId->accountId)
                ->get();
            if (count($Position_2) > 1 ){
                Device::query()
                    ->where('position', '=', 2)
                    ->where('accountId', '=', $accountId->accountId)
                    ->orderBy('created_at', 'ASC')
                    ->limit(1)
                    ->delete();
            }
        }

    }


    public function updated(Device $model)
    {
        //
    }

    public function deleted(Device $model)
    {
        //
    }

    public function restored(Device $model)
    {
        //
    }

    public function forceDeleted(Device $model)
    {
        //
    }

}
