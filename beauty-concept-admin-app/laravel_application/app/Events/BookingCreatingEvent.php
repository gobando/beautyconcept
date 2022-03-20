<?php
/*
 * File name: BookingCreatingEvent.php
 * Last modified: 2021.09.15 at 13:30:06
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreatingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        if (!empty($booking->booking_at)) {
            $booking->booking_at = convertDateTime($booking->booking_at);
        }
        if (!empty($booking->start_at)) {
            $booking->start_at = convertDateTime($booking->start_at);
        }
        if (!empty($booking->ends_at)) {
            $booking->ends_at = convertDateTime($booking->ends_at);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
