<?php

namespace Satifest\Foundation\Tests\Feature\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Satifest\Foundation\Events\ServingSatifest;
use Satifest\Foundation\Http\Middleware\DispatchServingSatifestEvent;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Http\Middleware\DispatchServingSatifestEvent feature tests
 */
class DispatchServingSatifestEventTest extends TestCase
{
    /** @test */
    public function it_can_dispatch_serving_satifest_event()
    {
        Event::fake();

        Route::middleware(DispatchServingSatifestEvent::class)
            ->group(function () {
                Route::get('test-satifest/middleware/dispatch-serving-satifest-event', function (Request $request) {
                    return 'OK';
                });
            });

        $this->get('test-satifest/middleware/dispatch-serving-satifest-event')
            ->assertOk()
            ->assertSee('OK');

        Event::assertDispatched(ServingSatifest::class);
    }
}
