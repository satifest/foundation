<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
    /**
     * Repository provider constants.
     */
    public const GITHUB_PROVIDER = 'github';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sf_repositories';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'url' => Casts\RepositoryUrl::class,
    ];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::observe(new Observers\RepositoryObserver());
    }

    /**
     * Repository has many Plans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function plans()
    {
        return $this->hasMany(Plan::class, 'repository_id', 'id');
    }

    /**
     * Repository has many Releases.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function releases()
    {
        return $this->hasMany(Release::class, 'repository_id', 'id');
    }

    /**
     * Scope accessible by.
     */
    public function scopeAccessibleBy(Builder $query, Model $user): Builder
    {
        return $query->whereHas('plans', static function ($query) use ($user) {
            return $query->accessibleBy($user);
        });
    }

    /**
     * Scope release by repository url.
     */
    public function scopeByUrl(Builder $query, ?string $url): Builder
    {
        if (\is_null($url)) {
            return $query->where('id', '<', 1);
        }

        $repositoryUrl = Value\RepositoryUrl::make($url);

        return $query->where('url', '=', (string) $repositoryUrl);
    }

    /**
     * Scope release by repository name.
     *
     * @deprecated
     */
    public function scopeByPackageUrl(Builder $query, ?string $url): Builder
    {
        return $this->scopeByUrl($query, $url);
    }

    /**
     * Composer name accessor.
     */
    public function getComposerNameAttribute($value): string
    {
        return $this->package ?? $this->name;
    }

    /**
     * Webhook accessor.
     */
    public function getHookUrlAttribute($value): Value\HookUrl
    {
        return new Value\HookUrl($this->url, $this->provider);
    }

    /**
     * Create plan for Repository.
     */
    public function createPlan(?string $name = null, string $constraint = '*'): Plan
    {
        return Plan::forceCreate([
            'repository_id' => $this->getKey(),
            'name' => $name ?? "Plan for {$this->name}",
            'constraint' => $constraint,
        ]);
    }
}
