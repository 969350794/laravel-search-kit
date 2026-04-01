<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * IS NULL / IS NOT NULL 过滤
 * 
 * 用于查询字段是否为 NULL 值，常用于软删除、可选字段等场景
 * 
 * 配置示例:
 * 'deleted_at' => [
 *     'filter' => NullFilter::class,
 *     'column' => 'deleted_at',
 * ],
 * 
 * 支持两种模式:
 * - null: IS NULL 查询 (默认)
 * - not_null: IS NOT NULL 查询
 * 
 * 使用示例:
 * // 查询未删除的记录 (deleted_at IS NULL)
 * $filter = new NullFilter('deleted_at');
 * // SQL: WHERE deleted_at IS NULL
 * 
 * // 查询已删除的记录 (deleted_at IS NOT NULL)
 * $filter = new NullFilter('deleted_at', NullFilter::MODE_NOT_NULL);
 * // SQL: WHERE deleted_at IS NOT NULL
 * 
 * // 查询没有邮箱的用户 (email IS NULL)
 * $filter = new NullFilter('email');
 * // SQL: WHERE email IS NULL
 */
class NullFilter implements QueryFilter
{
    public const MODE_NULL = 'null';
    public const MODE_NOT_NULL = 'not_null';

    public function __construct(
        protected ?string $column,
        protected string $mode = self::MODE_NULL
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null) {
            return $query;
        }

        return match($this->mode) {
            self::MODE_NOT_NULL => $query->whereNotNull($this->column),
            default => $query->whereNull($this->column),
        };
    }
}
