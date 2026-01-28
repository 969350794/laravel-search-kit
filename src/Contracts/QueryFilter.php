<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface QueryFilter
{
    public function apply(Builder $query): Builder;
}
