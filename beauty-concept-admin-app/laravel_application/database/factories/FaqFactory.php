<?php
/*
 * File name: FaqFactory.php
 * Last modified: 2022.02.02 at 19:13:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Faq;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Faq::class, function (Faker $faker) {
    return [
        'question' => $faker->text(100),
        'answer' => $faker->realText(),
        'faq_category_id' => $faker->numberBetween(1, 4)
    ];
});
