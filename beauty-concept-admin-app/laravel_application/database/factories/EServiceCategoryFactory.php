<?php
/*
 * File name: EServiceCategoryFactory.php
 * Last modified: 2022.02.13 at 22:47:27
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Category;
use App\Models\EService;
use App\Models\EServiceCategory;
use App\Models\Salon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(EServiceCategory::class, function (Faker $faker) {
    return [
        'category_id' => Category::all()->random()->id,
        'e_service_id' => EService::all()->random()->id
    ];
});
