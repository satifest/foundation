<?php

namespace Satifest\Foundation\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Satifest\Foundation\Value\RepoUrl as Value;

class RepositoryUrl implements CastsInboundAttributes
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
        $url = Value::make($value);

        return [
            'name' => $url->name(),
            $key => $value,
        ];
    }
}
