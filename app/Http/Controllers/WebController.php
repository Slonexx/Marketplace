<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\SessionController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class WebController extends Controller
{
    public function index(){

        require_once 'Config/lib.php';

        $contextName = 'IFRAME';

        $_SESSION['contextName'] = $contextName;

       // require_once 'Config/user-context-loader.inc.php';

        $ApiKey = "4a539f95d34697f6b6cf4130050757126a54e882";
        $sessi = new SessionController();
        $sessi->SessionInitialization($ApiKey);


        return view('web.index');
    }


}
