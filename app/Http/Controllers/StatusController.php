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

    public function getStatusName($accountId,$status_Kaspi)
    {
        $status = null;

        //Настройка статусов нужны тут
        $settings = app(SettingController::class)->getSettings();

        $currSetting = null;

        foreach($settings as $setting){
            if($setting->accountId == $accountId){
              $currSetting = $setting;
              break;
            }
        }

      if($currSetting != null){

          switch ($status_Kaspi) {
            case 'APPROVED_BY_BANK':
              $status = $currSetting->APPROVED_BY_BANK;
            break;
            case 'ACCEPTED_BY_MERCHANT':
              $status = $currSetting->ACCEPTED_BY_MERCHANT;
            break;
            case 'CANCELLED':
                case 'CANCELLING':
              $status = $currSetting->CANCELLED;
            break;
            case 'COMPLETED':
              $status = $currSetting->COMPLETED;
            break;
            case 'KASPI_DELIVERY_RETURN_REQUESTED':
              case 'RETURN_ACCEPTED_BY_MERCHANT':
                case 'RETURNED':
                    $status = $currSetting->RETURNED;
                  break;
        }
          
      }

      // switch ($status_Kaspi) {
      //         case 'APPROVED_BY_BANK':
      //           $status = "Новый";
      //         break;
      //         case 'ACCEPTED_BY_MERCHANT':
      //           $status = "Подтвержден";
      //         break;
      //         case 'CANCELLED':
      //             case 'CANCELLING':
      //           $status = "Отменен";
      //         break;
      //         case 'COMPLETED':
      //           $status = "Доставлен";
      //         break;
      //         case 'KASPI_DELIVERY_RETURN_REQUESTED':
      //           case 'RETURN_ACCEPTED_BY_MERCHANT':
      //             case 'RETURNED':
      //                 $status = "Возврат";
      //               break;
      // }

        return $status;
    }
}
