<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 字符串长度过滤 (CHAR_LENGTH)
 * 
 * 用于根据字符串的长度进行过滤，支持各种比较操作符
 * 
 * 配置示例:
 * 'name' => [
 *     'filter' => CharLengthFilter::class,
 *     'column' => 'name',
 *     'params' => ['column', 'length', 'operator'],
 * ],
 * 
 * 使用示例:
 * // 查询名字长度大于 5 的用户
 * $filter = new CharLengthFilter('name', 5, '>');
 * // SQL: WHERE CHAR_LENGTH(name) > 5
 * 
 * // 查询名字长度等于 3 的记录
 * $filter = new CharLengthFilter('code', 3, '=');
 * // SQL: WHERE CHAR_LENGTH(code) = 3
 * 
 * // 查询描述长度不超过 100 的文章
 * $filter = new CharLengthFilter('description', 100, '<=');
 * // SQL: WHERE CHAR_LENGTH(description) <= 100
 */
class CharLengthFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected ?int $length,
        protected string $operator = '>'
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->length === null) {
            return $query;
        }

        $allowedOperators = ['=', '!=', '>', '>=', '<', '<='];
        $operator = in_array($this->operator, $allowedOperators) ? $this->operator : '>';

        return $query->whereRaw(
            "CHAR_LENGTH({$this->column}) {$operator} ?",
            [$this->length]
        );
    }
}
