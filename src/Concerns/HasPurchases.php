<?php

namespace Satifest\Foundation\Concerns;

use Satifest\Foundation\Purchase;

trait HasPurchases
{
    /**
     * Purchaser has many Purchases.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'purchaser_id', 'id');
    }
}
