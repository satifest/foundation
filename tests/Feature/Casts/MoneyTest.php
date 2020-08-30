<?php

namespace Satifest\Foundation\Tests\Feature\Casts;

use Money\Money;
use Satifest\Foundation\License;
use Satifest\Foundation\Testing\Factories\LicenseFactory;
use Satifest\Foundation\Tests\TestCase;

/**
 * @testdox Satifest\Foundation\Casts\Money feature tests
 */
class MoneyTest extends TestCase
{
    /** @test */
    public function it_cast_to_money()
    {
        $license = LicenseFactory::new()->make([
            'amount' => 3500,
            'currency' => 'MYR',
        ]);

        $money = $license->price;

        $this->assertInstanceOf(Money::class, $money);
        $this->assertSame(3500, (int) $money->getAmount());
        $this->assertSame('MYR', (string) $money->getCurrency());
    }

    /** @test */
    public function it_cast_from_money()
    {
        $license = LicenseFactory::new()->make([
            'amount' => 3500,
            'currency' => 'MYR',
        ]);

        $license->price = Money::USD(4000);

        $this->assertSame(4000, $license->amount);
        $this->assertSame('USD', $license->currency);
    }
}
