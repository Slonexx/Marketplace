<?php

namespace App\Http\Controllers\Web\postSetting;

use App\Clients\MsClient;
use App\Http\Controllers\Config\getSettingVendorController;
use App\Http\Controllers\Controller;
use App\Models\AutomationModel;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class postAutomationController extends Controller
{

    public function postAutomation(Request $request, $accountId)
    {
        $Setting = new getSettingVendorController($accountId);

        $dataFromRequest = $request->all();

        $groupedData = [];

        foreach ($dataFromRequest as $key => $value) {
            if ($key !== '_token' && $key !== 'isAdmin') {
                $index = $this->getIndexFromKey($key);
                $field = str_replace("_{$index}", '', $key);
                $groupedData[$index][$field] = $value;
            }
        }

        $existingRecords = AutomationModel::where('accountId', $accountId)->get();

        if (!$existingRecords->isEmpty()) {
            foreach ($existingRecords as $record) {
                $record->delete();
            }
        }

        foreach ($groupedData as $data) {
            // Создаем экземпляр модели
            $model = new AutomationModel();

            // Устанавливаем значения полей из группированных данных
            $model->accountId = $accountId; // Примерно такая же логика для других полей
            $model->entity = $data['entity'] ?? '';
            $model->status = $data['status'] ?? '';
            $model->payment = $data['payment'] ?? '';
            $model->saleschannel = $data['saleschannel'] ?? '';
            $model->project = $data['project'] ?? '';

            // Сохраняем модель в базе данных
            $model->save();
        }






        try {
            $Client = new MsClient($Setting->TokenMoySklad);
            $url_check ='https://smartrekassa.kz/api/webhook/' ;
            $Webhook_check = true;
            $Webhook_body = $Client->get('https://api.moysklad.ru/api/remap/1.2/entity/webhook/')->rows;
            if ($Webhook_body != []){
                foreach ($Webhook_body as $item){
                    if ($item->url == $url_check){
                        $Webhook_check = false;
                    }
                }
            }
            if ($Webhook_check) {
                foreach ($Client->get('https://api.moysklad.ru/api/remap/1.2/entity/webhook/')->rows as $item){
                    if (strpos(($item->url), "https://smartkaspi.kz/") !== false) {
                        $Client->delete($item->meta->href,null);
                    }
                }

                $Client->post('https://api.moysklad.ru/api/remap/1.2/entity/webhook/', [
                    'url' => 'https://smartkaspi.kz/api/webhook/customerorder',
                    'action' => "UPDATE",
                    'entityType' => 'customerorder',
                    'diffType' => "FIELDS",
                ]);
                $Client->post('https://api.moysklad.ru/api/remap/1.2/entity/webhook/', [
                    'url' => 'https://smartkaspi.kz/api/webhook/demand',
                    'action' => "UPDATE",
                    'entityType' => 'demand',
                    'diffType' => "FIELDS",
                ]);
                $Client->post('https://api.moysklad.ru/api/remap/1.2/entity/webhook/', [
                    'url' => 'https://smartkaspi.kz/api/webhook/salesreturn',
                    'action' => "UPDATE",
                    'entityType' => 'salesreturn',
                    'diffType' => "FIELDS",
                ]);
            }


            $message = "Настройки сохранились";
        } catch (BadResponseException $e){
            $message = json_decode($e->getResponse()->getBody()->getContents())->errors[0]->error;
        }


        return redirect()->route('getAutomation', ['accountId' => $accountId, 'isAdmin' => $request->isAdmin, 'message' => $message]);
    }


    function getIndexFromKey($key) {
        return (int) explode('_', $key)[1];
    }
}
