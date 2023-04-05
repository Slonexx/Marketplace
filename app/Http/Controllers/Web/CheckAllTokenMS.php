<?php

namespace App\Http\Controllers\Web;

use App\Clients\MsClient;
use App\Http\Controllers\Controller;
use App\Services\Settings\SettingsService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class CheckAllTokenMS extends Controller
{
    public function CheckAllTokenMS(){
        $allSettings = app(SettingsService::class)->getSettings();
        $Result = [];
        foreach ($allSettings as $setting){
            $Client = new MsClient($setting->TokenMoySklad);
            try {
                $Client->get("https://online.moysklad.ru/api/remap/1.2/entity/employee");
            } catch (GuzzleException $exception){
                $Result[] = "ac217934-edaf-4975-91ae-a0ea408727de.".$setting->accountId;
            }

        }
        return response()->json($Result);
    }
}
