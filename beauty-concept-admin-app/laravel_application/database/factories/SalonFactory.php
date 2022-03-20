<?php
/*
 * File name: SalonFactory.php
 * Last modified: 2022.02.12 at 18:38:05
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Address;
use App\Models\Salon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Salon::class, function (Faker $faker) {
    return [
        'name' => $faker->randomElement(['Vibes Hair', 'Spa Skin Care', 'Waxing', 'Miami Nail', 'Terra Bella Day', 'Healing Hands Massage', 'Damisa Thai Massage', 'Blanc Beauty Smile', 'The Comfort Zone Spa', 'Esthetics center', 'Studio Lux', 'Royalty Nails', 'Beauty Attraction']) . " " . $faker->company,
        'description' => $faker->text,
        'salon_level_id' => $faker->numberBetween(2, 4),
        'address_id' => Address::all()->random()->id,
        'phone_number' => $faker->phoneNumber,
        'mobile_number' => $faker->phoneNumber,
        'availability_range' => $faker->randomFloat(2, 6000, 15000),
        'available' => $faker->boolean(100),
        'featured' => $faker->boolean(40),
        'accepted' => $faker->boolean(95),
    ];
});
