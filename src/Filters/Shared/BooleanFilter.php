<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 布尔值过滤
 * 
 * 用于查询布尔类型的字段，自动将 true/false 转换为数据库的 1/0
 * 
 * 配置示例:
 * 'is_active' => [
 *     'filter' => BooleanFilter::class,
 *     'column' => 'is_active',
 *     'params' => ['column', 'value'],
 * ],
 * 
 * 使用示例:
 * // 查询激活的用户 (is_active = 1)
 * $filter = new BooleanFilter('is_active', true);
 * // SQL: WHERE is_active = 1
 * 
 * // 查询未激活的用户 (is_active = 0)
 * $filter = new BooleanFilter('is_active', false);
 * // SQL: WHERE is_active = 0
 * 
 * // 查询已认证的文章 (is_verified = 1)
 * $filter = new BooleanFilter('is_verified', true);
 * // SQL: WHERE is_verified = 1
 */
class BooleanFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected ?bool $value
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null) {
            return $query;
        }

        return $query->where($this->column, '=', $this->value ? 1 : 0);
    }
}
