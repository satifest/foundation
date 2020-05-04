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
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../database/migrations' => \database_path('migrations'),
            ], 'satifest-migrations');
        }
    }

    /**
     * Register Satifest's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Satifest::$runsMigrations) {
            return $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}
