<?php
/*
 * File name: RolesTableSeeder.php
 * Last modified: 2022.02.15 at 16:47:16
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('roles')->truncate();

        DB::table('roles')->insert(array(
            0 =>
                array(
                    'id' => 2,
                    'name' => 'admin',
                    'guard_name' => 'web',
                    'default' => 0,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            1 =>
                array(
                    'id' => 3,
                    'name' => 'salon owner',
                    'guard_name' => 'web',
                    'default' => 0,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            2 =>
                array(
                    'id' => 4,
                    'name' => 'customer',
                    'guard_name' => 'web',
                    'default' => 1,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}
