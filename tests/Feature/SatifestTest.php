<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Satifest\Foundation\Satifest;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Tests\User;

class SatifestTest extends TestCase
{
    /** @test */
    public function it_can_get_user_model()
    {
        $this->assertSame(User::class, Satifest::getUserModel());
    }

    public function it_can_override_user_model()
    {
        Satifest::setUserModel('Illuminate\Foundation\Auth\User');

        $this->assertSame('Illuminate\Foundation\Auth\User', Satifest::getUserModel());
    }
}
