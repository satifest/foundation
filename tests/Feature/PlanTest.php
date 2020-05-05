<?php

namespace Satifest\Foundation\Tests\Feature;

use Money\Money;
use Satifest\Foundation\Plan;
use Satifest\Foundation\Repository;
use Satifest\Foundation\Tests\TestCase;

class PlanTest extends TestCase
{
    /** @test */
    public function it_can_cast_to_money()
    {
        $repository = \factory(Repository::class)->create([
            'name' => 'satifest/demo-test-package',
            'url' => 'https://github.com/satifest/demo-test-package',
        ]);

        $plan = \factory(Plan::class)->make([
            'repository_id' => $repository->getKey(),
        ]);

        $plan->amount = Money::MYR(3000);

        $plan->save();

        $this->assertDatabaseHas('plans', [
            'id' => $plan->getKey(),
            'amount' => 3000,
            'currency' => 'MYR',
        ]);
    }
}
