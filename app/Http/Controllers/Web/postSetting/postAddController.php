<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getMainSetting;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class postAddController extends Controller
{
    public function postAddSetting(Request $request, $accountId){
        $isAdmin = $request->isAdmin;
        $cfg = new cfg();
        $appId = $cfg->appId;
        $app = AppInstanceContoller::loadApp($appId, $accountId);
        $result = $this->saveBD($accountId, $app, $request);

        $mainSetting = new getMainSetting($accountId);
        $Setting = new getSettingVendorController($accountId);

        $TokenMoySklad = $Setting->TokenMoySklad;
        $url_customerorder = "https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata";
        $url_saleschannel = "https://api.moysklad.ru/api/remap/1.2/entity/saleschannel";
        $url_project = "https://api.moysklad.ru/api/remap/1.2/entity/project";
        $responses = Http::withToken($TokenMoySklad)->pool(fn (Pool $pool) => [
            $pool->as('body_customerorder')->withToken($TokenMoySklad)->get($url_customerorder),
            $pool->as('body_saleschannel')->withToken($TokenMoySklad)->get($url_saleschannel),
            $pool->as('body_project')->withToken($TokenMoySklad)->get($url_project),
        ]);

        return view('setting.add',[
            "Body_customerorder" => $responses['body_customerorder']->object()->states,
            "Body_saleschannel" => $responses['body_saleschannel']->object()->rows,
            "Body_project" => $responses['body_project']->object()->rows,

            "Saleschannel" => $Setting->Saleschannel,  "Project" => $Setting->Project,

            "APPROVED_BY_BANK" => $Setting->APPROVED_BY_BANK,
            "ACCEPTED_BY_MERCHANT" => $Setting->ACCEPTED_BY_MERCHANT,
            "COMPLETED" => $Setting->COMPLETED,
            "CANCELLED" => $Setting->CANCELLED,
            "RETURNED" => $Setting->RETURNED,

            "result" => $result,

            "accountId"=> $accountId,
            'isAdmin' => $isAdmin,
        ]);
    }

    private function saveBD($accountId, $app, $request){
        $cfg = new cfg();
        try {
            $Saleschannel = $request->Saleschannel; $Project = $request->Project;
            if ($Saleschannel == "0") $Saleschannel = null; if ($Project == "0") $Project = null;

            $app->Saleschannel = $Saleschannel; $app->Project = $Project;

            if ($request->APPROVED_BY_BANK == 'Статус МойСклад') { $app->APPROVED_BY_BANK = null;
            } else { $app->APPROVED_BY_BANK = $request->APPROVED_BY_BANK; }
            if ($request->ACCEPTED_BY_MERCHANT == 'Статус МойСклад') { $app->ACCEPTED_BY_MERCHANT = null;
            } else { $app->ACCEPTED_BY_MERCHANT = $request->ACCEPTED_BY_MERCHANT; }
            if ($request->COMPLETED == 'Статус МойСклад') { $app->COMPLETED = null;
            } else { $app->COMPLETED = $request->COMPLETED; }
            if ($request->CANCELLED == 'Статус МойСклад') { $app->CANCELLED = null;
            } else { $app->CANCELLED = $request->CANCELLED; }
            if ($request->RETURNED == 'Статус МойСклад') { $app->RETURNED = null;
            } else { $app->RETURNED = $request->RETURNED; }

            $app->status = AppInstanceContoller::ACTIVATED;
            $vendorAPI = new VendorApiController();
            $vendorAPI->updateAppStatus($cfg->appId, $accountId, $app->getStatusName());

            $app->persist();

            DataBaseService::createAddSetting(
                $accountId, $app->Project, $app->Saleschannel,
                $app->APPROVED_BY_BANK, $app->ACCEPTED_BY_MERCHANT,
                $app->COMPLETED, $app->CANCELLED, $app->RETURNED
            );
            $result = [
                'status' => true,
                'message' => 'Настройки сохранились !',
            ];
        } catch (\Throwable $e) {
            $result = [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $result;
    }
}
