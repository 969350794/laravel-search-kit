<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * BINARY 二进制比较过滤（区分大小写）
 * 
 * 用于进行区分大小写的精确匹配，使用 MySQL 的 BINARY 操作符
 * 
 * 配置示例:
 * 'code' => [
 *     'filter' => BinaryEqualFilter::class,
 *     'column' => 'code',
 *     'params' => ['column', 'value'],
 * ],
 * 
 * 使用示例:
 * // 查询代码为 "ABC" (区分大小写)
 * $filter = new BinaryEqualFilter('code', 'ABC');
 * // SQL: WHERE BINARY code = 'ABC'
 * 
 * // 查询密码令牌 (区分大小写)
 * $filter = new BinaryEqualFilter('token', 'AbC123XyZ');
 * // SQL: WHERE BINARY token = 'AbC123XyZ'
 */
class BinaryEqualFilter implements QueryFilter
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

        return $query->whereRaw(
            "BINARY {$this->column} = ?",
            [$this->value]
        );
    }
}
