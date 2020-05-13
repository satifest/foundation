<?php

namespace Satifest\Foundation\Rules;

use Illuminate\Contracts\Validation\Rule;

class RepositoryName implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \preg_match('/^([\w\-\_])+\/([\w\-\_])+$/i', $value) == 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be a valid repository name.';
    }
}
