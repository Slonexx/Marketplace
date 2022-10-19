<?php

namespace App\Http\Controllers\getData;

use App\Http\Controllers\Controller;
use App\Services\workWithBD\DataBaseService;
use Illuminate\Http\Request;

class getMainSetting extends Controller
{
    var $accountId;
    var $TokenMoySklad;
    var $TokenKaspi;
    var $Organization;
    var $PaymentDocument;
    var $Document;
    var $PaymentAccount;
    var $CheckCreatProduct;
    var $Store;
    var $Project;
    var $Saleschannel;
    var $APPROVED_BY_BANK;
    var $ACCEPTED_BY_MERCHANT;
    var $COMPLETED;
    var $CANCELLED;
    var $RETURNED;

    public function __construct($accountId)
    {
        $app = DataBaseService::showMainSetting($accountId);
        $this->accountId = $app['accountId'];
        $this->TokenMoySklad = $app['TokenMoySklad'];
        $this->TokenKaspi = $app['TokenKaspi'];

        $appOrder = DataBaseService::showOrderSetting($accountId);
        $this->Organization = $appOrder['Organization'];
        $this->PaymentDocument = $appOrder['PaymentDocument'];
        $this->Document = $appOrder['Document'];
        $this->PaymentAccount = $appOrder['PaymentAccount'];
        $this->CheckCreatProduct = $appOrder['CheckCreatProduct'];
        $this->Store = $appOrder['Store'];

        $app = DataBaseService::showAddSetting($accountId);
        $this->Project = $app['Project'];
        $this->Saleschannel = $app['Saleschannel'];
        $this->APPROVED_BY_BANK = $app['APPROVED_BY_BANK'];
        $this->ACCEPTED_BY_MERCHANT = $app['ACCEPTED_BY_MERCHANT'];
        $this->COMPLETED = $app['COMPLETED'];
        $this->CANCELLED = $app['CANCELLED'];
        $this->RETURNED = $app['RETURNED'];
    }
}
