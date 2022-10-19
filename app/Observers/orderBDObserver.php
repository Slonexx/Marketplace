<?php

namespace App\Observers;

use App\Models\orderSetting;
use Illuminate\Support\Facades\DB;

class orderBDObserver
{
    public function created(orderSetting $model)
    {

        $accountIds = orderSetting::all('accountId');

        foreach($accountIds as $accountId){

            $query = orderSetting::query();
            $logs = $query->where('accountId',$accountId->accountId)->get();
            if(count($logs) > 1){
                DB::table('order_settings')
                    ->where('accountId','=',$accountId->accountId)
                    ->orderBy('created_at', 'ASC')
                    ->limit(1)
                    ->delete();
            }

        }

    }


    public function updated(orderSetting $model)
    {
        //
    }

    public function deleted(orderSetting $model)
    {
        //
    }

    public function restored(orderSetting $model)
    {
        //
    }

    public function forceDeleted(orderSetting $model)
    {
        //
    }
}
