<?php

namespace App\Providers;

use App\Models\addSetting;
use App\Models\Device;
use App\Models\InfoLogModel;
use App\Models\orderSetting;
use App\Observers\addBDObserver;
use App\Observers\deviceObserver;
use App\Observers\InfoLogModelObserver;
use App\Observers\orderBDObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        InfoLogModel::observe(InfoLogModelObserver::class);
        Device::observe(deviceObserver::class);
        orderSetting::observe(orderBDObserver::class);
        addSetting::observe(addBDObserver::class);
    }
}
