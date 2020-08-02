<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use Satifest\Foundation\Release;

$factory->define(Release::class, function (Faker $faker) {
    return [
        'title' => $faker->text(5),
        'description' => $faker->paragraph(3),
        'artifact_disk' => 'local',
        'artifact_url' => $faker->sha1,
        'type' => 'stable',
    ];
});
