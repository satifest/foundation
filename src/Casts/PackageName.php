<?php

namespace Satifest\Foundation\Casts;

use Satifest\Foundation\Value\PackageUrl;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;

class PackageName implements CastsInboundAttributes
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
