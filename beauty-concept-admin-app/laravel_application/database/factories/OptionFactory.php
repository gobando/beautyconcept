<?php
/*
 * File name: OptionFactory.php
 * Last modified: 2022.02.02 at 19:13:53
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\EService;
use App\Models\Option;
use App\Models\OptionGroup;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Option::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Addon 1', 'Addon 2', 'Addon 3', 'Addon 4']),
        'description' => $faker->sentence(4),
        'price' => $faker->randomFloat(2, 10, 50),
        'e_service_id' => EService::all()->random()->id,
        'option_group_id' => OptionGroup::all()->random()->id,
    ];
});
