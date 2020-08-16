<?php

namespace Satifest\Foundation\Tests\Feature;

use Mockery as m;
use Satifest\Foundation\Events\ServingSatifest;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Tests\User;

/**
 * @testdox Satifest\Foundation\Satifest feature tests
 */
class SatifestTest extends TestCase
{
    /** @test */
    public function it_can_customize_auth()
    {
        Satifest::auth(static function () {
            return true;
        });

        $request = m::mock('Illuminate\Http\Request');

        $this->assertTrue(Satifest::check($request));
    }

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
    public function it_can_get_auth_token()
    {
        $this->assertSame('satifest_token', Satifest::getAuthTokenName());
    }

    /** @test */
    public function it_can_override_auth_token()
    {
        Satifest::setAuthTokenName('api_token');

        $this->assertSame('api_token', Satifest::getAuthTokenName());
    }


    /** @test */
    public function it_cant_override_auth_token_with_blank_value()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Unable to set blank value for auth_token');

        Satifest::setAuthTokenName(' ');
    }

    /**
     * @test
     * @dataProvider invalidColumnNameDataProvider
     */
    public function it_cant_override_auth_token_with_invalid_column_name($given)
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage("[{$given}] not a valid column name for auth_token");

        Satifest::setAuthTokenName($given);
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


    /**
     * Valid column name data provider.
     *
     * @return array
     */
    public function invalidColumnNameDataProvider()
    {
        yield ['email->"%27))%23injectedSQL'];
        yield [\str_pad('email', 65, 'x')];
    }
}
