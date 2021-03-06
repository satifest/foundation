<?php

namespace Satifest\Foundation\Actions;

use Illuminate\Database\Eloquent\Collection;
use Satifest\Foundation\Contracts\Licensing;
use Satifest\Foundation\License;
use Satifest\Foundation\Plan;
use Satifest\Foundation\Satifest;

class CreateLicense
{
    /**
     * The licensable ID.
     *
     * @var int|string
     */
    protected $licensableId;

    /**
     * THe licensable type.
     *
     * @var string
     */
    protected $licensableType;

    /**
     * Construct a new Create License object.
     *
     * @param  string|int  $licensableId
     * @param  string  $licensableType
     */
    public function __construct($licensableId, string $licensableType)
    {
        $this->licensableId = $licensableId;
        $this->licensableType = $licensableType;
    }

    /**
     * Handle creating license.
     *
     * @param  \Illuminate\Support\Collection|array|string  $plans
     */
    public function __invoke(Licensing $licensing, $plans = []): License
    {
        $license = License::forceCreate([
            'licensable_id' => $this->licensableId,
            'licensable_type' => $this->licensableType,
            'name' => $licensing->name(),
            'provider' => $licensing->provider(),
            'uid' => $licensing->uid(),
            'type' => $licensing->type(),
            'amount' => (int) $licensing->price()->getAmount(),
            'currency' => (string) $licensing->price()->getCurrency(),
            'ends_at' => $licensing->endsAt(),
            'allocation' => ! Satifest::$allowsCollaborations ? 0 : $licensing->allocation(),
        ]);

        if ($plans === '*') {
            $plans = Plan::pluck('id')->all();
        }

        if (($plans instanceof Collection && $plans->isNotEmpty())
            || (\is_array($plans) && ! empty($plans))
        ) {
            $license->plans()->sync($plans);
        }

        return $license;
    }
}
