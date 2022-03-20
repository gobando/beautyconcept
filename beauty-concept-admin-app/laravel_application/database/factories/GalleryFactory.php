<?php
/*
 * File name: GalleryFactory.php
 * Last modified: 2022.02.02 at 21:16:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Gallery;
use App\Models\Salon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Gallery::class, function (Faker $faker) {
    return [
        'description' => $faker->sentence,
        'salon_id' => Salon::all()->random()->id
    ];
});
