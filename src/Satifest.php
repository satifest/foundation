<?php

namespace Satifest\Foundation;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Laravie\QueryFilter\Value\Column;

class Satifest
{
    use Concerns\AuthorizesRequests;

    /**
     * Indicates if Satifest migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Purchaser model (normally refer to the user).
     *
     * @var string
     */
    protected static $userModel;

    /**
     * Get list of supported hosts.
     *
     * @var array
     */
    protected static $supportedHosts = [
        'github.com',
        'gitlab.com',
    ];

    /**
     * Auth token name.
     *
     * @var string
     */
    protected static $authTokenName = 'api_token';

    /**
     * Set purchaser model.
     */
    public static function setUserModel(string $userModel): void
    {
        $uses = \class_uses_recursive($userModel);

        if (! isset($uses[Concerns\Licensable::class])) {
            throw new InvalidArgumentException("Given [$userModel] does not implements '".Concerns\Licensable::class."' trait.");
        }

        static::$userModel = $userModel;
    }

    /**
     * Get the purchaser model.
     */
    public static function getUserModel(): string
    {
        if (! isset(static::$userModel)) {
            $provider = \config('auth.guards.web.provider');

            static::setUserModel(\config("auth.providers.{$provider}.model"));
        }

        return static::$userModel;
    }

    /**
     * Set auth token name.
     */
    public static function setAuthTokenName(string $name): void
    {
        if (\blank($name)) {
            throw new InvalidArgumentException("Unable to set blank value for auth_token");
        } elseif (! Column::validateColumnName($name)) {
            throw new InvalidArgumentException("[{$name}] not a valid column name for auth_token");
        }

        static::$authTokenName = $name;
    }

    /**
     * Get auth token name.
     */
    public static function getAuthTokenName(): string
    {
        return static::$authTokenName;
    }

    /**
     * Set supported hosts.
     */
    public static function setSupportedHosts(array $hosts): void
    {
        static::$supportedHosts = $hosts;
    }

    /**
     * Get supported hosts.
     *
     * @return array
     */
    public static function getSupportedHosts(): array
    {
        return static::$supportedHosts;
    }

    /**
     * Configure Satifest to not register its migrations.
     */
    public static function ignoreMigrations(): void
    {
        static::$runsMigrations = false;
    }

    /**
     * Register an event listener for the Nova "serving" event.
     *
     * @param  \Closure|string  $callback
     *
     * @return void
     */
    public static function serving($callback): void
    {
        Event::listen(Events\ServingSatifest::class, $callback);
    }

    /**
     * Register routing for Satifest.
     */
    public static function route(?string $namespace): RouteRegistrar
    {
        $routing = Value\Routing::make(\config('satifest.url') ?? '/');

        return $routing($namespace);
    }

    /**
     * Get package name from GitHub.
     */
    public static function packageNameFromUrl(string $url): string
    {
        $package = Value\RepoUrl::make($url);

        if (! $package->isSupportedDomain()) {
            throw new InvalidArgumentException("Unable to resolved none supported repository URL: {$url}");
        }

        return $package->name();
    }
}
