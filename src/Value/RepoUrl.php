<?php

namespace Satifest\Foundation\Value;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Spatie\Url\Url;

class RepoUrl
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
        $url = (string) Str::of($url)
            ->replaceMatches('/^(.*)\.git$/', static function ($match) {
                return $match[1];
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

        return \trim($this->url->getPath(), '/');
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
        return \in_array($this->url->getHost(), $this->supportedHosts)
            && $this->url->getScheme('https');
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
