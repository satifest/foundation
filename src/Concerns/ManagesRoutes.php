<?php

namespace Satifest\Foundation\Concerns;

use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Event;
use Satifest\Foundation\Events\ServingSatifest;
use Satifest\Foundation\Value\Routing;

trait ManagesRoutes
{
    /**
     * Register an event listener for the Nova "serving" event.
     *
     * @param  \Closure|string  $callback
     *
     * @return void
     */
    public static function serving($callback): void
    {
        Event::listen(ServingSatifest::class, $callback);
    }

    /**
     * Register routing for Satifest.
     */
    public static function route(?string $namespace, ?string $prefix = null): RouteRegistrar
    {
        $routing = Routing::make(\config('satifest.url') ?? '/');

        return $routing($namespace, null);
    }

    /**
     * Register routing for Satifest.
     */
    public static function dashboardRoute(?string $namespace): RouteRegistrar
    {
        return static::route($namespace, \config('satifest.path'));
    }
}
