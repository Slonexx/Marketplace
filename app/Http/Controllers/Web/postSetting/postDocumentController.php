<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getSetting;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class postDocumentController extends Controller
{
    public function postDocument(Request $request, $accountId): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $isAdmin = $request->isAdmin;
        $apiKey = 'f5ac6559-b5cd-4e0e-89e5-7fd32a6d60a5';
        $app = new getSetting($accountId);

        try {
            DataBaseService::updateSetting($accountId, $app->tokenMs, $apiKey,
                $request->paymentDocument,null,null);
            $message = [
                'alert' => ' alert alert-success alert-dismissible fade show in text-center ',
                'message' => ' Настройки сохранились ',
            ];
        } catch (\Throwable $e){
            $message = [
                'alert' => ' alert alert-danger alert-dismissible fade show in text-center ',
                'message' => $e->getMessage(),
            ];
        }

        return view('setting.document', [
            'accountId' => $accountId,
            'isAdmin' => $isAdmin,
            'message' => $message,
            'paymentDocument' =>  $request->paymentDocument,
        ]);
    }
}
