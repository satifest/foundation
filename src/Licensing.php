<?php

namespace Satifest\Foundation;

use DateTimeImmutable;
use Money\Money;

class Licensing implements Contracts\Licensing
{
    /**
     * Licensing alias name.
     *
     * var string|null
     */
    protected $name;

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
     * Collaborator allocation.
     *
     * @var int
     */
    protected $allocation = 0;

    /**
     * Construct a new Licensing value object.
     */
    public function __construct(
        string $provider,
        string $uid,
        string $type,
        ?Money $price,
        ?DateTimeImmutable $endsAt = null,
        int $allocation = 0
    ) {
        $this->provider = $provider;
        $this->uid = $uid;
        $this->type = $type;
        $this->price = $price ?? Money::USD(0);
        $this->endsAt = $endsAt;
        $this->allocation = $allocation;
    }

    /**
     * Construct a new Licensing using "off-one" payment.
     */
    public static function makePurchase(string $provider, string $uid, Money $price, int $allocation = 0)
    {
        return new static($provider, $uid, 'purchase', $price, null, $allocation);
    }

    /**
     * Construct a new Licensing using "recurring" payment.
     */
    public static function makeRecurring(string $provider, string $uid, Money $price, DateTimeImmutable $endsAt, int $allocation = 0)
    {
        return new static($provider, $uid, 'recurring', $price, $endsAt, $allocation);
    }

    /**
     * Construct a new Licensing using "sponsorware" payment.
     */
    public static function makeSponsorware(string $provider, string $uid, ?Money $price, ?DateTimeImmutable $endsAt = null, int $allocation = 0)
    {
        return new static($provider, $uid, 'sponsorware', $price, $endsAt, $allocation);
    }

    /**
     * Set alias for the license.
     *
     * @return $this
     */
    public function alias(string $name)
    {
        $this->name = $name;

        return $this;
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
     * Set collaborator allocation to licensing.
     *
     * @return $this
     */
    public function collaborators(int $allocation)
    {
        $this->allocation = $allocation;

        return $this;
    }

    /**
     * Get licensing alias name.
     */
    public function name(): ?string
    {
        return $this->name;
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

    /**
     * Get collaborator allocation.
     */
    public function allocation(): int
    {
        return $this->allocation;
    }
}
