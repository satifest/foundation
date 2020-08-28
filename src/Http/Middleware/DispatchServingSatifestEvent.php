<?php

namespace Satifest\Foundation\Http\Middleware;

use Closure;
use Satifest\Foundation\Events\ServingSatifest;

class DispatchServingSatifestEvent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        ServingSatifest::dispatch($request);

        return $next($request);
    }
}
