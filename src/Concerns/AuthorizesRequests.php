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
     * @return static
     */
    public static function auth($callback)
    {
        static::$authUsing = $callback;

        return new static;
    }

    /**
     * Determine if the given request can access the Satifest dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function check($request)
    {
        return (static::$authUsing ?: static function () {
            return \app()->environment('local');
        })($request);
    }
}
