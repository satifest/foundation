<?php

namespace Satifest\Foundation\Tests\Unit;

use Carbon\CarbonImmutable;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Satifest\Foundation\Contracts\Licensing as LicensingContract;
use Satifest\Foundation\Licensing;

/**
 * @testdox Satifest\Foundation\Licensing unit tests
 */
class LicensingTest extends TestCase
{
    /** @test */
    public function it_has_proper_signature()
    {
        $now = CarbonImmutable::now();
        $endsAt = $now->addYear(1);

        $licensing = new Licensing(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            'purchase',
            Money::USD(2500),
            $endsAt
        );

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertInstanceOf(\DatetimeImmutable::class, $licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('purchase', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
        $this->assertSame($endsAt->toDatetimeString(), (string) $licensing->endsAt()->toDatetimeString());
    }

    /** @test */
    public function it_can_be_constructed_without_ends_at()
    {
        $licensing = new Licensing(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            'purchase',
            Money::USD(2500)
        );

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertNull($licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('purchase', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
    }

    /** @test */
    public function it_can_be_constructed_without_ends_at_and_use_supported_at_to_add_ends_at()
    {
        $now = CarbonImmutable::now();
        $endsAt = $now->addYear(1);

        $licensing = new Licensing(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            'purchase',
            Money::USD(2500)
        );

        $licensing->supportedUntil($endsAt);

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertInstanceOf(\DatetimeImmutable::class, $licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('purchase', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
        $this->assertSame($endsAt->toDatetimeString(), (string) $licensing->endsAt()->toDatetimeString());
    }

    /** @test */
    public function it_can_use_make_purchase_helper()
    {
        $licensing = Licensing::makePurchase(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            Money::USD(2500)
        );

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertNull($licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('purchase', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
    }

    /** @test */
    public function it_can_use_make_recurring_helper()
    {
        $now = CarbonImmutable::now();
        $endsAt = $now->addYear(1);

        $licensing = Licensing::makeRecurring(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            Money::USD(2500),
            $endsAt
        );

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertInstanceOf(\DatetimeImmutable::class, $licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('recurring', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
        $this->assertSame($endsAt->toDatetimeString(), (string) $licensing->endsAt()->toDatetimeString());
    }

    /** @test */
    public function it_can_use_make_sponsorware_helper()
    {
        $licensing = Licensing::makeSponsorware(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            Money::USD(2500)
        );

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertNull($licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('sponsorware', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
    }

    /** @test */
    public function it_can_use_make_sponsorware_helper_with_ends_at()
    {
        $now = CarbonImmutable::now();
        $endsAt = $now->addYear(1);

        $licensing = Licensing::makeSponsorware(
            'stripe',
            '4eC39HqLyjWDarjtT1zdp7dc',
            Money::USD(2500),
            $endsAt
        );

        $this->assertInstanceOf(LicensingContract::class, $licensing);
        $this->assertInstanceOf(Money::class, $licensing->price());
        $this->assertInstanceOf(\DatetimeImmutable::class, $licensing->endsAt());

        $this->assertSame('stripe', $licensing->provider());
        $this->assertSame('4eC39HqLyjWDarjtT1zdp7dc', $licensing->uid());
        $this->assertSame('sponsorware', $licensing->type());
        $this->assertSame('2500', $licensing->price()->getAmount());
        $this->assertSame('USD', (string) $licensing->price()->getCurrency());
        $this->assertSame($endsAt->toDatetimeString(), (string) $licensing->endsAt()->toDatetimeString());
    }
}
