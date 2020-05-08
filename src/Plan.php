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
    protected $table = 'plans';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => Casts\Money::class.':amount,currency',
    ];

    /**
     * Plan belongs to many Purchasess.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'plan_purchase', 'plan_id', 'purchase_id')->withTimestamps();
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
}
