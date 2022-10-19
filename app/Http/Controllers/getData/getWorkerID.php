<?php

namespace App\Http\Controllers\getData;

use App\Http\Controllers\Controller;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class getWorkerID extends Controller
{
    public string $id;
    public $znm;
    public $access;

    public function __construct($znm)
    {
        $app = DataBaseService::showWorkerFirst($znm);
        $this->id = $app['id'];
        $this->znm = $app['znm'];
        $this->access = $app['access'];
    }
}
