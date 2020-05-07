<?php

namespace Satifest\Foundation\Observers;

use Satifest\Foundation\Events\PackageHasChanged;
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
        \event(new PackageHasChanged($repository));
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
        if ($repository->wasChanged(['name', 'type', 'url'])) {
            \event(new PackageHasChanged($repository));
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
        \event(new PackageHasChanged($repository));
    }

    /**
     * Handle the repository "restored" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     *
     * @return void
     */
    public function restored(Repository $repository)
    {
        \event(new PackageHasChanged($repository));
    }

    /**
     * Handle the repository "force deleted" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     *
     * @return void
     */
    public function forceDeleted(Repository $repository)
    {
        \event(new PackageHasChanged($repository));
    }
}
