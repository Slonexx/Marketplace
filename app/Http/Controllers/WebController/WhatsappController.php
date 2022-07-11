<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WhatsappController extends Controller
{

    public function Index(){
        return view('web.whatsapp');
    }

}
