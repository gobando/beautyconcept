<?php
/*
 * File name: PaymentStatusFactory.php
 * Last modified: 2022.02.02 at 19:13:53
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\PaymentStatus;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(PaymentStatus::class, function (Faker $faker) {
    return [
        'status' => $faker->text(48),
        'order' => $faker->numberBetween(1, 10)
    ];
});

$factory->state(PaymentStatus::class, 'status_more_127_char', function (Faker $faker) {
    return [
        'status' => $faker->paragraph(20),
    ];
});
