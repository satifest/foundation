<?php

namespace Satifest\Foundation\Tests\Feature\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Money\Money;
use Satifest\Foundation\License;
use Satifest\Foundation\Licensing;
use Satifest\Foundation\Plan;
use Satifest\Foundation\Repository;
use Satifest\Foundation\Tests\TestCase;
use Satifest\Foundation\Tests\User;

/**
 * @testdox Satifest\Foundation\Concerns\Licensable feature tests
 */
class LicensableTest extends TestCase
{
    /** @test */
    public function it_has_many_licenses_relation()
    {
        $user = \factory(User::class)->make();

        $licenses = $user->licenses();

        $this->assertInstanceOf(HasMany::class, $licenses);
        $this->assertNull($licenses->getParentKey());
        $this->assertSame('users.id', $licenses->getQualifiedParentKeyName());
        $this->assertSame('user_id', $licenses->getForeignKeyName());
        $this->assertSame('sf_licenses.user_id', $licenses->getQualifiedForeignKeyName());
        $this->assertSame('id', $licenses->getLocalKeyName());
    }

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

        $this->assertSame(0, $license->plans()->count());
    }

    /** @test */
    public function it_can_create_license_with_plans_for_user()
    {
        $user = \factory(User::class)->create();
        $repository = \factory(Repository::class)->create([
            'name' => 'satifest/test-demo-package',
            'url' => 'http://github.com/satifest/test-demo-package',
        ]);
        $plans = \factory(Plan::class, 2)->create([
            'repository_id' => $repository->id,
        ]);

        $license = $user->createLicense(Licensing::makePurchase(
            'stripe', '4eC39HqLyjWDarjtT1zdp7dc', Money::USD(3500)
        ), $plans->pluck('id')->all());

        $this->assertInstanceOf(License::class, $license);

        $this->assertDatabaseHas('sf_licenses', [
            'provider' => 'stripe',
            'uid' => '4eC39HqLyjWDarjtT1zdp7dc',
            'type' => 'purchase',
            'amount' => '3500',
            'currency' => 'USD',
            'user_id' => $user->getKey(),
        ]);

        $this->assertSame(2, $license->plans()->count());
    }
}
