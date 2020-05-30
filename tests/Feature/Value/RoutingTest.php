<?php

namespace Satifest\Foundation\Tests\Feature\Value;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Value\Routing;

/**
 * @testdox Satifest\Foundation\Value\Routing feature tests
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
