<?php

namespace A969350794\LaravelSearchKit\Tests\Filters;

use A969350794\LaravelSearchKit\Tests\TestCase;
use A969350794\LaravelSearchKit\Filters\Shared\EqualFilter;
use A969350794\LaravelSearchKit\Filters\Shared\NotEqualFilter;
use A969350794\LaravelSearchKit\Filters\Shared\GreaterThanFilter;
use A969350794\LaravelSearchKit\Filters\Shared\GreaterThanOrEqualFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LessThanFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LessThanOrEqualFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LikeFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LikeStartFilter;
use A969350794\LaravelSearchKit\Filters\Shared\LikeEndFilter;
use A969350794\LaravelSearchKit\Filters\Shared\DateRangeFilter;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class FilterTest extends TestCase
{
    /**
     * 测试 EqualFilter
     */
    public function test_equal_filter()
    {
        $filter = new EqualFilter('status', 'active');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('status', '=', 'active')
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 NotEqualFilter
     */
    public function test_not_equal_filter()
    {
        $filter = new NotEqualFilter('status', 'inactive');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('status', '!=', 'inactive')
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 GreaterThanFilter
     */
    public function test_greater_than_filter()
    {
        $filter = new GreaterThanFilter('price', 150);

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('price', '>', 150)
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 GreaterThanOrEqualFilter
     */
    public function test_greater_than_or_equal_filter()
    {
        $filter = new GreaterThanOrEqualFilter('price', 150);

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('price', '>=', 150)
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 LessThanFilter
     */
    public function test_less_than_filter()
    {
        $filter = new LessThanFilter('price', 150);

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('price', '<', 150)
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 LessThanOrEqualFilter
     */
    public function test_less_than_or_equal_filter()
    {
        $filter = new LessThanOrEqualFilter('price', 150);

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('price', '<=', 150)
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 LikeFilter
     */
    public function test_like_filter()
    {
        $filter = new LikeFilter('name', 'test');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('name', 'like', '%test%')
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 LikeStartFilter
     */
    public function test_like_start_filter()
    {
        $filter = new LikeStartFilter('name', 'test');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('name', 'like', 'test%')
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 LikeEndFilter
     */
    public function test_like_end_filter()
    {
        $filter = new LikeEndFilter('name', 'test');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockEloquentBuilder->expects($this->once())
            ->method('where')
            ->with('name', 'like', '%test')
            ->willReturn($mockEloquentBuilder);

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 DateRangeFilter
     */
    public function test_date_range_filter()
    {
        $filter = new DateRangeFilter('created_at', '2024-01-01', '2024-06-30');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        // 使用两个单独的 expect 调用来测试两次 where 调用
        $mockEloquentBuilder->expects($this->exactly(2))
            ->method('where')
            ->with($this->logicalOr(
                $this->equalTo('created_at'),
                $this->equalTo('2024-01-01'),
                $this->equalTo('2024-06-30'),
                $this->equalTo('>='),
                $this->equalTo('<=')
            ))
            ->willReturnCallback(function ($column, $operator, $value) use ($mockEloquentBuilder) {
                // 验证具体参数
                $this->assertContains($column, ['created_at']);
                $this->assertContains($operator, ['>=', '<=']);
                $this->assertContains($value, ['2024-01-01', '2024-06-30']);
                return $mockEloquentBuilder;
            });

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试 DateRangeFilter 自定义操作符
     */
    public function test_date_range_filter_with_custom_operators()
    {
        $filter = new DateRangeFilter('created_at', '2024-01-01', '2024-12-31', '>', '<');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        // 使用两个单独的 expect 调用来测试两次 where 调用
        $mockEloquentBuilder->expects($this->exactly(2))
            ->method('where')
            ->willReturnCallback(function ($column, $operator, $value) use ($mockEloquentBuilder) {
                if ($column === 'created_at' && $operator === '>' && $value === '2024-01-01') {
                    return $mockEloquentBuilder;
                } elseif ($column === 'created_at' && $operator === '<' && $value === '2024-12-31') {
                    return $mockEloquentBuilder;
                }
                return $mockEloquentBuilder;
            });

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }

    /**
     * 测试过滤器在空值时的行为
     */
    public function test_filters_with_null_values()
    {
        // 测试列名为 null 的情况
        $filter = new EqualFilter(null, 'some_value');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        // 当列名为空时，不应该调用 where 方法
        $mockEloquentBuilder->expects($this->never())
            ->method('where');

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);

        // 测试值为空字符串的情况
        $filter2 = new EqualFilter('status', '');

        $mockEloquentBuilder2 = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        // 当值为空字符串时，不应该调用 where 方法
        $mockEloquentBuilder2->expects($this->never())
            ->method('where');

        $result2 = $filter2->apply($mockEloquentBuilder2);
        $this->assertSame($mockEloquentBuilder2, $result2);
    }

    /**
     * 测试 DateRangeFilter 空值情况
     */
    public function test_date_range_filter_with_null_values()
    {
        $filter = new DateRangeFilter(null, '2024-01-01', '2024-06-30');

        $mockEloquentBuilder = $this->getMockBuilder(EloquentBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        // 当列名为空时，不应该调用 where 方法
        $mockEloquentBuilder->expects($this->never())
            ->method('where');

        $result = $filter->apply($mockEloquentBuilder);
        $this->assertSame($mockEloquentBuilder, $result);
    }
}
