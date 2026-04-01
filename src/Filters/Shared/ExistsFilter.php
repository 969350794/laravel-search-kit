<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * EXISTS 子查询过滤
 * 
 * 用于检查子查询是否返回结果，常用于判断关联记录是否存在
 * 
 * 配置示例:
 * 'has_orders' => [
 *     'filter' => ExistsFilter::class,
 *     'params' => ['callback'],
 * ],
 * 
 * 使用示例:
 * // 查询有订单的用户
 * $filter = new ExistsFilter(function($query) {
 *     $query->from('orders')
 *           ->whereColumn('orders.user_id', 'users.id');
 * });
 * // SQL: WHERE EXISTS (SELECT * FROM orders WHERE orders.user_id = users.id)
 * 
 * // 查询有已支付订单的用户
 * $filter = new ExistsFilter(function($query) {
 *     $query->from('orders')
 *           ->whereColumn('orders.user_id', 'users.id')
 *           ->where('status', 'paid');
 * });
 * // SQL: WHERE EXISTS (SELECT * FROM orders WHERE orders.user_id = users.id AND status = 'paid')
 */
class ExistsFilter implements QueryFilter
{
    public function __construct(
        protected ?Closure $callback = null
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->callback === null) {
            return $query;
        }

        return $query->whereExists($this->callback);
    }
}
