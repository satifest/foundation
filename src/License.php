<?php

namespace Satifest\Foundation;

use Carbon\Carbon;
use Carbon\CarbonInterface;
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
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::observe(new Observers\LicenseObserver());
    }

    /**
     * License has many relationship with Teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany(Team::class, 'license_id', 'id');
    }

    /**
     * License has many relationship with Invites.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invites()
    {
        return $this->teams()->whereNull('user_id');
    }

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
     * License has many and belongs to relationship with Teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collaborationTeams()
    {
        return $this->belongsToMany(Satifest::getUserModel(), 'sf_teams', 'license_id', 'user_id')
            ->using(Team::class)
            ->withPivot('email', 'accepted_at')
            ->withTimestamps();
    }

    /**
     * Scope active on specific datetime.
     */
    public function scopeActiveOn(Builder $query, CarbonInterface $carbon): Builder
    {
        return $query->where(static function ($query) use ($carbon) {
            return $query->whereNull('ends_at')
                ->orWhere('ends_at', '>', $carbon);
        });
    }

    /**
     * Scope accessible by user.
     */
    public function scopeAccessibleBy(Builder $query, Model $user): Builder
    {
        if (! $user->exists) {
            return $query->where('id', '<', 1);
        }

        return $this->scopeActiveOn($query, Carbon::now())
            ->where(static function ($query) use ($user) {
                return $query->where('user_id', '=', $user->getKey())
                    ->orWhereHas('teams', static function ($query) use ($user) {
                        return $query->where(column_name(Team::class, 'user_id'), '=', $user->getKey());
                    });
            });
    }

    /**
     * Scope owned by user.
     */
    public function scopeLicensee(Builder $query, Model $user): Builder
    {
        if (! $user->exists) {
            return $query->where('id', '<', 1);
        }

        return $query->where(static function ($query) use ($user) {
            return $query->where('user_id', '=', $user->getKey());
        });
    }

    /**
     * Check whether allocation is under utilised.
     */
    public function underUtilised(): bool
    {
        return $this->allocation >= $this->utilisation;
    }

    /**
     * Check whether allocation has been utilised.
     */
    public function utilised(): bool
    {
        return $this->allocation === $this->utilisation;
    }
}
