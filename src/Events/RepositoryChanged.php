<?php

namespace Satifest\Foundation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Satifest\Foundation\Repository;

class RepositoryChanged
{
    use Dispatchable, SerializesModels;

    /**
     * The Repository model.
     *
     * @var \Satifest\Foundation\Repository|null
     */
    public $repository;

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
    public function __construct(?Repository $repository = null, string $state)
    {
        $this->repository = $repository;
        $this->state = $state;
    }
}
