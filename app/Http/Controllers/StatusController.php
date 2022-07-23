<?php

namespace App\Http\Controllers;

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

    public function getStatusName($status_Kaspi)
    {
        $status = null;

        //Настройка статусов нужны тут

        switch ($status_Kaspi) {
            case 'APPROVED_BY_BANK':
              $status = "Новый";
            break;
            case 'ACCEPTED_BY_MERCHANT':
              $status = "Подтвержден";
            break;
            case 'CANCELLED':
                case 'CANCELLING':
              $status = "Отменен";
            break;
            case 'COMPLETED':
              $status = "Доставлен";
            break;
            case 'KASPI_DELIVERY_RETURN_REQUESTED':
              case 'RETURN_ACCEPTED_BY_MERCHANT':
                case 'RETURNED':
                    $status = "Возврат";
                  break;
        }
        return $status;
    }
}
