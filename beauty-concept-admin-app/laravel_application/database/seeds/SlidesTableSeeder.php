<?php
/*
 * File name: SlidesTableSeeder.php
 * Last modified: 2022.02.15 at 16:47:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class SlidesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('slides')->truncate();

        DB::table('slides')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'order' => 1,
                    'text' => 'Assign a Handyman at Work to Fix the Household',
                    'button' => 'Discover It',
                    'text_position' => 'bottom_start',
                    'text_color' => '#333333',
                    'button_color' => '#D94464',
                    'background_color' => '#FFFFFF',
                    'indicator_color' => '#333333',
                    'image_fit' => 'cover',
                    'e_service_id' => NULL,
                    'salon_id' => NULL,
                    'enabled' => 1,
                    'created_at' => '2021-01-25 11:51:45',
                    'updated_at' => '2021-01-31 11:08:13',
                ),
            1 =>
                array(
                    'id' => 2,
                    'order' => 2,
                    'text' => 'Fix the Broken Stuff by Asking for the Technicians',
                    'button' => 'Repair',
                    'text_position' => 'bottom_start',
                    'text_color' => '#333333',
                    'button_color' => '#D94464',
                    'background_color' => '#FFFFFF',
                    'indicator_color' => '#333333',
                    'image_fit' => 'cover',
                    'e_service_id' => NULL,
                    'salon_id' => NULL,
                    'enabled' => 1,
                    'created_at' => '2021-01-25 14:23:49',
                    'updated_at' => '2021-01-31 11:03:05',
                ),
            2 =>
                array(
                    'id' => 3,
                    'order' => 3,
                    'text' => 'Add Hands to Your Cleaning Chores',
                    'button' => 'Book Now',
                    'text_position' => 'bottom_start',
                    'text_color' => '#333333',
                    'button_color' => '#D94464',
                    'background_color' => '#FFFFFF',
                    'indicator_color' => '#333333',
                    'image_fit' => 'cover',
                    'e_service_id' => NULL,
                    'salon_id' => NULL,
                    'enabled' => 1,
                    'created_at' => '2021-01-31 11:04:36',
                    'updated_at' => '2021-01-31 11:06:45',
                ),
        ));


    }
}
