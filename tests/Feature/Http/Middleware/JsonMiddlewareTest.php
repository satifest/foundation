<?php

namespace Satifest\Foundation\Tests\Feature\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Satifest\Foundation\Http\Middleware\JsonMiddleware;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Http\Middleware\JsonMiddleware feature tests
 */
class JsonMiddlewareTest extends TestCase
{
    /** @test */
    public function it_can_set_default_accept_to_application_json()
    {
        Route::middleware(JsonMiddleware::class)
            ->group(function () {
                Route::get('test-satifest/middleware/accept-json', function (Request $request) {
                    $this->assertSame('application/json', $request->headers->get('Accept'));

                    return 'OK';
                });
            });

        $this->get('test-satifest/middleware/accept-json')
            ->assertOk()
            ->assertSee('OK');
    }
}
