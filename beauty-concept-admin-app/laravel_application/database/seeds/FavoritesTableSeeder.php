<?php
/*
 * File name: FavoritesTableSeeder.php
 * Last modified: 2022.02.15 at 16:47:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('favorites')->truncate();
    }
}
