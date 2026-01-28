<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * BETWEEN 过滤 (between start and end)
 *
 * 配置示例:
 * 'price' => [
 *     'filter' => BetweenFilter::class,
 *     'column' => 'price',
 *     'params' => ['column', 'key:price_min', 'key:price_max'],
 * ],
 */
class BetweenFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected mixed   $start,
        protected mixed   $end
    )
    {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null) {
            return $query;
        }

        // 两个值都有才应用
        if ($this->start !== null && $this->start !== '' && $this->end !== null && $this->end !== '') {
            return $query->whereBetween($this->column, [$this->start, $this->end]);
        }

        // 只有 start，使用 >=
        if ($this->start !== null && $this->start !== '') {
            return $query->where($this->column, '>=', $this->start);
        }

        // 只有 end，使用 <=
        if ($this->end !== null && $this->end !== '') {
            return $query->where($this->column, '<=', $this->end);
        }

        return $query;
    }
}
