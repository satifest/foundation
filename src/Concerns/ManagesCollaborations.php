<?php

namespace Satifest\Foundation\Concerns;

trait ManagesCollaborations
{
    /**
     * Indicates if collaborations should be managed by Satifest.
     *
     * @var bool
     */
    public static $allowsCollaborations = true;

    /**
     * Disable collaborations.
     */
    public static function disableCollaborations(): void
    {
        static::$allowsCollaborations = false;
    }
}
