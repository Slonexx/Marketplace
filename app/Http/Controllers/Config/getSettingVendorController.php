<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Config\Lib\AppInstanceContoller;
use App\Http\Controllers\Config\Lib\cfg;
use App\Http\Controllers\Config\Lib\VendorApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class getSettingVendorController extends Controller
{
    var $appId;
    var $accountId;
    var $TokenMoySklad;
    var $TokenKaspi;
    var $Organization;
    var $PaymentDocument;
    var $Document;
    var $PaymentAccount;
    var $Saleschannel;
    var $Project;
    var $CheckCreatProduct;
    var $Store;
    var $APPROVED_BY_BANK;
    var $ACCEPTED_BY_MERCHANT;
    var $COMPLETED;
    var $CANCELLED;
    var $RETURNED;

    public function __construct($accountId)
    {
        $cfg = new cfg();

        $appId = $cfg->appId;
        $json = AppInstanceContoller::loadApp($appId, $accountId);

        $this->appId = $json->appId;
        $this->accountId = $json->accountId;
        $this->TokenMoySklad = $json->TokenMoySklad;
        $this->TokenKaspi = $json->TokenKaspi;
        $this->Organization = $json->Organization;
        $this->PaymentDocument = $json->PaymentDocument;
        $this->Document = $json->Document;
        $this->PaymentAccount = $json->PaymentAccount;
        $this->Saleschannel = $json->Saleschannel;
        $this->Project = $json->Project;
        $this->CheckCreatProduct = $json->CheckCreatProduct;
        $this->Store = $json->Store;
        $this->APPROVED_BY_BANK = $json->APPROVED_BY_BANK;
        $this->ACCEPTED_BY_MERCHANT = $json->ACCEPTED_BY_MERCHANT;
        $this->COMPLETED = $json->COMPLETED;
        $this->CANCELLED = $json->CANCELLED;
        $this->RETURNED = $json->RETURNED;
        return $json;

    }

}
