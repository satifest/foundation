<?php

namespace Satifest\Foundation;

use Spatie\Url\Url;
use InvalidArgumentException;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\RouteRegistrar;

class Satifest
{
    use Concerns\AuthorizesRequests;

    /**
     * Purchaser model (normally refer to the user).
     *
     * @var string
     */
    protected static $purchaserModel;

    /**
     * Indicates if Satifest migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Ignored hosts.
     *
     * @var array
     */
    protected static $ignoredHosts = ['', 'http://localhost', 'https://localhost'];

    /**
     * Set purchaser model.
     */
    public static function setPurchaserModel(string $purchaserModel): void
    {
        $uses = \class_uses_recursive($purchaserModel);

        if (! isset($uses[Concerns\HasPurchases::class])) {
            throw new InvalidArgumentException("Given [$purchaserModel] does not implements '".Concerns\HasPurchases::class."' trait.");
        }

        static::$purchaserModel = $purchaserModel;
    }

    /**
     * Get the purchaser model.
     */
    public static function getPurchaserModel(): string
    {
        if (! isset(static::$purchaserModel)) {
            $provider = \config('auth.guards.web.provider');

            static::setPurchaserModel(\config("auth.providers.{$provider}.model"));
        }

        return static::$purchaserModel;
    }

    /**
     * Configure Satifest to not register its migrations.
     */
    public static function ignoreMigrations(): void
    {
        static::$runsMigrations = false;
    }

    /**
     * Register routing for Satifest.
     */
    public static function route(string $namespace): RouteRegistrar
    {
        return \tap(Route::namespace($namespace), function ($router) {
            $url = Url::fromString(\config('satifest.url'));

            $router->prefix($url->getPath());

            $domain = \transform($url->getHost(), static function ($host) use ($ignoredHosts) {
                return ! \in_array($host, $ignoredHosts) ? $host : null;
            });

            if (! \is_null($domain)) {
                $router->domain($domain);
            }
        });
    }
}
