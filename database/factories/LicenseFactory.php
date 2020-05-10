<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Satifest\Foundation\License;

$factory->define(License::class, function (Faker $faker) {
    return [
        'provider' => 'stripe',
        'uid' => (string) Str::orderedUuid(),
        'type' => 'one-off',
        'amount' => 2500,
        'currency' => 'USD',
        'ends_at' => null,
    ];
});
