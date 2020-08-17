<?php

namespace Satifest\Foundation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Satifest\Foundation\License;

class LicenseChanged
{
    use Dispatchable, SerializesModels;

    /**
     * The License model.
     *
     * @var \Satifest\Foundation\License|null
     */
    public $license;

    /**
     * The event state.
     *
     * @var string
     */
    public $state;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(?License $license = null, string $state)
    {
        $this->license = $license;
        $this->state = $state;
    }
}
