<?php

namespace Satifest\Foundation\Concerns;

use InvalidArgumentException;
use Laravie\QueryFilter\Column;

trait AuthorizesRequests
{
    /**
     * Auth token name.
     *
     * @var string
     */
    protected static $authTokenName = 'satifest_token';

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

    /**
     * Set auth token name.
     *
     * @throws \InvalidArgumentException
     */
    public static function setAuthTokenName(string $name): void
    {
        if (\blank(\trim($name, ' '))) {
            throw new InvalidArgumentException('Unable to set blank value for auth_token');
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
}
