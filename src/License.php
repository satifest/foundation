<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sf_licenses';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => Casts\Money::class.':amount,currency',
        'ends_at' => 'datetime',
        'allocation' => 'int',
        'utilisation' => 'int',
    ];

    /**
     * License has many and belongs to relationship with Plans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'sf_license_plan', 'license_id', 'plan_id')->withTimestamps();
    }

    /**
     * License belongs to a User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Satifest::getUserModel(), 'user_id', 'id', 'user');
    }

    /**
     * Scope stable release.
     */
    public function scopeAccessibleBy(Builder $query, Model $user): Builder
    {
        return $query->where('user_id', '=', $user->getKey());
    }

    /**
     * Check whether allocation is under utilised.
     */
    public function underUtilised(): bool
    {
        return $this->allocation >== $this->utilisation;
    }

    /**
     * Check whether allocation has been utilised.
     */
    public function utilised(): bool
    {
        return $this->allocation === $this->utilisation;
    }
}
