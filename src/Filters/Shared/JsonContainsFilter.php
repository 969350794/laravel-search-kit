<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Filters\Shared;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * JSON 包含过滤 (MySQL json_contains)
 * 
 * 用于查询 JSON 字段是否包含指定的值，适用于标签、配置等场景
 * 
 * 配置示例:
 * 'tags' => [
 *     'filter' => JsonContainsFilter::class,
 *     'column' => 'tags',
 *     'params' => ['column', 'value'],  // value 应该是数组或 JSON 字符串
 * ],
 * 
 * 使用示例:
 * // 查询包含 "php" 和 "laravel" 标签的文章
 * $filter = new JsonContainsFilter('tags', ['php', 'laravel']);
 * // SQL: WHERE json_contains(tags, '["php","laravel"]')
 * 
 * // 查询包含指定 ID 的配置项
 * $filter = new JsonContainsFilter('settings', json_encode(["theme" => "dark"]));
 * // SQL: WHERE json_contains(settings, '{"theme":"dark"}')
 * 
 * // 查询包含特定权限的用户
 * $filter = new JsonContainsFilter('permissions', ['edit', 'delete']);
 * // SQL: WHERE json_contains(permissions, '["edit","delete"]')
 */
class JsonContainsFilter implements QueryFilter
{
    public function __construct(
        protected ?string $column,
        protected mixed $value
    ) {
    }

    public function apply(Builder $query): Builder
    {
        if ($this->column === null || $this->value === null) {
            return $query;
        }

        // 如果 value 是数组，转换为 JSON 字符串
        $jsonValue = is_array($this->value) ? json_encode($this->value) : $this->value;

        if (empty($jsonValue)) {
            return $query;
        }

        return $query->whereRaw(
            "json_contains({$this->column}, ?)",
            [$jsonValue]
        );
    }
}
