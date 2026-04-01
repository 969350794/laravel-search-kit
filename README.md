# Laravel Search Kit

自用的 Laravel 搜索过滤包，支持多种查询操作符和自定义配置。

## ✨ 特性

- 🎯 **多种操作符支持**: `=`, `!=`, `>`, `>=`, `<`, `<=`, `IN`, `NOT IN`, `LIKE`, `BETWEEN` 等
- 🔧 **灵活配置**: 使用显式 `params` 配置，清晰明了
- 🚀 **自动注册**: 安装后自动发现，无需手动配置
- 📦 **易于扩展**: 支持自定义 Filter，遵循统一规范
- ✅ **类型安全**: 完善的验证和错误处理
- 🧪 **完整测试**: 包含全面的单元测试覆盖
- 🆕 **18+ 过滤器**: 涵盖常见查询场景，包括 NULL、布尔值、JSON、全文搜索等

## 📦 安装

```bash
composer require "969350794/laravel-search-kit:^1.0"
```

安装后，Laravel 会自动发现并注册服务提供者，无需任何配置即可使用。

### 发布配置文件（可选）

```bash
php artisan vendor:publish --tag=search-kit-config
```

## 🚀 快速开始

### 1. 创建 SearchDefinition

```php
```php
<?php

namespace App\Query;

use A969350794\LaravelSearchKit\Contracts\SearchDefinition;
use A969350794\LaravelSearchKit\Filters\Shared\ComparisonFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LikeFilter;
use A969350794\LaravelSearchKit\Filters\Shared\DateRangeFilter;

class CompanySearchDefinition implements SearchDefinition
{
    public static function rules(): array
    {
        return [
            // 等于查询 (status = 'active')
            'status' => [
                'filter' => ComparisonFilter::class,
                'column' => 'status',
                'params' => ['column', 'value'],
            ],
            
            // 模糊查询 (company_name LIKE '%关键词%')
            'company_name' => [
                'filter' => LikeFilter::class,
                'column' => 'company_name',
                'params' => ['column', 'value'],
            ],
            
            // 日期范围查询 (created_at BETWEEN '2024-01-01' AND '2024-12-31')
            'created_at' => [
                'filter' => DateRangeFilter::class,
                'column' => 'created_at',
                'params' => ['column', 'key:created_at_start', 'key:created_at_end'],
            ],
        ];
    }
}
```

### 2. 在 Service 中使用

```php
<?php

namespace App\Services;

use A969350794\LaravelSearchKit\Config\SearchConfig;
use A969350794\LaravelSearchKit\Pipeline\QueryPipeline;
use App\Models\Company;
use App\Query\CompanySearchDefinition;

class CompanyService
{
    public function search(array $data)
    {
        $query = Company::query();
        
        return new QueryPipeline($query)
            ->through(SearchConfig::filters(CompanySearchDefinition::class, $data))
            ->get($perPage);
    }
}
```

### 3. 在 Controller 中使用

```php
<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request, CompanyService $service)
    {
        return $service->search($request->all());
    }
}
```

## 📚 支持的 Filter

### 基础查询类

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `ComparisonFilter` | `=`, `!=`, `>`, `>=`, `<`, `<=` | 比较查询（默认 `=`） |
| `InFilter` | `IN`, `NOT IN` | IN 查询（默认 `IN`） |
| `LikeFilter` | `LIKE value%`, `%value`, `%value%` | 模糊查询（默认开头匹配） |
| `BetweenFilter` | `BETWEEN` | 范围查询 |
| `EnumFilter` | - | Enum 枚举过滤 |
| `DateRangeFilter` | `>=`, `<=` (可配置) | 日期范围查询 |

### NULL 和布尔类

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `NullFilter` | `IS NULL`, `IS NOT NULL` | NULL 查询（默认 `IS NULL`） |
| `BooleanFilter` | `=` | 布尔值过滤（true/false → 1/0） |

### JSON 和文本搜索类

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `JsonContainsFilter` | `JSON_CONTAINS` | JSON 包含查询 |
| `FullTextFilter` | `MATCH ... AGAINST` | 全文搜索 |
| `RegexFilter` | `REGEXP` | 正则表达式查询 |

### 特殊比较类

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `CharLengthFilter` | `CHAR_LENGTH` | 字符串长度过滤 |
| `BinaryEqualFilter` | `BINARY =` | 区分大小写比较 |
| `TrimEqualFilter` | `TRIM() =` | 去除空格后比较 |

### 子查询和关系类

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `ExistsFilter` | `EXISTS` | EXISTS 子查询 |
| `WhereHasFilter` | `WHERE HAS` | 关系存在性查询 |

### 复合条件类

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `OrFilter` | `OR` | OR 复合条件 |
| `AndFilter` | `AND` | AND 复合条件 |

## 💡 常用 Filter 使用示例

### ComparisonFilter - 比较查询

```php
// 大于查询 (price > 100)
$filter = new ComparisonFilter('price', 100, '>');
// SQL: WHERE price > 100

// 等于查询 (status = 'active')
$filter = new ComparisonFilter('status', 'active');
// SQL: WHERE status = 'active'

// 不等于查询 (status != 'deleted')
$filter = new ComparisonFilter('status', 'deleted', '!=');
// SQL: WHERE status != 'deleted'
```

### InFilter - IN 查询

```php
// IN 查询 (status IN ('active', 'pending'))
$filter = new InFilter('status', ['active', 'pending']);
// SQL: WHERE status IN ('active', 'pending')

// NOT IN 查询 (status NOT IN ('deleted', 'archived'))
$filter = new InFilter('status', ['deleted', 'archived'], InFilter::MODE_NOT_IN);
// SQL: WHERE status NOT IN ('deleted', 'archived')
```

### LikeFilter - 模糊查询

```php
// 开头匹配 (name LIKE '张%')
$filter = new LikeFilter('name', '张');
// SQL: WHERE name LIKE '张%'

// 结尾匹配 (email LIKE '%@gmail.com')
$filter = new LikeFilter('email', '@gmail.com', LikeFilter::MODE_END);
// SQL: WHERE email LIKE '%@gmail.com'

// 完全匹配 (title LIKE '%PHP%')
$filter = new LikeFilter('title', 'PHP', LikeFilter::MODE_BOTH);
// SQL: WHERE title LIKE '%PHP%'
```

### NullFilter - NULL 查询

```php
// IS NULL (deleted_at IS NULL)
$filter = new NullFilter('deleted_at');
// SQL: WHERE deleted_at IS NULL

// IS NOT NULL (email IS NOT NULL)
$filter = new NullFilter('email', NullFilter::MODE_NOT_NULL);
// SQL: WHERE email IS NOT NULL
```

### BooleanFilter - 布尔值查询

```php
// 真值 (is_active = 1)
$filter = new BooleanFilter('is_active', true);
// SQL: WHERE is_active = 1

// 假值 (is_verified = 0)
$filter = new BooleanFilter('is_verified', false);
// SQL: WHERE is_verified = 0
```

### JsonContainsFilter - JSON 查询

```php
// JSON 包含 (tags 包含 ["php", "laravel"])
$filter = new JsonContainsFilter('tags', ['php', 'laravel']);
// SQL: WHERE json_contains(tags, '["php","laravel"]')
```

### BetweenFilter - 范围查询

```php
// BETWEEN (price BETWEEN 100 AND 500)
$filter = new BetweenFilter('price', 100, 500);
// SQL: WHERE price BETWEEN 100 AND 500

// 单边条件 (price >= 100)
$filter = new BetweenFilter('price', 100, null);
// SQL: WHERE price >= 100
```

### AndFilter / OrFilter - 复合条件

```php
// AND 条件 (age > 18 AND age < 60)
$filter = new AndFilter([
    new ComparisonFilter('age', 18, '>'),
    new ComparisonFilter('age', 60, '<')
]);
// SQL: WHERE (age > 18 AND age < 60)

// OR 条件 (status = 'active' OR status = 'pending')
$filter = new OrFilter([
    new ComparisonFilter('status', 'active'),
    new ComparisonFilter('status', 'pending')
]);
// SQL: WHERE (status = 'active' OR status = 'pending')
```

## 🔧 配置参数说明

`params` 数组支持以下特殊值：

- `'value'` - 从 `data[$key]` 获取值
- `'column'` - 从 `rule['column']` 获取字段名
- `'enum'` - 从 `rule['enum']` 获取 Enum 类
- `'key:xxx'` - 从 `data['xxx']` 获取值
- `'rule:xxx'` - 从 `rule['xxx']` 获取值
- 其他值 - 直接使用，如果 rule 中存在同名配置则优先使用

### DateRangeFilter 高级用法

```php
'created_at' => [
    'filter' => DateRangeFilter::class,
    'column' => 'created_at',
    'start_operator' => '>',  // 可选: >=, >, 默认 >=
    'end_operator' => '<',    // 可选: <=, <, 默认 <=
    'params' => ['column', 'key:created_at_start', 'key:created_at_end', 'start_operator', 'end_operator'],
],
```

### EnumFilter 使用示例

```php
'status' => [
    'filter' => EnumFilter::class,
    'column' => 'status',
    'enum' => CompanyStatus::class,
    'params' => ['column', 'enum', 'value'],
],
```

## 🧪 测试

运行测试套件：

```bash
cd laravel-search-kit
vendor/bin/phpunit
```

## 🤝 贡献

欢迎提交 Issue 和 Pull Request！

## 📄 许可证

此项目基于 MIT 许可证开源 - 详见 [LICENSE](LICENSE) 文件。
