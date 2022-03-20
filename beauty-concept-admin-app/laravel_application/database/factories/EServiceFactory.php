<?php
/*
 * File name: EServiceFactory.php
 * Last modified: 2022.02.02 at 21:16:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

/** @var Factory $factory */

use App\Models\EService;
use App\Models\Salon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(EService::class, function (Faker $faker) {
    $services = [
        'Haircut & Beard Trim/Eadge',
        'Haircut with eyebrows',
        'Skill press and style',
        'Wand curls',
        'Health trim',
        'Ponytail',
        'Wig consultation',
        'Braid down',
        'Shampoo & deep conditioning Treatment',
        'Keratin hair treatment',
        'Quick weave',
        'Quick weave removal',
        'Massage Services',
        'Thai Massage Services',
        'Facials Services',
        'Child haircut',
        'Balayage',
        'Brazilian Blowout',
        'Global Keratin treatment',
        'Neck trim',
        'Color correction',
        'Hair Botox',
        'Beard trim',
        'Relax the neck and back',
        'Body rub with hot stone',
        'Foot reflexology',
    ];
    $price = $faker->randomFloat(2, 10, 50);
    $discountPrice = $price - $faker->randomFloat(2, 1, 10);
    return [
        'name' => $faker->randomElement($services),
        'price' => $price,
        'discount_price' => $faker->randomElement([$discountPrice, 0]),
        'duration' => $faker->numberBetween(1, 5) . ":00",
        'description' => $faker->text,
        'featured' => $faker->boolean,
        'enable_booking' => $faker->boolean,
        'available' => $faker->boolean,
        'salon_id' => Salon::all()->random()->id
    ];
});
