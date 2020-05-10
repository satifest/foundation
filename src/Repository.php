<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;

class Repository extends Model
{
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
        'url' => Casts\RepoUrl::class,
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
     * Create plan for Repository.
     */
    public function createPlan(?string $name = null, string $constraint = '*'): Plan
    {
        return Plan::forceCreate([
            'repository_id' => $this->getKey(),
            'name' => $name ?? "Basic Plan for {$this->name}",
            'constraint' => $constraint,
        ]);
    }
}
