<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;

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
}
