<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Satifest\Foundation\Plan;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'constraint' => '*',
        'name' => $faker->colorName,
        'amount' => 2500,
        'currency' => 'USD',
    ];
});
