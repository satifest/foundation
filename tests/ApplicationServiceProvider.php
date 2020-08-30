<?php

namespace Satifest\Foundation\Tests;

use Satifest\Foundation\SatifestServiceProvider;

class ApplicationServiceProvider extends SatifestServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSatifestProvider();
    }
}
