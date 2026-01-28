<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 大于过滤 (>)
 * 
 * 配置示例:
 * 'price' => [
 *     'filter' => GreaterThanFilter::class,
 *     'column' => 'price',
 *     'params' => ['column', 'value'],
 * ],
 */
class GreaterThanFilter implements QueryFilter
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

        return $query->where($this->column, '>', $this->value);
    }
}
