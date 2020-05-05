<?php

namespace Satifest\Foundation\Tests;

use Satifest\Foundation\Satifest;
use Orchestra\Testbench\TestCase as Testbench;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends Testbench
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/../database/factories');
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        Satifest::setPurchaserModel(User::class);
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            'Satifest\Foundation\SatifestServiceProvider',
        ];
    }
}
