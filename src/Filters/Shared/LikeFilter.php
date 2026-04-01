<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * LIKE 模糊过滤
 * 
 * 用于字符串的模糊匹配查询，支持开头匹配、结尾匹配和完全匹配三种模式
 * 
 * 配置示例:
 * 'name' => [
 *     'filter' => LikeFilter::class,
 *     'column' => 'name',
 *     'params' => ['column', 'value'],
 * ],
 * 
 * 支持三种模式:
 * - start: value% (默认，开头匹配)
 * - end: %value (结尾匹配)
 * - both: %value% (完全匹配)
 * 
 * 使用示例:
 * // 查询名字以 "张" 开头的用户
 * $filter = new LikeFilter('name', '张');
 * // SQL: WHERE name LIKE '张%'
 * 
 * // 查询邮箱以 "@gmail.com" 结尾的用户
 * $filter = new LikeFilter('email', '@gmail.com', LikeFilter::MODE_END);
 * // SQL: WHERE email LIKE '%@gmail.com'
 * 
 * // 查询包含 "PHP" 的文章标题
 * $filter = new LikeFilter('title', 'PHP', LikeFilter::MODE_BOTH);
 * // SQL: WHERE title LIKE '%PHP%'
 */
class LikeFilter implements QueryFilter
{
    public const MODE_START = 'start';
    public const MODE_END = 'end';
    public const MODE_BOTH = 'both';

    public function __construct(
        protected ?string $column,
        protected ?string $value,
        protected string $mode = self::MODE_START
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null || $this->value === '') {
            return $query;
        }

        $pattern = match($this->mode) {
            self::MODE_END => '%' . $this->value,
            self::MODE_BOTH => '%' . $this->value . '%',
            default => $this->value . '%',
        };

        return $query->where($this->column, 'like', $pattern);
    }
}
