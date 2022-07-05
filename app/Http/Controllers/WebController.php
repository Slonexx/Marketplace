<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class WebController extends Controller
{
    public function index(){
        return view('web.index');
    }





    public function support(){
        return view('web.support');
    }

    public function supportSubmit(Request  $request){

        Mail::send('web.support', $date, function ($messages) use ($user){
            $messages->to('');
        });

    }




}
