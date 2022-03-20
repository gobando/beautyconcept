<?php
/*
 * File name: PaymentMethodsTableSeeder.php
 * Last modified: 2022.02.15 at 16:47:17
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('payment_methods')->truncate();

        DB::table('payment_methods')->insert(array(
            array(
                'id' => 2,
                'name' => 'RazorPay',
                'description' => 'Click to pay with RazorPay gateway',
                'route' => '/RazorPay',
                'order' => 2,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-01-17 14:33:49',
                'updated_at' => '2021-02-17 22:37:30',
            ),
            array(
                'id' => 5,
                'name' => 'PayPal',
                'description' => 'Click to pay with your PayPal account',
                'route' => '/PayPal',
                'order' => 1,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-01-17 15:46:06',
                'updated_at' => '2021-02-17 22:38:47',
            ),
            array(
                'id' => 6,
                'name' => 'Cash',
                'description' => 'Click to pay cash when finish',
                'route' => '/Cash',
                'order' => 3,
                'default' => 1,
                'enabled' => 1,
                'created_at' => '2021-02-17 22:38:42',
                'updated_at' => '2021-02-17 22:38:42',
            ),
            array(
                'id' => 7,
                'name' => 'Credit Card (Stripe)',
                'description' => 'Click to pay with your Credit Card',
                'route' => '/Stripe',
                'order' => 3,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-02-17 22:38:42',
                'updated_at' => '2021-02-17 22:38:42',
            ),
            array(
                'id' => 8,
                'name' => 'PayStack',
                'description' => 'Click to pay with PayStack gateway',
                'route' => '/PayStack',
                'order' => 5,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-07-23 22:38:42',
                'updated_at' => '2021-07-23 22:38:42',
            ), array(
                'id' => 9,
                'name' => 'FlutterWave',
                'description' => 'Click to pay with FlutterWave gateway',
                'route' => '/FlutterWave',
                'order' => 6,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-07-23 22:38:42',
                'updated_at' => '2021-07-23 22:38:42',
            ),
            array(
                'id' => 10,
                'name' => 'Malaysian Stripe FPX	',
                'description' => 'Click to pay with Stripe FPX gateway',
                'route' => '/StripeFPX',
                'order' => 7,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-07-24 22:38:42',
                'updated_at' => '2021-07-24 22:38:42',
            ),
            array(
                'id' => 11,
                'name' => 'Wallet',
                'description' => 'Click to pay with Wallet',
                'route' => '/Wallet',
                'order' => 8,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-08-08 22:38:42',
                'updated_at' => '2021-08-08 22:38:42',
            ),
            array(
                'id' => 12,
                'name' => 'PayMongo',
                'description' => 'Click to pay with PayMongo',
                'route' => '/PayMongo',
                'order' => 12,
                'default' => 0,
                'enabled' => 1,
                'created_at' => '2021-10-08 22:38:42',
                'updated_at' => '2021-10-08 22:38:42',
            ),
        ));


    }
}
