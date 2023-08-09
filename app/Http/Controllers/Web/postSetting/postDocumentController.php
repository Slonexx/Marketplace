<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class postDocumentController extends Controller
{
    public function postDocument(Request $request, $accountId): \Illuminate\Http\RedirectResponse
    {
        $isAdmin = $request->isAdmin;
        $Setting = new getSettingVendorController($accountId);

        try {
            DataBaseService::updateSetting($accountId, $Setting->TokenMoySklad, $request->payment_type,
                $request->createDocument_asWay,null,null, $request->OperationCash, $request->OperationCard, $request->OperationMobile);
        } catch (\Throwable $e){

        }

        return redirect()->route('getWorker', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
        ]);
    }
}
