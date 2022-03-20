<?php
/*
 * File name: WalletsTableSeeder.php
 * Last modified: 2022.02.16 at 12:02:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class WalletsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallets')->truncate();
        DB::table('wallets')->insert(array(
            array(
                'id' => '01194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'My USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 1,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '02194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'Home USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 2,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '03194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'Work USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 3,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '04194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'Dummy USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 4,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '05194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'Old USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 5,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '06194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'New USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 6,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '07194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'USD Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 7,
                'enabled' => 1,
                'created_at' => '2021-08-07 13:17:34',
                'updated_at' => '2021-08-07 13:17:34',
            ),
            array(
                'id' => '8d194a4f-f302-47af-80b2-ceb2075d36dc',
                'name' => 'Dollar Wallet',
                'balance' => 200,
                'currency' => '{"id":1,"name":"US Dollar","symbol":"$","code":"USD","decimal_digits":2,"rounding":0}',
                'user_id' => 8,
                'enabled' => 1,
                'created_at' => '2021-01-07 13:17:34',
                'updated_at' => '2021-01-07 13:17:34',
            ),
        ));

    }
}
