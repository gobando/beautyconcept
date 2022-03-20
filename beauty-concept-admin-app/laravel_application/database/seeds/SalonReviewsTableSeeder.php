<?php
/*
 * File name: SalonReviewsTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\SalonReview;
use Illuminate\Database\Seeder;

class SalonReviewsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('salon_reviews')->truncate();


        factory(SalonReview::class, 100)->create();

    }
}
