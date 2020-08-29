<?php

namespace Satifest\Foundation\Observers;

use Satifest\Foundation\Events\RepositoryChanged;
use Satifest\Foundation\Events\RepositoryCreated;
use Satifest\Foundation\Repository;

class RepositoryObserver
{
    /**
     * Handle the repository "created" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     *
     * @return void
     */
    public function created(Repository $repository)
    {
        \event(new RepositoryCreated($repository));

        $repository->createPlan();
    }

    /**
     * Handle the repository "updated" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     *
     * @return void
     */
    public function updated(Repository $repository)
    {
        if ($repository->wasChanged(['name', 'package', 'type', 'url'])) {
            \event(new RepositoryChanged($repository, __METHOD__));
        }
    }

    /**
     * Handle the repository "deleted" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     *
     * @return void
     */
    public function deleted(Repository $repository)
    {
        \event(new RepositoryChanged($repository, __METHOD__));
    }
}
