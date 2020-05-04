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
    protected $table = 'repositories';

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
}
