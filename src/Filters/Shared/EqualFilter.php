<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 等于过滤 (=)
 * 
 * 配置示例:
 * 'status' => [
 *     'filter' => EqualFilter::class,
 *     'column' => 'status',
 *     'params' => ['column', 'value'],
 * ],
 */
class EqualFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected mixed $value
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null || $this->value === '') {
            return $query;
        }

        return $query->where($this->column, '=', $this->value);
    }
}
