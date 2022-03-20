<?php
/*
 * File name: BookingsTableSeeder.php
 * Last modified: 2022.02.19 at 13:08:05
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('bookings')->delete();
        DB::table('bookings')->truncate();

        factory(Booking::class, 20)->create();
    }
}
