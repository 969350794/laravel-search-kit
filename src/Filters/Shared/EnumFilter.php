<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Enum 过滤
 * 
 * 配置示例:
 * 'status' => [
 *     'filter' => EnumFilter::class,
 *     'column' => 'status',
 *     'enum' => CompanyStatus::class,
 *     'params' => ['column', 'enum', 'value'],
 * ],
 */
class EnumFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected string $enumClass,
        protected mixed $value
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null || $this->value === '') {
            return $query;
        }

        // 转换为 int 类型（如果 enum 是 int 类型）
        $intValue = is_numeric($this->value) ? (int)$this->value : $this->value;

        // 验证值是否在 enum 的有效值列表中
        if (!in_array($intValue, $this->enumClass::values(), true)) {
            return $query;
        }

        return $query->where($this->column, $intValue);
    }
}
