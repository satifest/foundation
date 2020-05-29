<?php

namespace Satifest\Foundation\Tests\Feature;

use Mockery as m;
use Satifest\Foundation\Events\ServingSatifest;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Tests\User;

class SatifestTest extends TestCase
{
    /** @test */
    public function it_can_get_user_model()
    {
        $this->assertSame(User::class, Satifest::getUserModel());
    }

    /** @test */
    public function it_can_override_user_model()
    {
        Satifest::setUserModel(User::class);

        $this->assertSame(User::class, Satifest::getUserModel());
    }

    /** @test */
    public function it_can_handle_serving_event()
    {
        Satifest::serving(function () {
            $GLOBALS['satifest-serving'] = true;
            $this->addToAssertionCount(1);
        });

        \event(new ServingSatifest(m::mock('Illuminate\Http\Request')));
        $this->assertTrue($GLOBALS['satifest-serving']);

        unset($GLOBALS['satifest-serving']);
    }

    /** @test */
    public function it_can_create_route_for_satifest()
    {
        config([
            'app.url' => 'http://satifest.test',
            'satifest.url' => 'http://satifest.test/satis',
        ]);

        Satifest::route(null)
            ->group(function ($router) {
                $router->get('test-route', function () {
                    return 'satifest route test';
                });
            });

        $this->get('satis/test-route')
            ->assertOk()
            ->assertSee('satifest route test');
    }
}
