<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'amount' => Casts\Money::class.':amount,currency',
        'purchased_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    /**
     * Purchase belongs to many Plans.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_purchase', 'purchase_id', 'plan_id')->withTimestamps();
    }

    /**
     * Purchase belongs to a Puchaser.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchaser()
    {
        return $this->belongsTo(Satifest::getPurchaserModel(), 'purchaser_id', 'id', 'purchaser');
    }
}
