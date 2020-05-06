<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Release extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'releases';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
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
     * Scope release by repository name.
     */
    public function scopeByRepositoryName(Builder $query, string $name): Builder
    {
        return $query->whereHas('repository', static function ($query) use ($name) {
            return $query->where('name', '=', $name);
        });
    }
}
