<?php

namespace App\Console\Commands;

use App\Models\InfoLogModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CheckSettingsController;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class ProductCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command add products';

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

                $log = "Add products... ".$tenDaysBefore."|".$today."\n";
                print_r($log);

                //здесь настройки

                // InfoLogModel::create([
                //     'accountId' => $settings->accountId,
                //     'message' => $log,
                // ]);

                $urlAttributes = "https://marketplace.vetmobile.kz/api/setAttributes";
                $client_Asycn = new \GuzzleHttp\Client();
                $client_Asycn->postAsync($urlAttributes,[
                    'form_params' => [
                        'tokenMs' => $settings->TokenMoySklad,
                         'accountId' => $settings->accountId,
                    ]
                ])->then(
                    function (ResponseInterface $res) {
                        //echo $res->getStatusCode() . "\n";
                    },
                    function (RequestException $e) {
                        
                    }
                )->wait();

                try {

                    $kaspiAllStates = ['NEW', 'SIGN_REQUIRED', 'PICKUP', 'DELIVERY', 'KASPI_DELIVERY', 'ARCHIVE'];

                    foreach($kaspiAllStates as $state){
                            $response = Http::post('https://marketplace.vetmobile.kz/api/products',[
                                'tokenKaspi' => $settings->TokenKaspi,
                                'tokenMs' => $settings->TokenMoySklad,
                                'state' => $state,
                                'fdate' => $tenDaysBefore,
                                'sdate' => $today,
                                'option' => $settings->CheckCreatProduct,
                            ])->throw();
                            $logSt = "Состояние заказа из Kaspi:".$state." ".$response->body()."\n";
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
