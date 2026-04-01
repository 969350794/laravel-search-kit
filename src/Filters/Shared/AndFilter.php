<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * AND 复合条件过滤
 * 
 * 用于将多个过滤条件用 AND 连接，必须同时满足所有条件
 * 
 * 配置示例:
 * 'age_range_and_status' => [
 *     'filter' => AndFilter::class,
 *     'params' => ['filters'],  // filters 是 QueryFilter 数组
 * ],
 * 
 * 使用示例:
 * // 查询年龄在 18 到 60 岁之间的用户
 * $filter = new AndFilter([
 *     new ComparisonFilter('age', 18, '>'),
 *     new ComparisonFilter('age', 60, '<')
 * ]);
 * // SQL: WHERE (age > 18 AND age < 60)
 * 
 * // 查询状态为 active 且已认证的用户
 * $filter = new AndFilter([
 *     new ComparisonFilter('status', 'active'),
 *     new BooleanFilter('is_verified', true)
 * ]);
 * // SQL: WHERE (status = 'active' AND is_verified = 1)
 */
class AndFilter implements QueryFilter
{
    /**
     * @param array<QueryFilter> $filters
     */
    public function __construct(
        protected array $filters = []
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if (empty($this->filters)) {
            return $query;
        }

        return $query->where(function ($q) {
            foreach ($this->filters as $filter) {
                if ($filter instanceof QueryFilter) {
                    $filter->apply($q);
                }
            }
        });
    }
}
