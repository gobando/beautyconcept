<?php
/*
 * File name: FaqsTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:07
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('faqs')->truncate();

        factory(Faq::class, 30)->create();
    }
}
