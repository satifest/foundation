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
        $routing = Value\Routing::make(\config('satifest.url') ?? '/');

        return \tap(Route::prefix($routing->prefix()), function ($router) use ($routing, $namespace) {
            if (! empty($namespace)) {
                $router->namespace($namespace);
            }

            if (! \is_null($domain = $routing->domain())) {
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
