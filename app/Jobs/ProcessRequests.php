<?php

namespace App\Jobs;

use Illuminate\Http\Request;
use App\Http\Controllers\OrderController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRequests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $settings;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */


    // NEW – новый заказ
    // SIGN_REQUIRED – заказ на подписании
    // PICKUP – самовывоз
    // DELIVERY – доставка
    // KASPI_DELIVERY – Kaspi Доставка
    // ARCHIVE – архивный заказ

    public function handle()
    {
        //Обработка задач заказам, товарам и статусам
        //$kaspiAllStates = ['NEW', 'SIGN_REQUIRED', 'PICKUP', 'DELIVERY', 'KASPI_DELIVERY', 'ARCHIVE'];

        $today = date("Y-m-d");
        $tenDaysBefore = date ('Y-m-d', strtotime ('-10 day'));

        //foreach($kaspiAllStates as $stateKaspi){

            $requestInsertOrder = new Request();
            $requestInsertOrder->setMethod('POST');
            $requestInsertOrder->request->add([
                'tokenKaspi' => 'Oiau+82MUNfcUYPQG9rEyzec3H34OwI5SQ+w6ToodIM=',
                'tokenMs' => '8eb0e2e3fc1f31effe56829d5fdf60444d2e3d3f',
                'payment_option' => '2',
                'demand_option' => '2',
                'state' => "ARCHIVE",
                'fdate' => $tenDaysBefore,
                'sdate' => $today,
                'organization_id' => '72d4b01d-feab-11ec-0a80-0738000e5a8d',
                'project_name' => 'Test Project',
                'sale_channel_name' => 'Kaspi Shop',
                'organization_account_number' => '21003543',
            ]);
            app(OrderController::class)->insertOrders($requestInsertOrder);
            //sleep(10);



       // }

        
    }
}
