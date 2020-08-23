<?php

namespace Satifest\Foundation;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\ServiceProvider;

class SatifestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSatifestProvider();
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

        Satifest::setSupportedHosts(
            $this->app->make('satifest.provider')->pluck('domain')->all()
        );
    }

    /**
     * Register "satifest.provider" service.
     */
    protected function registerSatifestProvider(): void
    {
        $this->app->singleton('satifest.provider', static function () {
            $config = LazyCollection::make(function () {
                $config = \config('satifest', []);
                $schema = ['domain' => null, 'token' => null, 'webhook-secret' => null];

                foreach ($this->providers as $provider) {
                    yield $provider => ($config[$provider] ?? $schema);
                }
            })->filter(static function ($provider) {
                return ! \is_null($provider['domain']) && ! \is_null($provider['token']);
            });

            return $config;
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
