<?php

namespace Satifest\Foundation\Casts;

use Spatie\Url\Url;
use Illuminate\Support\Str;
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
        $url = Url::fromString($value);

        return [
            'name' => (string) Str::of($url->getPath())->trim('/')->replaceMatches('/^(.*)\.git$/', static function ($match) {
                return $match[1];
            }),
            $key => $value,
        ];
    }
}
