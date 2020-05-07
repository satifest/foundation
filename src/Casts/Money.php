<?php

namespace Satifest\Foundation\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Money\Currency;
use Money\Money as MoneyObject;

class Money implements CastsAttributes
{
    /**
     * The amount column.
     *
     * @var string
     */
    protected $amount;

    /**
     * The currency column.
     *
     * @var string
     */
    protected $currency;

    /**
     * Create a new cast instance.
     */
    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     *
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return new MoneyObject(
            $attributes[$this->amount],
            new Currency($attributes[$this->currency])
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     *
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return [
            $this->amount => (int) $value->getAmount(),
            $this->currency => (string) $value->getCurrency(),
        ];
    }
}
