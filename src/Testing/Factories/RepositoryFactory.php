<?php

namespace Satifest\Foundation\Testing\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Satifest\Foundation\Repository;

class RepositoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Repository::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider' => 'github',
            'type' => 'vcs',
        ];
    }
}
