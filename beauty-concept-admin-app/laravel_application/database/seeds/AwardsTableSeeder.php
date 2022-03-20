<?php
/*
 * File name: AwardsTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:07
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\Award;
use Illuminate\Database\Seeder;

class AwardsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('awards')->truncate();

        factory(Award::class, 50)->create();
    }
}
