<?php

namespace Satifest\Foundation\Tests\Unit\Value;

use PHPUnit\Framework\TestCase;
use Satifest\Foundation\Value\PackageUrl;

class PackageUrlTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGitHubPackages
     */
    public function it_can_validate_github_packages($given, $expected)
    {
        $package = PackageUrl::make($given);

        $this->assertTrue($package->isValid());
        $this->assertSame('github.com', $package->domain());
        $this->assertSame($expected, $package->packageName());
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
