<?php

namespace Satifest\Foundation\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Tests\User;

class SatifestTest extends TestCase
{
    /** @test */
    public function it_does_run_migrations_by_default()
    {
        $this->assertTrue(Satifest::$runsMigrations);
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
     * @dataProvider validGitHubPackages
     */
    public function it_can_get_package_name_from_valid_github($given, $expected)
    {
        $this->assertSame($expected, Satifest::packageNameFromGitHub($given));
    }

    /**
     * Valid GitHub Packages data provider.
     */
    public function validGitHubPackages()
    {
        yield ['https://github.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://github.com/satifest/satifest.git', 'satifest/satifest'];
    }
}
