<?php

namespace Satifest\Foundation\Value;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Satifest\Foundation\Satifest;
use Spatie\Url\Url;

class RepositoryUrl
{
    /**
     * The URL.
     *
     * @var \Spatie\Url\Url
     */
    protected $url;

    /**
     * Construct a new Package URL value object.
     */
    public function __construct(string $url)
    {
        $url = (string) Str::of($url)
            ->replaceMatches('/^(.*)\.git$/', static function ($matches) {
                return $matches[1];
            })
            ->replaceMatches('/^git@(.*)$/', static function ($matches) {
                return 'https://'.\str_replace(':', '/', $matches[1]);
            });

        $this->url = Url::fromString((string) $url);
    }

    /**
     * Alias construct using make.
     *
     * @return static
     */
    public static function make(string $url)
    {
        return new static($url);
    }

    /**
     * Get package name from URL.
     */
    public function name(): string
    {
        if (! $this->isValid()) {
            throw new InvalidArgumentException('Unable to find package name from invalid VCS URL');
        }

        return \implode('/', $this->url->getSegments());
    }

    /**
     * Get the domain for package.
     */
    public function domain(): string
    {
        return $this->url->getHost();
    }

    /**
     * Validate URL.
     */
    public function isValid(): bool
    {
        return $this->isSupportedDomain()
            && count($this->url->getSegments()) === 2
            && $this->url->getScheme('https');
    }

    /**
     * Validate supported domain.
     */
    public function isSupportedDomain(): bool
    {
        return \in_array($this->url->getHost(), Satifest::getSupportedHosts());
    }

    /**
     * Render as string.
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->url;
    }
}
