<?php

namespace Satifest\Foundation\Concerns;

trait WithCashierPaddle
{
    public static function hookToCashierPaddle(): void
    {
        // - enable webhook route for paddle
        // - add subscribed handler to generate Licensing::makeSubscription()
        // - add paid handler to generate Licensing::makePurchase()
        // - add subscribed failed/reject to revoke Licensing::makeSubscription()

    }
}
