<?php

namespace Satifest\Foundation\Observers;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the user "creating" event.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function creating(UserContract $user)
    {
        $column = Satifest::getAuthTokenName();

        if (\method_exists($user, 'getSatifestAuthToken')) {
            $user->setAttribute($column, Str::random(60));
        }
    }

    /**
     * Handle the user "updating" event.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function updating(UserContract $user)
    {
        $column = Satifest::getAuthTokenName();

        if (\method_exists($user, 'getSatifestAuthToken') && empty($user->getSatifestAuthToken())) {
            $user->setAttribute($column, Str::random(60));
        }
    }
}
