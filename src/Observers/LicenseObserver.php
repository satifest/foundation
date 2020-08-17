<?php

namespace Satifest\Foundation\Observers;

use Satifest\Foundation\Events\LicenseChanged;
use Satifest\Foundation\Events\LicenseCreated;
use Satifest\Foundation\License;

class LicenseObserver
{
    /**
     * Handle the license "created" event.
     *
     * @param  \Satifest\Foundation\License  $license
     *
     * @return void
     */
    public function created(License $license)
    {
        \event(new LicenseCreated($license));
    }

    /**
     * Handle the license "updated" event.
     *
     * @param  \Satifest\Foundation\License  $license
     *
     * @return void
     */
    public function updated(License $license)
    {
        \event(new LicenseChanged($license, __METHOD__));
    }

    /**
     * Handle the license "deleted" event.
     *
     * @param  \Satifest\Foundation\License  $license
     *
     * @return void
     */
    public function deleted(License $license)
    {
        \event(new LicenseChanged($license, __METHOD__));
    }
}
