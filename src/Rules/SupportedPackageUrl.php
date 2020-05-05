<?php

namespace Satifest\Foundation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Satifest\Foundation\Value\PackageUrl;

class SupportedPackageUrl implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return PackageUrl::make($value)->isValid();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute VCS is not supported or well-formatted.';
    }
}
