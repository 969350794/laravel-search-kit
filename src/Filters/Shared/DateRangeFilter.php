<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 日期范围过滤
 *
 * 配置示例:
 *
 * 默认使用 >= 和 <=:
 * 'created_at' => [
 *     'filter' => DateRangeFilter::class,
 *     'column' => 'created_at',
 *     'params' => ['column', 'key:created_at_start', 'key:created_at_end'],
 * ],
 *
 * 自定义操作符:
 * 'created_at' => [
 *     'filter' => DateRangeFilter::class,
 *     'column' => 'created_at',
 *     'start_operator' => '>',  // 可选: >=, >, 默认 >=
 *     'end_operator' => '<',    // 可选: <=, <, 默认 <=
 *     'params' => ['column', 'key:created_at_start', 'key:created_at_end', 'start_operator', 'end_operator'],
 * ],
 */
class DateRangeFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected ?string $start,
        protected ?string $end,
        protected string  $startOperator = '>=',
        protected string  $endOperator = '<='
    )
    {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null) {
            return $query;
        }

        // 验证操作符
        $validOperators = ['>=', '>', '<=', '<'];
        if (!in_array($this->startOperator, $validOperators, true)) {
            $this->startOperator = '>=';
        }

        if (!in_array($this->endOperator, $validOperators, true)) {
            $this->endOperator = '<=';
        }

        // 应用开始日期条件
        if ($this->start !== null && $this->start !== '') {
            $query = $query->where($this->column, $this->startOperator, $this->start);
        }

        // 应用结束日期条件
        if ($this->end !== null && $this->end !== '') {
            $query = $query->where($this->column, $this->endOperator, $this->end);
        }

        return $query;
    }
}
