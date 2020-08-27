<?php

namespace Satifest\Foundation\Concerns;

use Illuminate\Database\Eloquent\Collection;
use Satifest\Foundation\Contracts\Licensing;
use Satifest\Foundation\License;
use Satifest\Foundation\Plan;
use Satifest\Foundation\Team;

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
        $license = License::forceCreate([
            'licensable_id' => $this->getKey(),
            'licensable_type' => $this->getMorphClass(),
            'name' => $licensing->name(),
            'provider' => $licensing->provider(),
            'uid' => $licensing->uid(),
            'type' => $licensing->type(),
            'amount' => (int) $licensing->price()->getAmount(),
            'currency' => (string) $licensing->price()->getCurrency(),
            'ends_at' => $licensing->endsAt(),
            'allocation' => $licensing->allocation(),
        ]);

        if ($plans === '*') {
            $plans = Plan::pluck('id')->all();
        }

        if (($plans instanceof Collection && $plans->isNotEmpty())
            || (\is_array($plans) && ! empty($plans))
        ) {
            $license->plans()->sync($plans);
        }

        return $license;
    }
}
