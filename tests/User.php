<?php

namespace Satifest\Foundation\Tests;

use Satifest\Foundation\Concerns\HasPurchases;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasPurchases;
}
