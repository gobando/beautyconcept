<?php
/*
 * File name: EServiceCategoriesTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:57
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\EServiceCategory;
use Illuminate\Database\Seeder;

class EServiceCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('e_service_categories')->truncate();


        try {
            factory(EServiceCategory::class, 10)->create();
        } catch (Exception $e) {
        }
        try {
            factory(EServiceCategory::class, 10)->create();
        } catch (Exception $e) {
        }
        try {
            factory(EServiceCategory::class, 10)->create();
        } catch (Exception $e) {
        }
        try {
            factory(EServiceCategory::class, 10)->create();
        } catch (Exception $e) {
        }
        try {
            factory(EServiceCategory::class, 10)->create();
        } catch (Exception $e) {
        }
        try {
            factory(EServiceCategory::class, 10)->create();
        } catch (Exception $e) {
        }


    }
}
