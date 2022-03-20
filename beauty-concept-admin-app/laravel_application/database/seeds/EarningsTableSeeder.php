<?php
/*
 * File name: EarningsTableSeeder.php
 * Last modified: 2022.02.16 at 12:01:49
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class EarningsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('earnings')->truncate();
        $controller  = resolve('App\Http\Controllers\EarningController');
        $controller->create();


    }
}
