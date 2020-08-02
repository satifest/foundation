<?php

namespace Satifest\Foundation\Tests\Unit;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Tests\User;

/**
 * @testdox Satifest\Foundation\Satifest unit tests
 */
class SatifestTest extends TestCase
{
    protected $defaultSupportedHosts = [];

    protected function setUp(): void
    {
        $this->defaultSupportedHosts = Satifest::getSupportedHosts();
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        Satifest::$runsMigrations = true;
        Satifest::setSupportedHosts($this->defaultSupportedHosts);

        m::close();
    }

    /** @test */
    public function it_has_the_default_supported_hosts()
    {
        $this->assertSame([
            'github.com',
            'gitlab.com',
        ], Satifest::getSupportedHosts());
    }

    /** @test */
    public function it_can_set_supported_hosts()
    {
        Satifest::setSupportedHosts(['github.com']);

        $this->assertSame([
            'github.com',
        ], Satifest::getSupportedHosts());
    }

    /** @test */
    public function it_has_the_default_authorize_on_local()
    {
        $app = m::mock('Illuminate\Contracts\Foundation\Application');
        $app->shouldReceive('environment')->with('local')->andReturn(true);

        \Illuminate\Container\Container::setInstance($app);

        $request = m::mock('Illuminate\Http\Request');

        $this->assertTrue(app()->environment('local'));

        $this->assertTrue(Satifest::check($request));
    }

    /** @test */
    public function it_does_run_migrations_by_default()
    {
        $this->assertTrue(Satifest::$runsMigrations);
    }

    /** @test */
    public function it_can_skipped_running_migrations()
    {
        Satifest::ignoreMigrations();

        $this->assertFalse(Satifest::$runsMigrations);
    }

    /** @test */
    public function it_can_set_custom_purchaser_model()
    {
        Satifest::setUserModel(User::class);

        $this->assertSame(User::class, Satifest::getUserModel());
    }

    /** @test */
    public function it_cant_set_custom_puchaser_model_that_doesnt_implement_has_purchases_trait()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage("Given [Illuminate\Foundation\Auth\User] does not implements 'Satifest\Foundation\Concerns\Licensable' trait.");

        Satifest::setUserModel('Illuminate\Foundation\Auth\User');
    }

    /**
     * @test
     * @dataProvider validPackageUrls
     */
    public function it_can_get_package_name_from_valid_github($given, $expected)
    {
        $this->assertSame($expected, Satifest::packageNameFromUrl($given));
    }

    /**
     * @test
     * @dataProvider invalidPackageUrls
     */
    public function it_cant_get_package_name_from_invalid_github($given, $expected)
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage("Unable to resolved none supported repository URL: {$given}");

        $this->assertSame($expected, Satifest::packageNameFromUrl($given));
    }

    /**
     * Valid GitHub Packages data provider.
     */
    public function validPackageUrls()
    {
        yield ['https://github.com/satifest/demo-test-package', 'satifest/demo-test-package'];
        yield ['https://github.com/satifest/demo-test-package.git', 'satifest/demo-test-package'];
        yield ['https://github.com/satifest/foundation', 'satifest/foundation'];
        yield ['https://github.com/satifest/foundation.git', 'satifest/foundation'];
        yield ['https://github.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://github.com/satifest/satifest.git', 'satifest/satifest'];
        yield ['https://gitlab.com/satifest/demo-test-package', 'satifest/demo-test-package'];
        yield ['https://gitlab.com/satifest/demo-test-package.git', 'satifest/demo-test-package'];
        yield ['https://gitlab.com/satifest/foundation', 'satifest/foundation'];
        yield ['https://gitlab.com/satifest/foundation.git', 'satifest/foundation'];
        yield ['https://gitlab.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://gitlab.com/satifest/satifest.git', 'satifest/satifest'];
    }

    /**
     * Invalid GitHub Packages data provider.
     */
    public function invalidPackageUrls()
    {
        yield ['https://bitbucket.org/satifest/demo-test-package', 'satifest/demo-test-package'];
        yield ['https://bitbucket.org/satifest/demo-test-package.git', 'satifest/demo-test-package'];
        yield ['https://bitbucket.org/satifest/foundation', 'satifest/foundation'];
        yield ['https://bitbucket.org/satifest/foundation.git', 'satifest/foundation'];
        yield ['https://bitbucket.org/satifest/satifest', 'satifest/satifest'];
        yield ['https://bitbucket.org/satifest/satifest.git', 'satifest/satifest'];
    }
}
