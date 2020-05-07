<?php

namespace Satifest\Foundation\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Satifest\Foundation\Value\PackageUrl;

class Package implements CastsInboundAttributes
{
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
        $url = PackageUrl::make($value);

        return [
            'name' => $url->packageName(),
            $key => $value,
        ];
    }
}
