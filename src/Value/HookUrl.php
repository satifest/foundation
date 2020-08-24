<?php

namespace Satifest\Foundation\Value;

class HookUrl
{
    /**
     * Repository URL.
     *
     * @var string
     */
    protected $url;

    /**
     * Hook Provider.
     *
     * @var string
     */
    protected $provider;

    /**
     * Construct a new HookUrl.
     */
    public function __construct(string $url, string $provider)
    {
        $this->url = $url;
        $this->provider = $provider;
    }

    /**
     * Render to string.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->provider === 'github') {
            return "{$this->url}/settings/hooks/new";
        } elseif ($this->provider === 'gitlab') {
            return "{$this->url}/hooks";
        }

        return $this->url;
    }
}
