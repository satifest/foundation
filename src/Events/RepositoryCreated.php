<?php

namespace Satifest\Foundation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Satifest\Foundation\Repository;

class RepositoryCreated
{
    use Dispatchable, SerializesModels;

    /**
     * The Repository model.
     *
     * @var \Satifest\Foundation\Repository
     */
    public $repository;

    /**
     * The event state.
     *
     * @var string
     */
    public $state = 'created';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
}
