<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WhatsappController extends Controller
{

    public function Index(){
        return view('web.whatsapp');
    }

    public function WhatsappSend(Request $request){
        $request->validate([
            'name' => 'required|max:100',
            'message' => 'required|max:500',
        ]);

        $name = "Здравствуйте меня зовут ".$request->name.". ";
        $message =
        $inputName = str_ireplace(" ", "%20", $name);
        $inputMessage = str_ireplace(" ", "%20", $request->message);



        $message = "https://wa.me/87750498821?text=".$inputName.$inputMessage;

        Session::flash('whatsapp', "Сообщение отправлено");
        Session::flash('whatsapp_url', $message);
        Session::flash('alert-class', 'alert-success');

        return redirect()->back();

        //return redirect()->back();
      //  return redirect()->intended("https://wa.me/87750498821?text=Я%20заинтересован%20в%20покупке%20вашего%20авто");

    }

}
