<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

/**
 * 全文搜索过滤 (MySQL MATCH ... AGAINST)
 * 
 * 用于对文本字段进行全文搜索，需要在数据库中添加 FULLTEXT 索引
 * 
 * 配置示例:
 * 'content' => [
 *     'filter' => FullTextFilter::class,
 *     'column' => 'content',
 *     'params' => ['column', 'value'],
 * ],
 * 
 * 数据库迁移示例:
 * // 在 migration 中添加全文索引
 * $table->fullText('content');
 * $table->fullText(['title', 'content']); // 多列全文索引
 * 
 * 使用示例:
 * // 搜索包含关键词的文章内容
 * $filter = new FullTextFilter('content', 'PHP 开发教程');
 * // SQL: WHERE MATCH(content) AGAINST('PHP 开发教程' IN NATURAL LANGUAGE MODE)
 * 
 * // 搜索标题和描述
 * $filter = new FullTextFilter('title', 'Laravel 技巧');
 * // SQL: WHERE MATCH(title) AGAINST('Laravel 技巧' IN NATURAL LANGUAGE MODE)
 */
class FullTextFilter implements QueryFilter
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
            "MATCH({$this->column}) AGAINST(? IN NATURAL LANGUAGE MODE)",
            [$this->value]
        );
    }
}
