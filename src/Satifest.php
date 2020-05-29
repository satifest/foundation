<?php

namespace Satifest\Foundation;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use InvalidArgumentException;
use Spatie\Url\Url;

class Satifest
{
    use Concerns\AuthorizesRequests;

    /**
     * Purchaser model (normally refer to the user).
     *
     * @var string
     */
    protected static $userModel;

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
        $satifestUrl = Url::fromString(\config('satifest.url') ?? '/');

        $prefix = $satifestUrl->getPath();

        if ($prefix !== '/') {
            $prefix = \trim($prefix, '/');
        }

        return \tap(Route::prefix($prefix), function ($router) use ($satifestUrl, $namespace) {
            if (! empty($namespace)) {
                $router->namespace($namespace);
            }

            $domain = \transform($satifestUrl->getHost(), static function ($host) {
                $appUrl = Url::fromString(\config('app.url') ?? '/');

                if ($appUrl->getHost() === $host) {
                    return null;
                }

                return ! \in_array($host, static::$ignoredHosts) ? $host : null;
            });

            if (! \is_null($domain)) {
                $router->domain($domain);
            }
        });
    }

    /**
     * Get package name from GitHub.
     */
    public static function packageNameFromGitHub(string $githubUrl): string
    {
        $package = Value\RepoUrl::make($githubUrl);

        if ($package->domain() !== 'github.com') {
            throw new InvalidArgumentException("Unable to resolved none GitHub url: {$githubUrl}");
        }

        return $package->name();
    }
}
