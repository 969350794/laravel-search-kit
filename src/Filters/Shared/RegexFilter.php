<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 正则表达式过滤 (MySQL REGEXP)
 * 
 * 用于使用正则表达式进行复杂的模式匹配查询
 * 
 * 配置示例:
 * 'email' => [
 *     'filter' => RegexFilter::class,
 *     'column' => 'email',
 *     'params' => ['column', 'pattern'],
 * ],
 * 
 * 使用示例:
 * // 查询以 gmail 开头的邮箱
 * $filter = new RegexFilter('email', '^gmail');
 * // SQL: WHERE email REGEXP '^gmail'
 * 
 * // 查询符合特定格式的手机号
 * $filter = new RegexFilter('phone', '^1[3-9]\d{9}$');
 * // SQL: WHERE phone REGEXP '^1[3-9]\d{9}$'
 * 
 * // 查询包含数字的用户名
 * $filter = new RegexFilter('username', '[0-9]+');
 * // SQL: WHERE username REGEXP '[0-9]+'
 */
class RegexFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected ?string $pattern
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->pattern === null || $this->pattern === '') {
            return $query;
        }

        return $query->whereRaw(
            "{$this->column} REGEXP ?",
            [$this->pattern]
        );
    }
}
