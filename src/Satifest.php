<?php

namespace Satifest\Foundation;

use InvalidArgumentException;

class Satifest
{
    /**
     * Purchaser model (normally refer to the user).
     *
     * @var string
     */
    protected static $purchaserModel;

    /**
     * Set purchaser model.
     */
    public static function setPurchaserModel(string $purchaserModel): void
    {
        $uses = \class_uses_recursive($purchaserModel);

        if (! isset($uses[Concerns\HasPurchases::class])) {
            throw new InvalidArgumentException("Given [$purchaserModel] does not implements '".Concerns\HasPurchases::class."' trait.");
        }

        static::$purchaserModel = $purchaserModel;
    }

    /**
     * Get the purchaser model.
     */
    public static function getPurchaserModel(): string
    {
        if (! isset(static::$purchaserModel)) {
            $provider = \config('auth.guards.web.provider');

            static::setPurchaserModel(\config("auth.providers.{$provider}.model"));
        }

        return static::$purchaserModel;
    }

}
