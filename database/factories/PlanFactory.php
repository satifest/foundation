<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Money\Money;
use Satifest\Foundation\Plan;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'constraint' => '*',
        'name' => $faker->colorName,
        'amount' => Money::USD(2500),
    ];
});
