<?php

namespace Satifest\Foundation\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait ManagesLicenses
{
    /**
     * Allow to define custom licenses resolver.
     *
     * @var \Closure|null
     */
    protected static $licensesResolver = null;

    /**
     * Set licenses resolver for the project.
     */
    public static function licensesUsing(?Closure $callback): void
    {
        static::$licensesResolver = $callback;
    }

    /**
     * Set liceses resolver using team relation name for user.
     */
    public static function licensesUsingTeam(string $teamRelationName): void
    {
        static::licensesUsing(function ($user, $query) use ($teamRelationName) {
            $user->loadRelation($teamRelationName);

            return $query->licensee($user->{$teamRelationName})
                ->orCollaborators($user);
        });
    }

    /**
     * Resolve accessible licenses using licenses resolver.
     */
    public static function licensesAccessibleBy(Builder $query, Model $user): Builder
    {
        if (static::$licensesResolver === null) {
            return $query->licensee($user)
                ->orCollaborators($user);
        }

        \call_user_func(static::$licensesResolver, $user, $query);

        return $query;
    }
}
