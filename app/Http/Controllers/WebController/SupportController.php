<?php

namespace App\Http\Controllers\WebController;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SupportController extends Controller
{
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

        Alert::success('Сообщение отправлено', 'мы ответим вам в ближайшее время');
        return back();
    }


}
