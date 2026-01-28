<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * LIKE 开头过滤 (like value%)
 * 
 * 配置示例:
 * 'name' => [
 *     'filter' => LikeStartFilter::class,
 *     'column' => 'name',
 *     'params' => ['column', 'value'],
 * ],
 */
class LikeStartFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected ?string $value
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null || $this->value === '') {
            return $query;
        }

        return $query->where($this->column, 'like', $this->value . '%');
    }
}
