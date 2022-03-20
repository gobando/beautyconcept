<?php
/*
 * File name: SalonUserFactory.php
 * Last modified: 2022.02.02 at 19:13:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Salon;
use App\Models\SalonUser;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(SalonUser::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement([2, 4, 6]),
        'salon_id' => Salon::all()->random()->id
    ];
});
