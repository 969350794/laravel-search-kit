# Laravel Search Kit

自用的 Laravel 搜索过滤包，支持多种查询操作符和自定义配置。

## ✨ 特性

- 🎯 **多种操作符支持**: `=`, `!=`, `>`, `>=`, `<`, `<=`, `IN`, `NOT IN`, `LIKE`, `BETWEEN` 等
- 🔧 **灵活配置**: 使用显式 `params` 配置，清晰明了
- 🚀 **自动注册**: 安装后自动发现，无需手动配置
- 📦 **易于扩展**: 支持自定义 Filter，遵循统一规范
- ✅ **类型安全**: 完善的验证和错误处理
- 🧪 **完整测试**: 包含全面的单元测试覆盖

## 📦 安装

```bash
composer require "969350794/laravel-search-kit:^1.*"
```

安装后，Laravel 会自动发现并注册服务提供者，无需任何配置即可使用。

### 发布配置文件（可选）

```bash
php artisan vendor:publish --tag=search-kit-config
```

## 🚀 快速开始

### 1. 创建 SearchDefinition

```php
<?php

namespace App\Query;

use A969350794\LaravelSearchKit\Contracts\SearchDefinition;
use A969350794\LaravelSearchKit\Filters\Shared\EqualFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LikeFilter;
use A969350794\LaravelSearchKit\Filters\Shared\DateRangeFilter;

class CompanySearchDefinition implements SearchDefinition
{
    public static function rules(): array
    {
        return [
            'status' => [
                'filter' => EqualFilter::class,
                'column' => 'status',
                'params' => ['column', 'value'],
            ],
            
            'company_name' => [
                'filter' => LikeFilter::class,
                'column' => 'company_name',
                'params' => ['column', 'value'],
            ],
            
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

| Filter | 操作符 | 说明 |
|--------|--------|------|
| `EqualFilter` | `=` | 等于 |
| `NotEqualFilter` | `!=` | 不等于 |
| `GreaterThanFilter` | `>` | 大于 |
| `GreaterThanOrEqualFilter` | `>=` | 大于等于 |
| `LessThanFilter` | `<` | 小于 |
| `LessThanOrEqualFilter` | `<=` | 小于等于 |
| `InFilter` | `IN` | IN 查询 |
| `NotInFilter` | `NOT IN` | NOT IN 查询 |
| `LikeFilter` | `LIKE %value%` | 模糊查询 |
| `LikeStartFilter` | `LIKE value%` | 开头匹配 |
| `LikeEndFilter` | `LIKE %value` | 结尾匹配 |
| `BetweenFilter` | `BETWEEN` | 范围查询 |
| `EnumFilter` | - | Enum 枚举过滤 |
| `DateRangeFilter` | `>=`, `<=` (可配置) | 日期范围查询 |

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
