<?php

namespace App\Http\Controllers;

use App\Mail\FeedbackMailer;
use stdClass;
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

    public function supportSend(Request  $request){
        $request->validate([
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'message' => 'required|max:500',
        ]);

        $to = 'S.lone1212@yandex.kz';
        $subject = 'Обрантная связь с сайта Kaspi marketplace';

        $message = '
    <html>
    <body>
    <center>
    <table border="1" cellpadding="6" cellspacing="0" width="90%" bgcolor="black">
    <tr><td colspan="2" align="center" bgcolor="black"><b>Обратная связь </b></td></tr>';

        $message .= '<tr> <td> <b>Имя клиента</b></td>
                    <td>'.$request->name.'</td></tr>
                    <tr><td><b>Электронная почта</b></td>
                    <td>'.$request->email.'</td></tr>
                    <tr><td><b> Текст  сообщения</b></td>
                    <td>'.$request->message.'</td></tr>
                    </html>
    </body>';

      /*  $data = new stdClass();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->message = $request->message;
        Mail::to($data->email)->send(new FeedbackMailer($data));
        return redirect()->route('support')->with('success', 'Ваше сообщение успешно отправлено');*/

        mail($to, $subject, $message);
        return redirect()->route('support')->with('success', 'Ваше сообщение успешно отправлено');
    }




}
