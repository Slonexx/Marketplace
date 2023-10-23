<?php

namespace App\Http\Controllers\Web\getSetting;

use App\Clients\MsClient;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\getData\getDevices;
use App\Models\AutomationModel;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class AutomationController extends Controller
{

    public function getAutomation(Request $request, $accountId): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        if ($request->isAdmin == "NO") { return redirect()->route('indexNoAdmin', ["accountId" => $accountId, "isAdmin" => $request->isAdmin]); }

        if (isset($request->message)) {
            $message = $request->message;
            if ($message == "Настройки сохранились") {
                $class = "mt-1 alert alert-success alert-dismissible fade show in text-center";
            } else $class = "mt-1 alert alert-warning alert-danger fade show in text-center";
        } else {
            $message = '';
            $class = '';
        };

        $Setting = new getSettingVendorController($accountId);
        $Client = new MsClient($Setting->TokenMoySklad);

        try {
            $customerorder = $Client->get('https://api.moysklad.ru/api/remap/1.2/entity/customerorder/metadata');
            $demand = $Client->get('https://api.moysklad.ru/api/remap/1.2/entity/demand/metadata');
            $salesreturn = $Client->get('https://api.moysklad.ru/api/remap/1.2/entity/salesreturn/metadata');
        } catch (BadResponseException $e){
            return view('setting.error', [
                'accountId' => $accountId,
                'isAdmin' => $request->isAdmin,
                'message' => $e->getResponse()->getBody()->getContents()
            ]);
        }

        $body_project = $Client->get('https://api.moysklad.ru/api/remap/1.2/entity/project');
        $body_saleschannel = $Client->get('https://api.moysklad.ru/api/remap/1.2/entity/saleschannel');

        $dontChoose = json_decode(json_encode(['id'=>'0', 'name'=>'Не выбирать']));

        if (!property_exists($customerorder,'states')) { $customerorder = [$dontChoose];
        } else { $customerorder = $customerorder->states; array_unshift($customerorder, $dontChoose); }
        if (!property_exists($demand,'states')) { $demand = [$dontChoose];
        } else { $demand = $demand->states; array_unshift($demand, $dontChoose); }
        if (!property_exists($salesreturn,'states')) { $salesreturn = [$dontChoose];
        } else { $salesreturn = $salesreturn->states; array_unshift($salesreturn, $dontChoose); }


        if (!$body_project->meta->size > 0) { $body_project = [$dontChoose];
        } else { $body_project = $body_project->rows; array_unshift($body_project, $dontChoose); }

        if (!$body_saleschannel->meta->size > 0) { $body_saleschannel = [$dontChoose];
        } else { $body_saleschannel = $body_saleschannel->rows; array_unshift($body_saleschannel, $dontChoose); }


        $body_meta_status = [
            'customerorder' => (array) $customerorder,
            'demand' => (array) $demand,
            'salesreturn' => (array) $salesreturn,
        ];
        $body_meta_project = [
            'customerorder' => (array) $body_project,
            'demand' => (array) $body_project,
            'salesreturn' => (array) $body_project,
        ];
        $body_meta_saleschannel = [
            'customerorder' => (array) $body_saleschannel,
            'demand' => (array) $body_saleschannel,
            'salesreturn' => (array) $body_saleschannel,
        ];


        $multiDimensionalArray = (AutomationModel::where('accountId', $accountId)->get())->map(function ($record) {
            return [
                'accountId' => $record->accountId,
                'entity' => $record->entity,
                'status' => $record->status,
                'payment' => $record->payment,
                'saleschannel' => $record->saleschannel,
                'project' => $record->project,
            ];
        })->toArray();

        return view('setting.Automation', [
            'arr_meta' => $body_meta_status,
            'arr_project' => $body_meta_project,
            'arr_saleschannel' => $body_meta_saleschannel,

            'SavedCreateToArray' => $multiDimensionalArray,

            "message" => $message,
            "class" => $class,

            "accountId" => $accountId,
            "isAdmin" => $request->isAdmin,
        ]);
    }

}
