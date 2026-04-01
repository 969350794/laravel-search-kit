<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 比较过滤 (>, >=, <, <=, =, !=)
 * 
 * 用于各种数值、日期等类型的比较查询，支持所有常见的比较操作符
 * 
 * 配置示例:
 * 'price' => [
 *     'filter' => ComparisonFilter::class,
 *     'column' => 'price',
 *     'params' => ['column', 'value', 'operator'],
 * ],
 * 
 * 支持的操作符:
 * - '>' : 大于
 * - '>=': 大于或等于
 * - '<' : 小于
 * - '<=': 小于或等于
 * - '=' : 等于 (默认)
 * - '!=': 不等于
 * 
 * 使用示例:
 * // 查询价格大于 100 的商品
 * $filter = new ComparisonFilter('price', 100, '>');
 * // SQL: WHERE price > 100
 * 
 * // 查询库存大于等于 10 的商品
 * $filter = new ComparisonFilter('stock', 10, '>=');
 * // SQL: WHERE stock >= 10
 * 
 * // 查询状态不等于 deleted 的记录
 * $filter = new ComparisonFilter('status', 'deleted', '!=');
 * // SQL: WHERE status != 'deleted'
 */
class ComparisonFilter implements QueryFilter
{
    /**
     * 支持的操作符列表
     */
    protected array $operators = [
        '>',   // 大于
        '>=',  // 大于或等于
        '<',   // 小于
        '<=',  // 小于或等于
        '=',   // 等于
        '!=',  // 不等于
    ];

    public function __construct(
        protected ?string $column,
        protected mixed $value,
        protected string $operator = '='
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null || $this->value === '') {
            return $query;
        }

        // 如果操作符不在允许的列表中，默认使用 '='
        $operator = in_array($this->operator, $this->operators) ? $this->operator : '=';

        return $query->where($this->column, $operator, $this->value);
    }
}
