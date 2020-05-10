<?php

namespace Satifest\Foundation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;

class ServingSatifest
{
    use Dispatchable;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
