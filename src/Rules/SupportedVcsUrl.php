<?php

namespace Satifest\Foundation\Rules;

use Spatie\Url\Url;
use Illuminate\Contracts\Validation\Rule;

class SupportedVcsUrl implements Rule
{
    /**
     * List of supported VCS hosts.
     *
     * @var array
     */
    protected $supportedVcsHosts = [
        'github.com',
    ];

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
        $url = Url::fromString($value);

        return \in_array($url->getHost(), $this->supportedVcsHosts)
            && $url->getScheme('https');
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
