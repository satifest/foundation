<?php

namespace Satifest\Foundation;

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
     * Register the package's publishable resources.
     *
     * @return void
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
