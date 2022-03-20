<?php
/*
 * File name: FavoriteFactory.php
 * Last modified: 2022.02.02 at 19:13:53
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Favorite;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Favorite::class, function (Faker $faker) {
    return [
        'eservice_id' => $faker->numberBetween(1, 30),
        'user_id' => $faker->numberBetween(1, 6)
    ];
});
