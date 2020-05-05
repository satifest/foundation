<?php

namespace Satifest\Foundation\Observers;

use Satifest\Foundation\Repository;

class RepositoryObserver
{
    /**
     * Handle the repository "created" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     * @return void
     */
    public function created(Repository $repository)
    {
        //
    }

    /**
     * Handle the repository "updated" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     * @return void
     */
    public function updated(Repository $repository)
    {
        //
    }

    /**
     * Handle the repository "deleted" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     * @return void
     */
    public function deleted(Repository $repository)
    {
        //
    }

    /**
     * Handle the repository "restored" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     * @return void
     */
    public function restored(Repository $repository)
    {
        //
    }

    /**
     * Handle the repository "force deleted" event.
     *
     * @param  \Satifest\Foundation\Repository  $repository
     * @return void
     */
    public function forceDeleted(Repository $repository)
    {
        //
    }
}
