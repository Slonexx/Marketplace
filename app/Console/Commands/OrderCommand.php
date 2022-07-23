<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CheckSettingsController;
use App\Models\InfoLogModel;

class OrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command add orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $allRequiredSettings = app(CheckSettingsController::class)->haveSettings();
           //$schedule->command('queue:listen')->everyTwoMinutes();

            //здесь настройки
            if(count($allRequiredSettings)>0)
            foreach($allRequiredSettings as $requiredSettings){
                if($requiredSettings['check'] == true){
                    $settings = $requiredSettings['settings'];
                    $today = date("Y-m-d", strtotime ('+1 day'));
                    $tenDaysBefore = date ('Y-m-d', strtotime ('-9 day'));

                    $log = "Add orders... ".$tenDaysBefore."|".$today."\n";
                    print_r($log);

                    // InfoLogModel::create([
                    //     'accountId' => $settings->accountId,
                    //     'message' => $log,
                    // ]);

                    try {

                        $kaspiAllStates = ['NEW', 'SIGN_REQUIRED', 'PICKUP', 'DELIVERY', 'KASPI_DELIVERY', 'ARCHIVE'];

                        foreach($kaspiAllStates as $state){
                                $response = Http::post('https://smartkaspi.kz/api/orders',[
                                    'tokenKaspi' => $settings->TokenKaspi,
                                    'tokenMs' => $settings->TokenMoySklad,
                                    'accountId' => $settings->accountId,
                                    'payment_option' => $settings->PaymentDocument,
                                    'demand_option' => $settings->Document,
                                    'state' => $state,
                                    'fdate' => $tenDaysBefore,
                                    'sdate' => $today,
                                    'organization_id' => $settings->Organization,
                                    'project_name' => $settings->Project,
                                    'sale_channel_name' => $settings->Saleschannel,
                                    'organization_account_number' => $settings->PaymentAccount,
                                ])->throw();
                                $logSt = "Состояние заказа из Kaspi:".$state." ".json_decode($response->body())->message."\n";
                            print_r($logSt);
                            InfoLogModel::create([
                                'accountId' => $settings->accountId,
                                'message' => $logSt,
                            ]);
                        }

                    } catch (\Throwable $th) {
                    $this->error($th->getMessage());

                        InfoLogModel::create([
                        'accountId' => $settings->accountId,
                        'message' => $th->getMessage(),
                        ]);
                    }

                }
            }


          

    }
}
