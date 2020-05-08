<?php

namespace Satifest\Foundation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Satifest\Foundation\Purchase;

class PlanPurchased
{
    use Dispatchable, SerializesModels;

    /**
     * The Purchase model.
     *
     * @var \Satifest\Foundation\Purchase
     */
    public $purchase;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }
}
