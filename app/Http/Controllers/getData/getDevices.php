<?php

namespace App\Http\Controllers\getData;

use App\Http\Controllers\Controller;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class getDevices extends Controller
{
    public array $devices = [];

    public function __construct($accountId)
    {
        $app = DataBaseService::showDevice($accountId);
        foreach ($app as $item){
            $this->devices[] = $item;
        }

    }
}
