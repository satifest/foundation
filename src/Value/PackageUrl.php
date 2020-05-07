<?php

namespace Satifest\Foundation\Value;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Url\Url;

class PackageUrl
{
    /**
     * The URL.
     *
     * @var \Spatie\Url\Url
     */
    protected $url;

    /**
     * List of supported VCS hosts.
     *
     * @var array
     */
    protected $supportedHosts = [
        'github.com',
    ];

    /**
     * Construct a new Package URL value object.
     */
    public function __construct(string $url)
    {
        $this->url = Url::fromString($url);
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
    public function packageName(): string
    {
        if (! $this->isValid()) {
            throw new InvalidArgumentException('Unable to find package name from invalid VCS URL');
        }

        return Str::of($this->url->getPath())
            ->trim('/')
            ->replaceMatches('/^(.*)\.git$/', static function ($match) {
                return $match[1];
            });
    }

    /**
     * Validate URL.
     */
    public function isValid(): bool
    {
        return \in_array($this->url->getHost(), $this->supportedHosts)
            && $this->url->getScheme('https');
    }

    /**
     * Get the domain for package.
     */
    public function domain(): string
    {
        return $this->url->getHost();
    }

    /**
     * Render as string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->url->__toString();
    }
}
