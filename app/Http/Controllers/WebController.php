<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WebController extends Controller
{
    public function index(){
        return view('web.index');
    }





    public function support(){
        return view('web.support');
    }

    public function supportSend(Request  $request){
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'message' => 'required|max:500',
        ]);

        $to = 'sergei@smartkaspi.kz';
        $subject = 'Обрантная связь с сайта Kaspi marketplace';

        $headers = [
            "From" => "sergei@smartkaspi.kz",
            "Reply-To" => "sergei@smartkaspi.kz",
            "Content-type" => "text/plain; charset=utf-8",
        ];

        $message = "Форма обратной связи "."\r\n".
            "Имя клиента: ".$request->name."\r\n".
            "Электронная почта: ".$request->email."\r\n".
            "Текст  сообщения:".$request->message."\r\n";
        $message = wordwrap($message, 70, "\r\n");

        mail($to, $subject, $message, $headers);
        //alert()->info("Какой то текст")->persistent("Закрыть")->autoclose(3500);

        Alert::success('Сообщение отправлено', 'мы ответим вам в ближайшее время');
        return back();
       // return view('web.index');
    }




}
