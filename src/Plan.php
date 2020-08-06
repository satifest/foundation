<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sf_plans';

    /**
     * Plan has many and belongs to relationship with Licenses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function licenses()
    {
        return $this->belongsToMany(License::class, 'sf_license_plan', 'plan_id', 'license_id')->withTimestamps();
    }

    /**
     * Plan belongs to a Repository.
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
        return $query->whereHas('licenses', static function ($query) use ($user) {
            return $query->accessibleBy($user);
        });
    }
}
