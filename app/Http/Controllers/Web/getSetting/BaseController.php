<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function getBase($accountId, Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $isAdmin = $request->isAdmin;
        $Setting = new getSetting($accountId);
        $tokenMs = $Setting->tokenMs;
        $paymentDocument = $Setting->paymentDocument;

        if ($tokenMs == null) {
            $paymentDocument = "0";
        }

        return view('setting.base', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
            'paymentDocument' => $paymentDocument,
        ]);
    }
}
