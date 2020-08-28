<?php

namespace Satifest\Foundation\Http;

use Satifest\Foundation\Http\Middleware\Authorize;
use Satifest\Foundation\Http\Middleware\DispatchServingSatifestEvent;

class RouteRegistrar extends \Illuminate\Routing\RouteRegistrar
{
    /**
     * Include backend middleware.
     *
     * @return $this
     */
    public function withBackendMiddleware()
    {
        return $this->middleware(\config('satifest.middleware', [
            'web',
            'auth',
            DispatchServingSatifestEvent::class,
            Authorize::class,
        ]));
    }
}
