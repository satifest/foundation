<?php

namespace Satifest\Foundation\Testing\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Satifest\Foundation\License;

class LicenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = License::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'provider' => 'stripe',
            'uid' => (string) Str::orderedUuid(),
            'type' => 'one-off',
            'amount' => 2500,
            'currency' => 'USD',
            'ends_at' => null,
            'allocation' => 0,
            'utilisation' => 0,
        ];
    }

    /**
     * Indicate that the license under utilized.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function underUtilized()
    {
        return $this->state([
            'allocation' => 10,
            'utilisation' => 0,
        ]);
    }

    /**
     * Indicate that the license has been utilized.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function utilized()
    {
        return $this->state([
            'allocation' => 10,
            'utilisation' => 10,
        ]);
    }
}
