<?php

namespace Satifest\Foundation\Tests\Feature\Http;

use Illuminate\Http\Request;

class TestController
{
    public function __invoke(Request $request)
    {
        return response('Ok');
    }
}
