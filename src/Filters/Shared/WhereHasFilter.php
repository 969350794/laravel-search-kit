<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

/**
 * whereHas 关系存在性过滤
 * 
 * 用于查询具有特定关联记录的模型，是 Laravel Eloquent 的 whereHas 方法的封装
 * 
 * 配置示例:
 * 'has_published_posts' => [
 *     'filter' => WhereHasFilter::class,
 *     'params' => ['relation', 'callback'],
 * ],
 * 
 * 使用示例:
 * // 查询有文章的用户
 * $filter = new WhereHasFilter('posts');
 * // SQL: WHERE exists (select * from posts where posts.user_id = users.id)
 * 
 * // 查询有已发布文章的用户
 * $filter = new WhereHasFilter('posts', function($query) {
 *     $query->where('status', 'published');
 * });
 * // SQL: WHERE exists (select * from posts where posts.user_id = users.id and status = 'published')
 * 
 * // 查询订单金额大于 1000 的用户
 * $filter = new WhereHasFilter('orders', function($query) {
 *     $query->where('amount', '>', 1000);
 * });
 */
class WhereHasFilter implements QueryFilter
{
    public function __construct(
        protected ?string $relation,
        protected ?Closure $callback = null
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->relation === null) {
            return $query;
        }

        return $query->whereHas($this->relation, $this->callback);
    }
}
