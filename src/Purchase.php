<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;

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
     * Purchase belongs to a Plan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id', 'plan');
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
