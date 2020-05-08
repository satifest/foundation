<?php

namespace Satifest\Foundation\Observers;

use Carbon\Carbon;
use Satifest\Foundation\Events\PlanPurchased;
use Satifest\Foundation\Satifest\Foundation\Purchase;

class PurchaseObserver
{
    /**
     * Handle the purchase "created" event.
     *
     * @param  \Satifest\Foundation\Satifest\Foundation\Purchase  $purchase
     *
     * @return void
     */
    public function created(Purchase $purchase)
    {
        \event(new PlanPurchased($purchase));
    }

    /**
     * Handle the purchase "updated" event.
     *
     * @param  \Satifest\Foundation\Satifest\Foundation\Purchase  $purchase
     *
     * @return void
     */
    public function updated(Purchase $purchase)
    {
        if ($purchase->wasChanged('revoked_at') && ! \is_null($purchase->revoked_at)) {
            \event(new PurchaseRevoked($purchase));
        }
    }

    /**
     * Handle the purchase "deleting" event.
     *
     * @param  \Satifest\Foundation\Satifest\Foundation\Purchase  $purchase
     *
     * @return void
     */
    public function deleting(Purchase $purchase)
    {
        $purchase->revoked_at = Carbon::now();
    }

    /**
     * Handle the purchase "deleted" event.
     *
     * @param  \Satifest\Foundation\Satifest\Foundation\Purchase  $purchase
     *
     * @return void
     */
    public function deleted(Purchase $purchase)
    {
        \event(new PurchaseRevoked($purchase));
    }

    /**
     * Handle the purchase "restored" event.
     *
     * @param  \Satifest\Foundation\Satifest\Foundation\Purchase  $purchase
     *
     * @return void
     */
    public function restored(Purchase $purchase)
    {
        //
    }

    /**
     * Handle the purchase "force deleted" event.
     *
     * @param  \Satifest\Foundation\Satifest\Foundation\Purchase  $purchase
     *
     * @return void
     */
    public function forceDeleted(Purchase $purchase)
    {
        \event(new PurchaseRevoked($purchase));
    }
}
