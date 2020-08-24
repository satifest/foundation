<?php

namespace Satifest\Foundation;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\ServiceProvider;

class SatifestServiceProvider extends ServiceProvider
{
    /**
     * List of VCS providers.
     *
     * @var array
     */
    protected $vcsProviders = [
        'github' => 'GitHub',
        'gitlab' => 'GitLab',
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
            $this->registerMigrations();
        }
    }

    /**
     * Register "satifest.provider" service.
     */
    protected function registerSatifestProvider(): void
    {
        $this->app->singleton('satifest.provider', function () {
            return LazyCollection::make(function () {
                $config = \config('satifest', []);
                $schema = ['domain' => null, 'token' => null, 'webhook-secret' => null];

                foreach ($this->vcsProviders as $key => $name) {
                    yield $key => \array_merge(['name' => $name], $config[$key] ?? $schema);
                }
            });
        });
    }

    /**
     * Register the package's publishable resources.
     */
    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => \database_path('migrations'),
        ], 'satifest-migrations');
    }

    /**
     * Register Satifest's migration files.
     */
    protected function registerMigrations(): void
    {
        if (Satifest::$runsMigrations) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

            return;
        }
    }
}
