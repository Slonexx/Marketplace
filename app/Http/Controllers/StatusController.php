<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config\getSettingVendorController;
use Illuminate\Http\Request;

class StatusController extends Controller
{

        # APPROVED_BY_BANK – одобрен банком --> Новый

    #ACCEPTED_BY_MERCHANT– принят на обработку продавцом --> Подтвержден

    #CANCELLED – отменён ------------\>>  Отменен
    #CANCELLING – ожидает отмены-----/>>

    #COMPLETED – завершён

    #KASPI_DELIVERY_RETURN_REQUESTED – ожидает возврата
    #RETURN_ACCEPTED_BY_MERCHANT – ожидает решения по возврату
    #RETURNED – возвращён

    public function getStatusName($accountId,$status_Kaspi)
    {
        $Setting = new getSettingVendorController($accountId);
        return match ($status_Kaspi) {
            'APPROVED_BY_BANK' => $Setting->APPROVED_BY_BANK,
            'ACCEPTED_BY_MERCHANT' => $Setting->ACCEPTED_BY_MERCHANT,
            'CANCELLED' => $Setting->CANCELLED,
            'CANCELLING' => $Setting->CANCELLED,
            'COMPLETED' => $Setting->COMPLETED,
            'KASPI_DELIVERY_RETURN_REQUESTED', 'RETURN_ACCEPTED_BY_MERCHANT', 'RETURNED' => $Setting->RETURNED,
        };
    }
}
