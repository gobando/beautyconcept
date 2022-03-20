<?php
/*
 * File name: ModelHasRolesTableSeeder.php
 * Last modified: 2022.02.15 at 16:47:16
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class ModelHasRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('model_has_roles')->truncate();

        DB::table('model_has_roles')->insert(array(
            0 =>
                array(
                    'role_id' => 2,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 1,
                ),
            1 =>
                array(
                    'role_id' => 3,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 2,
                ),
            2 =>
                array(
                    'role_id' => 3,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 4,
                ),
            3 =>
                array(
                    'role_id' => 3,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 6,
                ),
            4 =>
                array(
                    'role_id' => 4,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 3,
                ),
            5 =>
                array(
                    'role_id' => 4,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 5,
                ),
            6 =>
                array(
                    'role_id' => 4,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 7,
                ),
            7 =>
                array(
                    'role_id' => 4,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => 8,
                ),
        ));


    }
}
