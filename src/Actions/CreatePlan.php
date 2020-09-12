<?php

namespace Satifest\Foundation\Actions;

use Illuminate\Support\Str;
use ReleaseName\Release;
use Satifest\Foundation\Plan;
use Satifest\Foundation\Repository;

class CreatePlan
{
    /**
     * The repository.
     *
     * @var \Satifest\Foundation\Repository
     */
    protected $repository;

    /**
     * Construct a new Create Plan object.
     *
     * @param \Satifest\Foundation\Repository  $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle creating plan.
     *
     * @param  string  $name
     * @param  string  $constraints
     */
    public function __invoke(?string $name = null, string $constraint = '*'): Plan
    {
        return Plan::forceCreate([
            'repository_id' => $this->repository->getKey(),
            'name' => $name ?? Str::title(\str_replace('-', ' ', Release::random())),
            'constraint' => $constraint,
        ]);
    }
}
