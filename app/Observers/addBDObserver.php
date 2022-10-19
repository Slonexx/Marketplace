<?php

namespace App\Observers;

use App\Models\addSetting;
use Illuminate\Support\Facades\DB;

class addBDObserver
{
    public function created(addSetting $model)
    {

        $accountIds = addSetting::all('accountId');

        foreach($accountIds as $accountId){

            $query = addSetting::query();
            $logs = $query->where('accountId',$accountId->accountId)->get();
            if(count($logs) > 1){
                DB::table('add_settings')
                    ->where('accountId','=',$accountId->accountId)
                    ->orderBy('created_at', 'ASC')
                    ->limit(1)
                    ->delete();
            }

        }

    }


    public function updated(addSetting $model)
    {
        //
    }

    public function deleted(addSetting $model)
    {
        //
    }

    public function restored(addSetting $model)
    {
        //
    }

    public function forceDeleted(addSetting $model)
    {
        //
    }
}
