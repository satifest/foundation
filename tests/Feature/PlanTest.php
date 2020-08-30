<?php

namespace Satifest\Foundation\Tests\Feature;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Satifest\Foundation\Testing\Factories\PlanFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Plan feature tests
 */
class PlanTest extends TestCase
{
    /** @test */
    public function it_belongs_to_many_licenses_relation()
    {
        $plan = PlanFactory::new()->make();

        $licenses = $plan->licenses();

        $this->assertInstanceOf(BelongsToMany::class, $licenses);
        $this->assertSame('plan_id', $licenses->getForeignPivotKeyName());
        $this->assertSame('sf_license_plan.plan_id', $licenses->getQualifiedForeignPivotKeyName());
        $this->assertSame('license_id', $licenses->getRelatedPivotKeyName());
        $this->assertSame('sf_license_plan.license_id', $licenses->getQualifiedRelatedPivotKeyName());
        $this->assertSame('id', $licenses->getParentKeyName());
        $this->assertSame('sf_plans.id', $licenses->getQualifiedParentKeyName());
        $this->assertSame('id', $licenses->getRelatedKeyName());
        $this->assertSame('sf_license_plan', $licenses->getTable());
        $this->assertSame('licenses', $licenses->getRelationName());
        $this->assertSame('pivot', $licenses->getPivotAccessor());
        $this->assertSame([
            'created_at',
            'updated_at',
        ], $licenses->getPivotColumns());
    }

    /** @test */
    public function it_belongs_to_user_relation()
    {
        $plan = PlanFactory::new()->make();

        $repository = $plan->repository();

        $this->assertInstanceOf(BelongsTo::class, $repository);
        $this->assertSame('repository_id', $repository->getForeignKeyName());
        $this->assertSame('sf_plans.repository_id', $repository->getQualifiedForeignKeyName());
        $this->assertSame('id', $repository->getOwnerKeyName());
        $this->assertSame('sf_repositories.id', $repository->getQualifiedOwnerKeyName());
        $this->assertSame('repository', $repository->getRelationName());
    }
}
