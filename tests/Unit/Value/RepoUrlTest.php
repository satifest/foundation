<?php

namespace Satifest\Foundation\Tests\Unit\Value;

use PHPUnit\Framework\TestCase;
use Satifest\Foundation\Value\RepoUrl;

class RepoUrlTest extends TestCase
{
    /**
     * @test
     * @dataProvider validGitHubPackages
     */
    public function it_can_validate_github_packages($given, $expected)
    {
        $package = RepoUrl::make($given);

        $this->assertTrue($package->isValid());
        $this->assertSame('github.com', $package->domain());
        $this->assertSame($expected, $package->name());

        $this->assertSame("https://github.com/{$expected}", (string) $package);
    }

    /**
     * @test
     * @dataProvider invalidGitHubPackages
     */
    public function it_cant_validate_invalid_github_packages($given)
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
    public function validGitHubPackages()
    {
        yield ['https://github.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://github.com/satifest/satifest.git', 'satifest/satifest'];
    }

    /**
     * Invalid GitHub Packages data provider.
     */
    public function invalidGitHubPackages()
    {
        yield ['https://gitlab.com/satifest/satifest'];
        yield ['https://gitlab.com/satifest/satifest.git'];
    }
}
