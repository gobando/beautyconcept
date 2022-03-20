<?php
/*
 * File name: AddressesTableSeeder.php
 * Last modified: 2022.02.15 at 15:05:07
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('addresses')->truncate();


        factory(Address::class, 20)->create();

    }
}
