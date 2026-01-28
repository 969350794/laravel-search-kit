<?php

namespace A969350794\LaravelSearchKit\Tests;

use A969350794\LaravelSearchKit\Config\SearchConfig;
use A969350794\LaravelSearchKit\Contracts\SearchDefinition;

class SearchConfigTest extends TestCase
{
    /**
     * 测试 SearchConfig::filters 方法
     */
    public function test_search_config_filters()
    {
        $filters = SearchConfig::filters(TestSearchDefinition::class, [
            'status' => 1,
            'name' => 'test',
        ]);
        
        $this->assertIsArray($filters);
        $this->assertNotEmpty($filters);
    }
}

/**
 * 测试用的 SearchDefinition
 */
class TestSearchDefinition implements SearchDefinition
{
    public static function rules(): array
    {
        return [
            'status' => [
                'filter' => \A969350794\LaravelSearchKit\Filters\Shared\EqualFilter::class,
                'column' => 'status',
                'params' => ['column', 'value'],
            ],
            'name' => [
                'filter' => \A969350794\LaravelSearchKit\Filters\Shared\LikeFilter::class,
                'column' => 'name',
                'params' => ['column', 'value'],
            ],
        ];
    }
}
