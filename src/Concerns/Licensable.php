<?php

namespace Satifest\Foundation\Concerns;

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
     */
    public function createLicense(Licensing $licensing): License
    {
        return License::forceCreate([
            'user_id' => $this->getKey(),
            'provider' => $licensing->provider(),
            'uid' => $licensing->uid(),
            'type' => $licensing->type(),
            'amount' => (int) $licensing->price()->getAmount(),
            'currency' => (string) $licensing->price()->getCurrency(),
            'ends_at' => $licensing->endsAt(),
        ]);
    }
}
