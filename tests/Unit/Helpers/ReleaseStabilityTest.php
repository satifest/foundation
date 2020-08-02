<?php

namespace Satifest\Foundation\Tests\Unit\Helpers;

use PHPUnit\Framework\TestCase;
use function Satifest\Foundation\release_stability;

class ReleaseStabilityTest extends TestCase
{
    /**
     * @test
     * @dataProvider stableReleases
     */
    public function it_can_detect_stable_releases($given)
    {
        $this->assertSame('stable', release_stability($given));
    }

    /**
     * @test
     * @dataProvider nightlyReleases
     */
    public function it_can_detect_nightly_releases($given)
    {
        $this->assertSame('nightly', release_stability($given));
    }

    /**
     * Stable releases.
     */
    public function stableReleases()
    {
        yield ['1.0.0.0'];
        yield ['1.1.0.0'];
        yield ['1.1.1.0'];
    }

    /**
     * Nightly releases.
     */
    public function nightlyReleases()
    {
        yield ['9999999-dev'];
    }
}
