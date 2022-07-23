<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExportProductController extends Controller
{
    public function index($accountId){
        return view('web.exportProduct', ['accountId' => $accountId]);
    }
}
