<?php

namespace Satifest\Foundation\Events;

use Satifest\Foundation\Repository;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class PackageHasChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
