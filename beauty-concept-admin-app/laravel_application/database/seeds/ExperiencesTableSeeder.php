<?php
/*
 * File name: ExperiencesTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:07
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperiencesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('experiences')->truncate();

        factory(Experience::class, 50)->create();
    }
}
