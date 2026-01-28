<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * NOT IN 过滤 (not in)
 * 
 * 配置示例:
 * 'status' => [
 *     'filter' => NotInFilter::class,
 *     'column' => 'status',
 *     'params' => ['column', 'value'],  // value 应该是数组
 * ],
 */
class NotInFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected mixed $value
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null) {
            return $query;
        }

        // 确保 value 是数组
        $values = is_array($this->value) ? $this->value : [$this->value];
        
        // 过滤空值
        $values = array_filter($values, fn($v) => $v !== null && $v !== '');
        
        if (empty($values)) {
            return $query;
        }

        return $query->whereNotIn($this->column, $values);
    }
}
