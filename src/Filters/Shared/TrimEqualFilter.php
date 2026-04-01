<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * TRIM 空格修剪后比较过滤
 * 
 * 用于在比较前自动去除字段两端的空格，避免因空格导致的匹配失败
 * 
 * 配置示例:
 * 'name' => [
 *     'filter' => TrimEqualFilter::class,
 *     'column' => 'name',
 *     'params' => ['column', 'value'],
 * ],
 * 
 * 使用示例:
 * // 查询名字为 "John" (自动去除两端空格)
 * $filter = new TrimEqualFilter('name', 'John');
 * // SQL: WHERE TRIM(name) = 'John'
 * 
 * // 查询手机号 (忽略存储时的空格)
 * $filter = new TrimEqualFilter('phone', '13800138000');
 * // SQL: WHERE TRIM(phone) = '13800138000'
 */
class TrimEqualFilter implements QueryFilter
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
            "TRIM({$this->column}) = ?",
            [$this->value]
        );
    }
}
