<?php

namespace Satifest\Foundation\Http\Middleware;

use Closure;
use Satifest\Foundation\Satifest;

class Authorize
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
        \abort_unless(Satifest::check($request), 403);

        return $next($request);
    }
}
