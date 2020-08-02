<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Satifest\Foundation\Repository;

$factory->define(Repository::class, function (Faker $faker) {
    return [
        'provider' => 'github',
        'type' => 'vcs',
    ];
});
