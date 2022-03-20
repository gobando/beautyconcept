<?php
/*
 * File name: BookingFactory.php
 * Last modified: 2022.02.16 at 11:47:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */


use App\Models\Address;
use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\EService;
use App\Models\Salon;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Booking::class, function (Faker $faker) {
    $salon = $faker->randomElement(Salon::where('accepted', '=', '1')->with('users')->get()->toArray());
    $eServices = EService::where('salon_id', '=', $salon['id'])->with('options')->limit(random_int(1,3))->get();
    $userId = $faker->randomElement(['3', '5', '7']);
    $bookingStatus = BookingStatus::get()->random();
    $bookingAt = $faker->dateTimeBetween('-7 months','70 hours');
    $startAt = $faker->dateTimeBetween('75 hours','80 hours');
    $endsAt = $faker->dateTimeBetween('81 hours','85 hours');
    return [
        'salon' => $salon,
        'e_services' => $eServices,
        'options' => $eServices->pluck('options')->flatten()->take(random_int(1,3)),
        'quantity' => 1,
        'user_id' => $userId,
        'employee_id' => $faker->randomElement(array_column($salon['users'],'id')),
        'booking_status_id' => $bookingStatus->id,
        'address' => $faker->randomElement(Address::where('user_id','=',$userId)->get()->toArray()),
        'taxes' => Salon::find($salon['id'])->taxes,
        'booking_at' => $bookingAt,
        'start_at' => $bookingStatus->order >= 40 ? $startAt : null,
        'ends_at' => $bookingStatus->order >= 50 ? $endsAt : null,
        'hint' => $faker->sentence,
        'cancel' => $faker->boolean(5),
    ];
});
