<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Pivot;

class Team extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sf_teams';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}
