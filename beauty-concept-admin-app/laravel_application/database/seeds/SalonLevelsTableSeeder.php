<?php
/*
 * File name: SalonLevelsTableSeeder.php
 * Last modified: 2022.02.15 at 16:47:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class SalonLevelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('salon_levels')->truncate();

        DB::table('salon_levels')->insert(array(
            0 =>
                array(
                    'id' => 2,
                    'name' => 'Level One',
                    'commission' => 50.0,
                    'disabled' => 0,
                    'default' => 1,
                    'created_at' => '2021-01-13 18:05:35',
                    'updated_at' => '2021-02-01 21:22:19',
                ),
            1 =>
                array(
                    'id' => 3,
                    'name' => 'Level Two',
                    'commission' => 75.0,
                    'disabled' => 0,
                    'default' => 0,
                    'created_at' => '2021-01-17 19:27:18',
                    'updated_at' => '2021-02-24 18:57:30',
                ),
            2 =>
                array(
                    'id' => 4,
                    'name' => 'Level Three',
                    'commission' => 85.0,
                    'disabled' => 0,
                    'default' => 0,
                    'created_at' => '2021-01-17 19:27:18',
                    'updated_at' => '2021-02-24 18:57:30',
                ),
        ));


    }
}
