<?php

namespace Satifest\Foundation\Concerns;

use Illuminate\Database\Eloquent\Collection;
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
        return $this->hasMany(License::class, 'user_id', 'id');
    }

    /**
     * Create a new license for the user.
     *
     * @param  \Illuminate\Support\Collection|array  $plans
     */
    public function createLicense(Licensing $licensing, $plans = []): License
    {
        $license = License::forceCreate([
            'user_id' => $this->getKey(),
            'provider' => $licensing->provider(),
            'uid' => $licensing->uid(),
            'type' => $licensing->type(),
            'amount' => (int) $licensing->price()->getAmount(),
            'currency' => (string) $licensing->price()->getCurrency(),
            'ends_at' => $licensing->endsAt(),
        ]);

        if (($plans instanceof Collection && $plans->isNotEmpty())
            || (\is_array($plans) && ! empty($plans))
        ) {
            $license->plans()->sync($plans);
        }

        return $license;
    }
}
