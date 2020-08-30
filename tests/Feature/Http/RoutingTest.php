<?php

namespace Satifest\Foundation\Tests\Feature\Http;

use Illuminate\Contracts\Support\Arrayable;
use Satifest\Foundation\Http\Routing;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Testing\Factories\UserFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Http\Routing feature tests
 */
class RoutingTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set([
            'app.url' => 'http://satifest.test',
        ]);
    }

    /**
     * @test
     * @dataProvider routingDataProvider
     */
    public function it_can_parse_routing($given, $domain, $prefix)
    {
        $routing = Routing::make($given);

        $this->assertInstanceOf(Arrayable::class, $routing);

        $this->assertSame($domain, $routing->domain());
        $this->assertSame($prefix, $routing->prefix());
        $this->assertSame([
            'domain' => $domain,
            'prefix' => $prefix,
        ], $routing->toArray());
    }

    /** @test */
    public function it_can_create_and_handle_dashboard_route()
    {
        Satifest::auth(function ($user) {
            return ! \is_null($user);
        });

        Satifest::dashboardRoute('Satifest\Foundation\Tests\Feature\Http')
            ->withBackendMiddlewares()
            ->group(function($router) {
                $router->get('test-route', 'TestController');
            });

        $user = UserFactory::new()->create();

        $this->actingAs($user)->get('test-route')->assertOk();
    }


    /** @test */
    public function it_can_create_and_handle_dashboard_route_as_guest()
    {
        Satifest::auth(function ($user) {
            return ! \is_null($user);
        });

        Satifest::dashboardRoute('Satifest\Foundation\Tests\Feature\Http')
            ->withBackendMiddlewares()
            ->group(function($router) {
                $router->get('test-route', 'TestController');

                $router->get('login', function() {
                    return 'login page';
                })->name('login');
            });

        $this->get('test-route')->assertRedirect('login');
    }

    /**
     * Routing data provider.
     */
    public function routingDataProvider()
    {
        yield ['/', null, '/'];
        yield ['satis', null, 'satis'];
        yield ['/satis', null, 'satis'];
        yield ['satis/', null, 'satis'];
        yield ['/satis/', null, 'satis'];
        yield ['http://localhost/satis', null, 'satis'];
        yield ['https://localhost/satis', null, 'satis'];
        yield ['http://satifest.demo/satis', 'satifest.demo', 'satis'];
        yield ['https://satifest.demo/satis', 'satifest.demo', 'satis'];
        yield ['http://satifest.test/satis', null, 'satis'];
        yield ['https://satifest.test/satis', null, 'satis'];
    }
}
