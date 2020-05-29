<?php

namespace Satifest\Foundation;

use DateTimeImmutable;
use Money\Money;

class Licensing implements Contracts\Licensing
{
    /**
     * Licensing provider.
     *
     * @var string
     */
    protected $provider;

    /**
     * Licensing unique identifier.
     *
     * @var string
     */
    protected $uid;

    /**
     * Licensing type (either one-off, sponsorship or recurring).
     *
     * @var string
     */
    protected $type;

    /**
     * Licensing price.
     *
     * @var \Money\Money
     */
    protected $price;

    /**
     * Licensing ends at.
     *
     * @var \DateTimeImmutable
     */
    protected $endsAt;

    /**
     * Construct a new Licensing value object.
     */
    public function __construct(
        string $provider,
        string $uid,
        string $type,
        ?Money $price,
        ?DateTimeImmutable $endsAt = null
    ) {
        $this->provider = $provider;
        $this->uid = $uid;
        $this->type = $type;
        $this->price = $price ?? Money::USD(0);
        $this->endsAt = $endsAt;
    }

    /**
     * Construct a new Licensing using "off-one" payment.
     */
    public static function makePurchase(string $provider, string $uid, Money $price)
    {
        return new static($provider, $uid, 'purchase', $price, null);
    }

    /**
     * Construct a new Licensing using "recurring" payment.
     */
    public static function makeRecurring(string $provider, string $uid, Money $price, DateTimeImmutable $endsAt)
    {
        return new static($provider, $uid, 'recurring', $price, $endsAt);
    }

    /**
     * Construct a new Licensing using "sponsorware" payment.
     */
    public static function makeSponsorware(string $provider, string $uid, ?Money $price, ?DateTimeImmutable $endsAt = null)
    {
        return new static($provider, $uid, 'sponsorware', $price, $endsAt);
    }

    /**
     * Explicitly set supported until (ends at) for the licensing.
     *
     * @return $this
     */
    public function supportedUntil(DateTimeImmutable $endsAt = null)
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    /**
     * Get licensing provider.
     */
    public function provider(): string
    {
        return $this->provider;
    }

    /**
     * Get licensing unique identifier.
     */
    public function uid(): string
    {
        return $this->uid;
    }

    /**
     * Get licensing type.
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get licensing price.
     */
    public function price(): Money
    {
        return $this->price;
    }

    /**
     * Get licensing ends at.
     */
    public function endsAt(): ?DateTimeImmutable
    {
        return $this->endsAt;
    }
}
