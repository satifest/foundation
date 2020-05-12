<?php

namespace Satifest\Foundation\Contracts;

use DateTimeImmutable;
use Money\Money;

interface Licensing
{

    /**
     * Get licensing provider.
     */
    public function provider(): string;

    /**
     * Get licensing unique identifier.
     */
    public function uid(): string;

    /**
     * Get licensing type.
     */
    public function type(): string;

    /**
     * Get licensing price.
     */
    public function price(): Money;

    /**
     * Get licensing ends at.
     */
    public function endsAt(): ?DateTimeImmutable;
}
