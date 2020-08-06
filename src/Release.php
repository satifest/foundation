<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    /**
     * Release type constants.
     */
    public const STABLE = 'stable';
    public const NIGHTLY = 'nightly';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sf_releases';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
        'synced_at' => 'datetime',
    ];

    /**
     * Release belongs to a Repository.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function repository()
    {
        return $this->belongsTo(Repository::class, 'repository_id', 'id', 'repository');
    }

    /**
     * Scope accessible by.
     */
    public function scopeAccessibleBy(Builder $query, Model $user): Builder
    {
        return $query->whereHas('repository', static function ($query) use ($user) {
            return $query->whereIn(
                column_name(Repository::class, 'id'),
                Repository::accessibleBy($user)->pluck('id')
            );
        });
    }

    /**
     * Scope stable release.
     */
    public function scopeStable(Builder $query): Builder
    {
        return $query->whereIn('type', [self::STABLE]);
    }

    /**
     * Scope release by package name.
     *
     * @param  string|array  $name
     */
    public function scopeByPackage(Builder $query, $name): Builder
    {
        if (! \is_string($name)) {
            $name = \implode('/', $name);
        }

        return $query->whereHas('repository', static function ($query) use ($name) {
            return $query->where(column_name(Repository::class, 'package'), '=', $name);
        });
    }

    /**
     * Scope release by repository name.
     *
     * @param  string|array  $name
     */
    public function scopeByName(Builder $query, $name): Builder
    {
        if (! \is_string($name)) {
            $name = \implode('/', $name);
        }

        return $query->whereHas('repository', static function ($query) use ($name) {
            return $query->where(column_name(Repository::class, 'name'), '=', $name);
        });
    }

    /**
     * Get virtual "name" accessor.
     */
    public function getNameAttribute(): string
    {
        return $this->title ?? $this->version;
    }

    /**
     * Check if release is stable version.
     */
    public function stableVersion(): bool
    {
        return $this->type === self::STABLE;
    }

    /**
     * Check if release is not stable version.
     */
    public function notStableVersion(): bool
    {
        return ! $this->stableVersion();
    }
}
