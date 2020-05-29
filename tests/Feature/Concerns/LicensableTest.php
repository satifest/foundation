<?php

namespace Satifest\Foundation\Tests\Feature\Concerns;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Money\Money;
use Satifest\Foundation\License;
use Satifest\Foundation\Licensing;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Tests\User;

class LicensableTest extends TestCase
{
    /** @test */
    public function it_can_create_license_for_user()
    {
        $user = \factory(User::class)->create();

        $license = $user->createLicense(Licensing::makePurchase(
            'stripe', '4eC39HqLyjWDarjtT1zdp7dc', Money::USD(3500)
        ));

        $this->assertInstanceOf(License::class, $license);

        $this->assertDatabaseHas('sf_licenses', [
            'provider' => 'stripe',
            'uid' => '4eC39HqLyjWDarjtT1zdp7dc',
            'type' => 'purchase',
            'amount' => '3500',
            'currency' => 'USD',
            'user_id' => $user->getKey(),
        ]);
    }
}
