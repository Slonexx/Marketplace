<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WhatsappController extends Controller
{

    public function Index($accountId){
       return redirect()->to("https://api.whatsapp.com/send/?phone=87750498821&text&type=phone_number&app_absent=0");
        return view('web.whatsapp', ['accountId' => $accountId] );
    }

    public function WhatsappSend(Request $request, $accountId){
        $request->validate([
            'name' => 'required|max:100',
            'message' => 'required|max:500',
        ]);

        $name = "Здравствуйте меня зовут ".$request->name.". ";
        $inputName = str_ireplace(" ", "%20", $name);
        $inputMessage = str_ireplace(" ", "%20", $request->message);

        "https://smartkaspi.kz/WhatsappSendNext/".$inputName."/".$inputMessage;
        return ;

    }

    public function WhatsappSendNext($inputName,$inputMessage ){
        $message = "https://wa.me/87750498821?text=".$inputName.$inputMessage;
        return redirect()->to($message);
    }

}
