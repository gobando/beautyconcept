<?php
/*
 * File name: AvailabilityHoursTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:07
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\AvailabilityHour;
use Illuminate\Database\Seeder;

class AvailabilityHoursTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('availability_hours')->truncate();


        factory(AvailabilityHour::class, 50)->create();
    }
}
