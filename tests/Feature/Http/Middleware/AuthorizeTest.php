<?php

namespace Satifest\Foundation\Tests\Feature\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Satifest\Foundation\Http\Middleware\Authorize;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Http\Middleware\Authorize feature tests
 */
class AuthorizeTest extends TestCase
{
    /** @test */
    public function it_can_authorize_valid_request()
    {
        Satifest::auth(function () {
            return true;
        });

        Route::middleware(Authorize::class)
            ->group(function () {
                Route::get('test-satifest/middleware/authorize', function (Request $request) {
                    return 'OK';
                });
            });

        $this->get('test-satifest/middleware/authorize')
            ->assertOk()
            ->assertSee('OK');
    }

    /** @test */
    public function it_cant_authorize_invalid_request()
    {
        Satifest::auth(function () {
            return false;
        });

        Route::middleware(Authorize::class)
            ->group(function () {
                Route::get('test-satifest/middleware/authorize', function (Request $request) {
                    return 'OK';
                });
            });

        $this->get('test-satifest/middleware/authorize')
            ->assertStatus(403)
            ->assertDontSee('OK');
    }
}
