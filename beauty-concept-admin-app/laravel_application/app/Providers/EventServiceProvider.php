<?php
/*
 * File name: EventServiceProvider.php
 * Last modified: 2022.02.16 at 18:16:51
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\SalonChangedEvent' => [
            'App\Listeners\UpdateSalonEarningTableListener',
            'App\Listeners\ChangeCustomerRoleToSalon',
        ],
        'App\Events\UserRoleChangedEvent' => [

        ],
        'App\Events\BookingChangedEvent' => [
            'App\Listeners\UpdateBookingEarningTable'
        ],
        'App\Events\BookingStatusChangedEvent' => [
            'App\Listeners\SendBookingStatusNotificationsListener'
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

        //
    }
}
