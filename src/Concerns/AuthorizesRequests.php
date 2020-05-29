<?php

namespace Satifest\Foundation\Concerns;

trait AuthorizesRequests
{
    /**
     * The callback that should be used to authenticate Satifest users.
     *
     * @var \Closure
     */
    public static $authUsing;

    /**
     * Register the Satifest authentication callback.
     *
     * @param  \Closure  $callback
     */
    public static function auth($callback): void
    {
        static::$authUsing = $callback;
    }

    /**
     * Determine if the given request can access the Satifest dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public static function check($request): bool
    {
        return (static::$authUsing ?: static function () {
            return \app()->environment('local');
        })($request);
    }
}
