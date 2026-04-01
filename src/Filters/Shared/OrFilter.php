<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * OR 复合条件过滤
 * 
 * 用于将多个过滤条件用 OR 连接，满足任一条件即可
 * 
 * 配置示例:
 * 'status_or_type' => [
 *     'filter' => OrFilter::class,
 *     'params' => ['filters'],  // filters 是 QueryFilter 数组
 * ],
 * 
 * 使用示例:
 * // 查询状态为 active 或 pending 的记录
 * $filter = new OrFilter([
 *     new ComparisonFilter('status', 'active'),
 *     new ComparisonFilter('status', 'pending')
 * ]);
 * // SQL: WHERE (status = 'active' OR status = 'pending')
 * 
 * // 查询价格低于 100 或销量大于 1000 的商品
 * $filter = new OrFilter([
 *     new ComparisonFilter('price', 100, '<'),
 *     new ComparisonFilter('sales', 1000, '>')
 * ]);
 * // SQL: WHERE (price < 100 OR sales > 1000)
 */
class OrFilter implements QueryFilter
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
