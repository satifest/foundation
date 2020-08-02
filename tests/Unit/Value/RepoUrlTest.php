<?php

namespace Satifest\Foundation\Tests\Unit\Value;

use PHPUnit\Framework\TestCase;
use Satifest\Foundation\Value\RepoUrl;

/**
 * @testdox Satifest\Foundation\Value\RepoUrl unit tests
 */
class RepoUrlTest extends TestCase
{
    /**
     * @test
     * @dataProvider validPackageUrls
     */
    public function it_can_validate_valid_packages($given, $expected)
    {
        $package = RepoUrl::make($given);

        $this->assertTrue($package->isValid());
        $this->assertSame($expected, $package->name());

        $this->assertSame("https://{$package->domain()}/{$expected}", (string) $package);
    }

    /**
     * @test
     * @dataProvider invalidPackageUrls
     */
    public function it_cant_validate_invalid_packages($given)
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Unable to find package name from invalid VCS URL');

        $package = RepoUrl::make($given);

        $this->assertFalse($package->isValid());

        $package->name();
    }

    /**
     * Valid GitHub Packages data provider.
     */
    public function validPackageUrls()
    {
        yield ['https://github.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://github.com/satifest/satifest.git', 'satifest/satifest'];
        yield ['git@github.com:satifest/satifest.git', 'satifest/satifest'];

        yield ['https://gitlab.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://gitlab.com/satifest/satifest.git', 'satifest/satifest'];
        yield ['git@gitlab.com:satifest/satifest.git', 'satifest/satifest'];
    }

    /**
     * Invalid GitHub Packages data provider.
     */
    public function invalidPackageUrls()
    {
        yield ['https://github.com/orgs/satifest/satifest'];
        yield ['https://bitbucket.org/satifest/satifest'];
        yield ['https://bitbucket.org/satifest/satifest.git'];
        yield ['https://bitbucket.org/orgs/satifest/satifest'];
    }
}
