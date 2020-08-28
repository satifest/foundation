<?php

namespace Satifest\Foundation\Value;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Routing\RouteRegistrar as LaravelRouteRegistrar;
use Illuminate\Support\Facades\Route;
use Satifest\Foundation\Http\RouteRegistrar;
use Spatie\Url\Url;

class Routing implements Arrayable
{
    /**
     * The URL.
     *
     * @var \Spatie\Url\Url
     */
    protected $url;

    /**
     * The routing prefix.
     *
     * @var string
     */
    protected $prefix = '/';

    /**
     * The routing domain.
     *
     * @var string|null
     */
    protected $domain = null;

    /**
     * Ignored hosts.
     *
     * @var array
     */
    protected $ignoredHosts = ['', 'localhost', 'http://localhost', 'https://localhost'];

    /**
     * Construct a new URL Routing.
     */
    public function __construct(Url $url)
    {
        $this->url = $url;

        $this->prefix = $this->parsePrefixFrom($url);
        $this->domain = $this->parseDomainFrom($url);
    }

    /**
     * Make a new URL Routing.
     *
     * @return static
     */
    public static function make(string $url = '/')
    {
        return new static(Url::fromString($url));
    }

    /**
     * Get domain value.
     */
    public function domain(): ?string
    {
        return $this->domain;
    }

    /**
     * Get prefix value.
     */
    public function prefix(): string
    {
        return $this->prefix;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'domain' => $this->domain,
            'prefix' => $this->prefix,
        ];
    }

    /**
     * Invoke building routing.
     */
    public function __invoke(?string $namespace, ?string $prefix): RouteRegistrar
    {
        $currentPrefix = $this->prefix();
        $routePrefixes = \array_filter([
            ($currentPrefix !== '/' ? $currentPrefix : null), \trim($prefix, '/')
        ]);

        return \tap(new RouteRegistrar(\app('router')), function ($router) use ($namespace, $routePrefixes) {
            $router->prefix(\implode('/', $routePrefixes));

            if (! empty($namespace)) {
                $router->namespace($namespace);
            }

            if (! \is_null($domain = $this->domain())) {
                $router->domain($domain);
            }
        });
    }

    /**
     * Parse domain from URL.
     */
    protected function parseDomainFrom(Url $url): ?string
    {
        return \transform($url->getHost(), function ($host) {
            $appUrl = Url::fromString(\config('app.url') ?? '/');

            if ($appUrl->getHost() === $host) {
                return null;
            }

            return ! \in_array($host, $this->ignoredHosts) ? $host : null;
        });
    }

    /**
     * Parse prefix from URL.
     */
    protected function parsePrefixFrom(Url $url): string
    {
        return \transform($url->getPath(), static function ($prefix) {
            if ($prefix === '/') {
                return $prefix;
            }

            return \trim($prefix, '/');
        });
    }
}
