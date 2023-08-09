<?php

namespace App\Http\Controllers\getData;

use App\Http\Controllers\Controller;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class getSetting extends Controller
{
    public mixed $accountId;
    public mixed $tokenMs;
    public mixed $apiKey;

    public mixed $saleChannel;
    public mixed $project;

    public mixed $paymentDocument;
    public mixed $payment_type;
    public mixed $OperationCash;
    public mixed $OperationCard;
    public mixed $OperationMobile;


    public function __construct($accountId)
    {
        $app = DataBaseService::showSetting($accountId);
        $this->accountId = $app['accountId'];
        $this->tokenMs = $app['tokenMs'];
        $this->apiKey = "6784dad7-6679-4950-b257-2711ff63f9bb";

        $this->saleChannel = $app['saleChannel'];
        $this->project = $app['project'];

        $this->paymentDocument = $app['paymentDocument'];
        $this->payment_type = $app['payment_type'];
        $this->OperationCash = $app['OperationCash'];
        $this->OperationCard = $app['OperationCard'];
        $this->OperationMobile = $app['OperationMobile'];

    }


}
