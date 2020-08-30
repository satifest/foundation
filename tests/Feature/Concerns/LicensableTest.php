<?php

namespace Satifest\Foundation\Tests\Feature\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Money\Money;
use Satifest\Foundation\License;
use Satifest\Foundation\Licensing;
use Satifest\Foundation\Testing\Factories\PlanFactory;
use Satifest\Foundation\Testing\Factories\RepositoryFactory;
use Satifest\Foundation\Testing\Factories\UserFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Concerns\Licensable feature tests
 */
class LicensableTest extends TestCase
{
    /** @test */
    public function it_has_many_licenses_relation()
    {
        $user = UserFactory::new()->make();

        $licenses = $user->licenses();

        $this->assertInstanceOf(MorphMany::class, $licenses);
        $this->assertNull($licenses->getParentKey());
        $this->assertSame('users.id', $licenses->getQualifiedParentKeyName());
        $this->assertSame('licensable_id', $licenses->getForeignKeyName());
        $this->assertSame('sf_licenses.licensable_id', $licenses->getQualifiedForeignKeyName());
        $this->assertSame('id', $licenses->getLocalKeyName());
    }

    /** @test */
    public function it_can_create_license_for_user()
    {
        $user = UserFactory::new()->create();

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
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
        ]);

        $this->assertSame(0, $license->plans()->count());
    }

    /** @test */
    public function it_can_create_license_for_user_with_license_name()
    {
        $user = UserFactory::new()->create();

        $license = $user->createLicense(Licensing::makePurchase(
            'stripe', '4eC39HqLyjWDarjtT1zdp7dc', Money::USD(3500)
        )->alias('Satifest v2'));

        $this->assertInstanceOf(License::class, $license);

        $this->assertDatabaseHas('sf_licenses', [
            'name' => 'Satifest v2',
            'provider' => 'stripe',
            'uid' => '4eC39HqLyjWDarjtT1zdp7dc',
            'type' => 'purchase',
            'amount' => '3500',
            'currency' => 'USD',
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
        ]);

        $this->assertSame(0, $license->plans()->count());
    }

    /** @test */
    public function it_can_create_license_for_user_with_allocations()
    {
        $user = UserFactory::new()->create();

        $license = $user->createLicense(Licensing::makePurchase(
            'stripe', '4eC39HqLyjWDarjtT1zdp7dc', Money::USD(3500)
        )->collaborators(3));

        $this->assertInstanceOf(License::class, $license);

        $this->assertDatabaseHas('sf_licenses', [
            'provider' => 'stripe',
            'uid' => '4eC39HqLyjWDarjtT1zdp7dc',
            'type' => 'purchase',
            'amount' => '3500',
            'currency' => 'USD',
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
            'allocation' => 3,
            'utilisation' => 0,
        ]);

        $this->assertSame(0, $license->plans()->count());
    }

    /** @test */
    public function it_can_create_license_with_plans_for_user()
    {
        $user = UserFactory::new()->create();
        $repository = RepositoryFactory::new()->create([
            'name' => 'satifest/test-demo-package',
            'url' => 'http://github.com/satifest/test-demo-package',
        ]);
        $plans = PlanFactory::new()->times(2)->create([
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
            'licensable_id' => $user->getKey(),
            'licensable_type' => $user->getMorphClass(),
        ]);

        $this->assertSame(2, $license->plans()->count());
    }
}
