<?php

namespace Satifest\Foundation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Satifest\Foundation\Repository;

class PackageChanged
{
    use Dispatchable, SerializesModels;

    /**
     * The Repository model.
     *
     * @var \Satifest\Foundation\Repository|null
     */
    public $repository;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(?Repository $repository = null)
    {
        $this->repository = $repository;
    }
}
