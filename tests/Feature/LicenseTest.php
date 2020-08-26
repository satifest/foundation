<?php

namespace Satifest\Foundation\Tests\Feature;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Money\Money;
use Satifest\Foundation\License;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\License feature tests
 */
class LicenseTest extends TestCase
{
    /** @test */
    public function it_can_cast_price_to_money()
    {
        $license = \factory(License::class)->make();

        $price = $license->price;

        $this->assertInstanceOf(Money::class, $price);
        $this->assertSame('2500', $price->getAmount());
        $this->assertSame('USD', (string) $price->getCurrency());
    }

    /** @test */
    public function it_can_cast_ends_at_to_carbon()
    {
        $license = \factory(License::class)->make([
            'ends_at' => $now = Carbon::now(),
        ]);

        $endsAt = $license->ends_at;

        $this->assertInstanceOf(CarbonInterface::class, $endsAt);
        $this->assertSame($now->toDateTimeString(), $endsAt->toDateTimeString());
    }

    /** @test */
    public function it_belongs_to_many_plans_relation()
    {
        $license = \factory(License::class)->make();

        $plans = $license->plans();

        $this->assertInstanceOf(BelongsToMany::class, $plans);
        $this->assertSame('license_id', $plans->getForeignPivotKeyName());
        $this->assertSame('sf_license_plan.license_id', $plans->getQualifiedForeignPivotKeyName());
        $this->assertSame('plan_id', $plans->getRelatedPivotKeyName());
        $this->assertSame('sf_license_plan.plan_id', $plans->getQualifiedRelatedPivotKeyName());
        $this->assertSame('id', $plans->getParentKeyName());
        $this->assertSame('sf_licenses.id', $plans->getQualifiedParentKeyName());
        $this->assertSame('id', $plans->getRelatedKeyName());
        $this->assertSame('sf_license_plan', $plans->getTable());
        $this->assertSame('plans', $plans->getRelationName());
        $this->assertSame('pivot', $plans->getPivotAccessor());
        $this->assertSame([
            'created_at',
            'updated_at',
        ], $plans->getPivotColumns());
    }
}
