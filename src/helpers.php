<?php

namespace Satifest\Foundation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * Get table name from Eloquent model.
 *
 * @param  string|\Illuminate\Database\Eloquent\Model  $model
 *
 * @throws \InvalidArgumentException
 */
function table_name($model): string
{
    if (\is_string($model)) {
        $model = new $model();
    }

    if (! $model instanceof Model) {
        throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
    }

    return $model->getTable();
}

/**
 * Get qualify column name from Eloquent model.
 *
 * @param  string|\Illuminate\Database\Eloquent\Model  $model
 *
 * @throws \InvalidArgumentException
 */
function column_name($model, string $attribute): string
{
    if (\is_string($model)) {
        $model = new $model();
    }

    if (! $model instanceof Model) {
        throw new InvalidArgumentException(\sprintf('Given $model is not an instance of [%s].', Model::class));
    }

    return $model->qualifyColumn($attribute);
}

/**
 * Get release stability from SemVer format.
 */
function release_stability(string $semver): string
{
    return Str::endsWith($semver, '-dev') || Str::startsWith($semver, 'dev-')
        ? Release::NIGHTLY
        : Release::STABLE;
}
