<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function getDocument($accountId, Request $request){
        $isAdmin = $request->isAdmin;
        $Setting = new getSetting($accountId);

        $tokenMs = $Setting->tokenMs;
        $paymentDocument = $Setting->paymentDocument;
        $payment_type = $Setting->payment_type;
        $OperationCash = $Setting->OperationCash;
        $OperationCard = $Setting->OperationCard;
        $OperationMobile = $Setting->OperationMobile;

        if ($tokenMs == null){
            return view('setting.no', [
                'accountId' => $accountId,
                'isAdmin' => $isAdmin,
            ]);
        }
        if ($paymentDocument == null) {
            $paymentDocument = "0";
        }
        if ($payment_type == null) {
            $payment_type = "1";
        }
        if ($OperationCash == null) {
            $OperationCash = "0";
        }
        if ($OperationCard == null) {
            $OperationCard = "0";
        }
        if ($OperationMobile == null) {
            $OperationMobile = "0";
        }

        if (isset($request->message)) {
            return view('setting.document', [
                'accountId' => $accountId,
                'isAdmin' => $isAdmin,

                'message' => $request->message,
                'paymentDocument' => $paymentDocument,
                'payment_type' => $payment_type,
                'OperationCash' => $OperationCash,
                'OperationCard' => $OperationCard,
                'OperationMobile' => $OperationMobile,
            ]);
        }

        return view('setting.document', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,


            'paymentDocument' => $paymentDocument,
            'payment_type' => $payment_type,
            'OperationCash' => $OperationCash,
            'OperationCard' => $OperationCard,
            'OperationMobile' => $OperationMobile,
        ]);
    }
}
