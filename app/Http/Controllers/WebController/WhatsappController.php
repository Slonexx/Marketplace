<?php

namespace App\Http\Controllers\WebController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WhatsappController extends Controller
{

    public function Index($accountId){
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
        $message = "https://wa.me/87750498821?text=".$inputName.$inputMessage;
        return redirect()->to($message);

    }



}
