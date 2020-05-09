<?php

namespace Satifest\Foundation\Tests;

use Satifest\Foundation\Concerns\Licensable;

class User extends \Illuminate\Foundation\Auth\User
{
    use Licensable;
}
