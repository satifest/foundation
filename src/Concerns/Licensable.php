<?php

namespace Satifest\Foundation\Concerns;

use Satifest\Foundation\Actions\CreateLicense;
use Satifest\Foundation\Contracts\Licensing;
use Satifest\Foundation\License;

trait Licensable
{
    /**
     * Licensable has many licenses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licenses()
    {
        return $this->morphMany(License::class, 'licensable');
    }

    /**
     * Create a new license for the user.
     *
     * @param  \Illuminate\Support\Collection|array|string  $plans
     */
    public function createLicense(Licensing $licensing, $plans = []): License
    {
        $action = CreateLicense::licensable($this);

        return $action($licensing, $plans);
    }
}
