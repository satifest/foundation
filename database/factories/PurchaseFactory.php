<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Carbon\Carbon;
use Faker\Generator as Faker;
use Satifest\Foundation\Purchase;

$factory->define(Purchase::class, function (Faker $faker) {
    return [
        'name' => $faker->domainName,
        'amount' => Money::USD(2500),
        'purchased_at' => Carbon::now(),
    ];
});
