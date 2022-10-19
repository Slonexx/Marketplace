<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function getDocument($accountId, Request $request): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $isAdmin = $request->isAdmin;
        $Setting = new getSetting($accountId);
        $tokenMs = $Setting->tokenMs;
        $paymentDocument = $Setting->paymentDocument;
        if ($tokenMs == null){
            return view('setting.no', [
                'accountId' => $accountId,
                'isAdmin' => $isAdmin,
            ]);
        }
        if ($paymentDocument == null) {
            $paymentDocument = "0";
        }

        return view('setting.document', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
            'paymentDocument' => $paymentDocument,
        ]);
    }
}
