<?php

namespace Satifest\Foundation\Testing\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Satifest\Foundation\Release;

class ReleaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Release::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(5),
            'description' => $this->faker->paragraph(3),
            'artifact_disk' => 'local',
            'artifact_url' => $this->faker->sha1,
            'type' => 'stable',
            'synced_at' => Carbon::now(),
        ];
    }
}
