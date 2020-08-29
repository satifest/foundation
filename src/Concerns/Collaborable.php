<?php

namespace Satifest\Foundation\Concerns;

use Satifest\Foundation\License;
use Satifest\Foundation\Team;

trait Collaborable
{
    /**
     * User has many collaboration teams.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collaborationTeams()
    {
        return $this->belongsToMany(License::class, 'sf_teams', 'user_id', 'license_id')
            ->using(Team::class)
            ->withPivot('email', 'accepted_at')
            ->withTimestamps();
    }
}
