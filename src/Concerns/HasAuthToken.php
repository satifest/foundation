<?php

namespace Satifest\Foundation\Concerns;

use Satifest\Foundation\Satifest;

trait HasAuthToken
{
    /**
     * Boot Has Auth Token
     */
    public static function bootHasAuthToken(): void
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
