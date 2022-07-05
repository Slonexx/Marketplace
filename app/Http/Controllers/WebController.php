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


        Mail::send('emails.welcome', array('key' => 'value'), function($message)
        {
            $message->to('foo@example.com', 'Джон Смит')->subject('Привет!');
        });
        /*Mail::send(['text => mail'], ['name', 'web 1'], function($message){
            $message->to('s.ivanov@smartinnovations.kz', 's.ivanov@smartinnovations')->subject('Test email');
            $message->from('s.ivanov@smartinnovations.kz' , 's.ivanov@');
        });*/


        return $request;

    }




}
