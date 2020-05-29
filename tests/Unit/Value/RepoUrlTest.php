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
     * Valid GitHub Packages data provider.
     */
    public function validGitHubPackages()
    {
        yield ['https://github.com/satifest/satifest', 'satifest/satifest'];
        yield ['https://github.com/satifest/satifest.git', 'satifest/satifest'];
    }
}
