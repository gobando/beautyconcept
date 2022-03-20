<?php
/*
 * File name: SendBookingStatusNotificationsListener.php
 * Last modified: 2022.02.16 at 18:23:31
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Listeners;

use App\Criteria\Bookings\BookingsOfSalonCriteria;
use App\Criteria\Bookings\PaidBookingsCriteria;
use App\Notifications\StatusChangedBooking;
use App\Repositories\BookingRepository;
use App\Repositories\EarningRepository;
use Illuminate\Support\Facades\Notification;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class SendBookingStatusNotificationsListener
 * @package App\Listeners
 */
class SendBookingStatusNotificationsListener
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->booking->at_salon) {
            if ($event->booking->bookingStatus->order < 20) {
                Notification::send([$event->booking->user], new StatusChangedBooking($event->booking));
            } else if ($event->booking->bookingStatus->order >= 20 && $event->booking->bookingStatus->order < 40) {
                Notification::send($event->booking->salon->users, new StatusChangedBooking($event->booking));
            } else {
                Notification::send([$event->booking->user], new StatusChangedBooking($event->booking));
            }
        } else {
            if ($event->booking->bookingStatus->order < 40) {
                Notification::send([$event->booking->user], new StatusChangedBooking($event->booking));
            } else {
                Notification::send($event->booking->salon->users, new StatusChangedBooking($event->booking));
            }
        }
    }
}
