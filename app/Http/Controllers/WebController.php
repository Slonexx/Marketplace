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


        return view('web.index');
    }


}
