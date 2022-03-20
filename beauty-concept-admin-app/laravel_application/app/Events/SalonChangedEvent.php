<?php
/*
 * File name: SalonChangedEvent.php
 * Last modified: 2022.02.02 at 21:20:43
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Events;

use App\Models\Salon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SalonChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $newSalon;

    public $oldSalon;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Salon $newSalon, Salon $oldSalon)
    {
        //
        $this->newSalon = $newSalon;
        $this->oldSalon = $oldSalon;
    }

}
