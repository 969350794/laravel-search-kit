<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * Enum 过滤
 * 
 * 用于枚举类型的字段查询，自动验证值的有效性并进行类型转换
 * 
 * 配置示例:
 * 'status' => [
 *     'filter' => EnumFilter::class,
 *     'column' => 'status',
 *     'enum' => CompanyStatus::class,
 *     'params' => ['column', 'enum', 'value'],
 * ],
 * 
 * 使用示例:
 * // 查询状态为 Active 的公司
 * $filter = new EnumFilter('status', CompanyStatus::class, 'active');
 * // SQL: WHERE status = 1 (假设 active 对应的值为 1)
 * 
 * // 查询用户类型为 VIP 的记录
 * $filter = new EnumFilter('user_type', UserType::class, UserType::VIP);
 * // SQL: WHERE user_type = 2 (假设 VIP 对应的值为 2)
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
