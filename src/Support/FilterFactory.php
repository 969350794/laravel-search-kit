<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Support;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use InvalidArgumentException;

class FilterFactory
{
    /**
     * 创建 Filter 实例
     *
     * @param string $key 搜索字段的 key（对应 data 中的 key）
     * @param array $rule 规则配置，必须包含 'filter' 和 'params'
     * @param array $data 搜索数据
     * @return QueryFilter|null
     * @throws InvalidArgumentException
     */
    public static function make(string $key, array $rule, array $data): ?QueryFilter
    {
        // 验证必要配置
        if (!isset($rule['filter'])) {
            throw new InvalidArgumentException("Rule for '{$key}' missing 'filter' key");
        }

        $filterClass = $rule['filter'];

        if (!class_exists($filterClass)) {
            throw new InvalidArgumentException("Filter class '{$filterClass}' not found");
        }

        if (!is_subclass_of($filterClass, QueryFilter::class)) {
            throw new InvalidArgumentException("Filter class '{$filterClass}' must implement QueryFilter");
        }

        // 如果没有 params 配置，尝试无参构造
        if (empty($rule['params'])) {
            return new $filterClass();
        }

        // 解析 params 配置，支持以下格式：
        // 1. 字符串 'value' - 从 data[$key] 获取
        // 2. 字符串 'column' - 从 rule['column'] 获取
        // 3. 字符串 'enum' - 从 rule['enum'] 获取
        // 4. 字符串 'key:xxx' - 从 data['xxx'] 获取
        // 5. 其他值 - 直接使用
        $args = [];
        foreach ($rule['params'] as $param) {
            $args[] = self::resolveParam($param, $key, $rule, $data);
        }

        return new $filterClass(...$args);
    }

    /**
     * 解析参数值
     *
     * @param mixed $param 参数配置
     * @param string $key 当前字段 key
     * @param array $rule 规则配置
     * @param array $data 数据
     * @return mixed
     */
    private static function resolveParam($param, string $key, array $rule, array $data)
    {
        // 字符串参数，支持特殊关键字
        if (is_string($param)) {
            // 'value' - 从 data[$key] 获取
            if ($param === 'value') {
                return $data[$key] ?? null;
            }

            // 'column' - 从 rule['column'] 获取
            if ($param === 'column') {
                return $rule['column'] ?? null;
            }

            // 'enum' - 从 rule['enum'] 获取
            if ($param === 'enum') {
                return $rule['enum'] ?? null;
            }

            // 'key:xxx' - 从 data['xxx'] 获取
            if (str_starts_with($param, 'key:')) {
                $dataKey = substr($param, 4);
                return $data[$dataKey] ?? null;
            }

            // 'rule:xxx' - 从 rule['xxx'] 获取（用于获取 rule 中的其他配置项）
            if (str_starts_with($param, 'rule:')) {
                $ruleKey = substr($param, 5);
                return $rule[$ruleKey] ?? null;
            }

            // 如果 rule 中存在同名配置，优先使用 rule 中的值
            if (isset($rule[$param])) {
                return $rule[$param];
            }

            // 其他字符串直接返回
            return $param;
        }

        // 非字符串直接返回
        return $param;
    }
}
