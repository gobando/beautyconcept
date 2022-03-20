<?php
/*
 * File name: SalonTaxFactory.php
 * Last modified: 2022.02.15 at 14:42:15
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Salon;
use App\Models\SalonTax;
use App\Models\SalonUser;
use App\Models\Tax;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(SalonTax::class, function (Faker $faker) {
    return [
        'tax_id' => Tax::all()->random()->id,
        'salon_id' => Salon::all()->random()->id
    ];
});
