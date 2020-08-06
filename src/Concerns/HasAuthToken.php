<?php

namespace Satifest\Foundation\Concerns;

use Satifest\Foundation\Satifest;

trait HasAuthToken
{
    /**
     * Initialize Has Auth Token trait.
     */
    public static function initializeHasAuthToken(): void
    {
        $this->hidden[] = Satifest::getAuthTokenName();
    }

    /**
     * Get the auth token value.
     */
    public function getSatifestAuthToken(): string
    {
        return $this->getAttribute(Satifest::getAuthTokenName());
    }
}
