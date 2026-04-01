<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * IN/NOT IN 过滤
 * 
 * 用于查询字段值是否在指定的数组中，支持 IN 和 NOT IN 两种模式
 * 
 * 配置示例:
 * 'status' => [
 *     'filter' => InFilter::class,
 *     'column' => 'status',
 *     'params' => ['column', 'value'],  // value 应该是数组
 * ],
 * 
 * 支持两种模式:
 * - in: IN 查询 (默认)
 * - not_in: NOT IN 查询
 * 
 * 使用示例:
 * // 查询状态为 active 或 pending 的记录
 * $filter = new InFilter('status', ['active', 'pending']);
 * // SQL: WHERE status IN ('active', 'pending')
 * 
 * // 查询状态不是 deleted 或 archived 的记录
 * $filter = new InFilter('status', ['deleted', 'archived'], InFilter::MODE_NOT_IN);
 * // SQL: WHERE status NOT IN ('deleted', 'archived')
 */
class InFilter implements QueryFilter
{
    public const MODE_IN = 'in';
    public const MODE_NOT_IN = 'not_in';

    public function __construct(
        protected ?string $column,
        protected mixed $value,
        protected string $mode = self::MODE_IN
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

        // 根据模式选择 IN 或 NOT IN
        return match($this->mode) {
            self::MODE_NOT_IN => $query->whereNotIn($this->column, $values),
            default => $query->whereIn($this->column, $values),
        };
    }
}
