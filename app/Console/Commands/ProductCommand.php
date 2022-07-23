<?php

namespace App\Console\Commands;

use App\Models\InfoLogModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\CheckSettingsController;

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
        $havAllRequiredSettings = app(CheckSettingsController::class)->haveSettings();
        //$schedule->command('queue:listen')->everyTwoMinutes();
        if($havAllRequiredSettings == true){
             $today = date("Y-m-d", strtotime ('+1 day'));
             $tenDaysBefore = date ('Y-m-d', strtotime ('-9 day'));

             $log = "Add products... ".$tenDaysBefore."|".$today."\n";
             print_r($log);

             InfoLogModel::create([
                'accountId' => 'fdhadkfdsd',
                'message' => $log,
            ]);

             try {

                 $kaspiAllStates = ['NEW', 'SIGN_REQUIRED', 'PICKUP', 'DELIVERY', 'KASPI_DELIVERY', 'ARCHIVE'];

                 foreach($kaspiAllStates as $state){
                         $response = Http::post('https://smartkaspi.kz/api/products',[
                             'tokenKaspi' => 'Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=',
                             'tokenMs' => '8eb0e2e3fc1f31effe56829d5fdf60444d2e3d3f',
                             'state' => $state,
                             'fdate' => $tenDaysBefore,
                             'sdate' => $today,
                             'option' => 2,
                          ])->throw();
                     $logSt = "Kaspi State:".$state." ".$response->body()."\n";
                     print_r($logSt);

                     InfoLogModel::create([
                        'accountId' => 'fdhadkfdsd',
                        'message' => $logSt,
                    ]);

                 }

             } catch (\Throwable $th) {
                $this->error($th->getMessage());

                InfoLogModel::create([
                    'accountId' => 'fdhadkfdsd',
                    'message' => $th->getMessage(),
                ]);

             }

        }
    }
}
