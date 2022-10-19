<?php

namespace App\Services\workWithBD;

use App\Models\addSetting;
use App\Models\Device;
use App\Models\mainSetting;
use App\Models\orderSetting;
use App\Models\Setting;
use App\Models\Worker;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class DataBaseService
{

    public static function createSetting($accountId,$tokenMs,$apiKey,$paymentDocument,$saleChannel,$project){
        Setting::create([
            'accountId' => $accountId,
            'tokenMs' => $tokenMs,
            'saleChannel' => $saleChannel,
            'paymentDocument' => $paymentDocument,
            'project' => $project,
            'apiKey' => $apiKey,
        ]);
    }
    public static function createDevice($znm,$password,$position,$accountId){
        Device::create([
            'znm' => $znm,
            'accountId' => $accountId,
            'password' => $password,
            'position' => $position,
        ]);
    }
    public static function createWorker($id,$znm,$access){
        Worker::create([
            'id' => $id,
            'znm' => $znm,
            'access' => $access,
        ]);
    }



    public static function showSetting($accountId){
        $find = Setting::query()->where('accountId', $accountId)->first();
        try {
            $result = $find->getAttributes();
        } catch (\Throwable $e) {
            $result = [
                "accountId" => $accountId,
                "tokenMs" => null,
                "apiKey" => null,
                "saleChannel" => null,
                "paymentDocument" => null,
                "project" => null,
            ];
        }
        return $result;
    }
    public static function showDeviceFirst($znm){
        $find = Device::query()->where('znm', $znm)->first();
        try {
            $result = $find->getAttributes();
        } catch (\Throwable $e) {
            $result = [
                'znm' => $znm,
                'accountId' => null,
                'password' => null,
                'position' => null,
            ];
        }
        return $result;
    }
    public static function showDevice($accountId){
        $Devices = [];
        $find = Device::query()->where('accountId', $accountId)->orderBy('position')->get();

        foreach ($find as $item) {
            $json = json_encode($item->getAttributes());
            $Devices[] = json_decode($json);
        }

        return $Devices;
    }
    public static function showWorkerFirst($id){

        $find = Worker::query()->where('id', $id)->first();
        try {
            $result = $find->getAttributes();
        } catch (\Throwable $e) {
            $result = [
                'id' => $id,
                'znm' => null,
                'access' => null,
            ];
        }
        return $result;
    }
    public static function showWorkers($znm){

        $Workers = [];
        $find = Worker::query()->where('znm', $znm)->get();

        foreach ($find as $item) {
            $json = json_encode($item->getAttributes());
            $Workers[] = json_decode($json);
        }

        return $Workers;

    }



    public static function updateSetting($accountId,$tokenMs,$apiKey,$paymentDocument,$saleChannel,$project){
       $find = Setting::query()->where('accountId', $accountId);
       $find->update([
           'tokenMs' => $tokenMs,
           'saleChannel' => $saleChannel,
           'paymentDocument' => $paymentDocument,
           'project' => $project,
           'apiKey' => $apiKey,
       ]);
    }
    public static function updateDevice($znm,$password,$position,$accountId){
        $find = Device::query()->where('znm', $znm);
        $find->update([
            'accountId' => $accountId,
            'password' => $password,
            'position' => $position,
        ]);

    }
    public static function updateWorker($id,$znm,$access){
        $find = Worker::query()->where('id', $id);
        $find->update([
            'znm' => $znm,
            'access' => $access,
        ]);
    }


    public static function deleteSetting($accountId): void
    {
        Setting::query()->where('accountId',$accountId)->delete();
    }
    public static function deleteDevice($znm): void
    {
        Device::query()->where('znm',$znm)->delete();
    }
    public static function deleteWorker($id){
        Worker::query()->where('id',$id)->delete();
    }


    public static function createMainSetting($accountId, $TokenMS, $TokenKASPI){

        mainSetting::create([
            "accountId" => $accountId,
            "TokenMoySklad" => $TokenMS,
            "TokenKaspi" => $TokenKASPI,
        ]);
    }
    public static function showMainSetting($accountId){
        $find = mainSetting::query()->where('accountId', $accountId)->first();
        try {
            $result = $find->getAttributes();
        } catch (\Throwable $e) {
            $result = [
                "accountId" => $accountId,
                "TokenMoySklad" => null,
                "TokenKaspi" => null,
            ];
        }
        return $result;
    }
    public static function updateMainSetting($accountId, $TokenKASPI){
        $find = mainSetting::query()->where('accountId', $accountId);
        $find->update([
            "TokenKaspi" => $TokenKASPI,
        ]);

    }

    public static function createOrderSetting($accountId, $Organization, $PaymentDocument, $Document, $PaymentAccount, $CheckCreatProduct, $Store){

        orderSetting::create([
            "accountId" => $accountId,
            "Organization" => $Organization,
            "PaymentDocument" => $PaymentDocument,
            "Document" => $Document,
            "PaymentAccount" => $PaymentAccount,
            "CheckCreatProduct" => $CheckCreatProduct,
            "Store" => $Store,
        ]);

    }
    public static function showOrderSetting($accountId){
        $find = orderSetting::query()->where('accountId', $accountId)->first();
        try {
            $result = $find->getAttributes();
        } catch (\Throwable $e) {
            $result = [
                "accountId" => $accountId,
                "Organization" => null,
                "PaymentDocument" => null,
                "Document" => null,
                "PaymentAccount" => null,
                "CheckCreatProduct" => null,
                "Store" => null,
            ];
        }
        return $result;
    }
    public static function updateOrderSetting($accountId, $Organization, $PaymentDocument, $Document, $PaymentAccount, $CheckCreatProduct, $Store){
        $find = orderSetting::query()->where('accountId', $accountId);
        $find->update([
            "accountId" => $accountId,
            "Organization" => $Organization,
            "PaymentDocument" => $PaymentDocument,
            "Document" => $Document,
            "PaymentAccount" => $PaymentAccount,
            "CheckCreatProduct" => $CheckCreatProduct,
            "Store" => $Store,
        ]);
    }

    public static function createAddSetting($accountId, $Project, $Saleschannel, $APPROVED_BY_BANK, $ACCEPTED_BY_MERCHANT, $COMPLETED, $CANCELLED, $RETURNED){
        addSetting::create([
            "accountId" => $accountId,
            "Project" => $Project,
            "Saleschannel" => $Saleschannel,
            "APPROVED_BY_BANK" => $APPROVED_BY_BANK,
            "ACCEPTED_BY_MERCHANT" => $ACCEPTED_BY_MERCHANT,
            "COMPLETED" => $COMPLETED,
            "CANCELLED" => $CANCELLED,
            "RETURNED" => $RETURNED,
        ]);
    }
    public static function showAddSetting($accountId){
        $find = addSetting::query()->where('accountId', $accountId)->first();
        try {
            $result = $find->getAttributes();
        } catch (\Throwable $e) {
            $result = [
                "accountId" => $accountId,
                "Project" => null,
                "Saleschannel" => null,
                "APPROVED_BY_BANK" => null,
                "ACCEPTED_BY_MERCHANT" => null,
                "COMPLETED" => null,
                "CANCELLED" => null,
                "RETURNED" => null,
            ];
        }
        return $result;
    }
}
