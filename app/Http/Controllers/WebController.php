<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WebController extends Controller
{
    public function index(){

        /*session_start();


        require_once 'Config/lib.php';

        $contextName = 'IFRAME';

        $_SESSION['contextName'] = $contextName;*/

       // require_once 'Config/user-context-loader.inc.php';


       //$test = app(SessionController::class)->SessionInitialization();

        $ApiKey = "4a539f95d34697f6b6cf4130050757126a54e882";

        $sessi = new SessionController();
      //  app(SessionController::class)->SessionInitialization($ApiKey);

        $sessi->SessionInitialization($ApiKey);

        //$sessi = session()->all();
        //$sessi = session()->get('Store');

        $sessi = $_SESSION["store"];
        dd($sessi);

        //return view('web.index');
    }


}
