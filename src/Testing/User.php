<?php

namespace Satifest\Foundation\Testing;

use Satifest\Foundation\Concerns\Collaborable;
use Satifest\Foundation\Concerns\Licensable;

class User extends \Illuminate\Foundation\Auth\User
{
    use Collaborable,
        Licensable;
}
