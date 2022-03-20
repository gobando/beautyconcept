<?php
/*
 * File name: ChangeCustomerRoleToSalon.php
 * Last modified: 2022.02.02 at 21:51:53
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Listeners;

/**
 * Class ChangeCustomerRoleToSalon
 * @package App\Listeners
 */
class ChangeCustomerRoleToSalon
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if ($event->newSalon->accepted) {
            foreach ($event->newSalon->users as $user) {
                $user->syncRoles(['salon owner']);
            }
        }
    }
}
