<?php

namespace App\Http\Controllers\getData;

use App\Http\Controllers\Controller;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class getWorkers extends Controller
{
    public array $workers;

    public function __construct($znm)
    {
        $app = DataBaseService::showWorkers($znm);
        if ($app) foreach ($app as $item){
            $this->workers[$item->id] = $item;
        }
        else  $this->workers[] = null;

    }


}

