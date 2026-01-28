<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Pipeline;

use A969350794\LaravelSearchKit\Contracts\QueryFilter;
use Illuminate\Database\Eloquent\Builder;

class QueryPipeline
{
    public function __construct(protected Builder $query)
    {
    }

    /**
     * 组装查询
     *
     * @param array $filters
     * @return Builder
     */
    public function through(array $filters): Builder
    {
        if ($filters) {
            foreach ($filters as $filter) {
                if ($filter instanceof QueryFilter) {
                    $this->query = $filter->apply($this->query);
                }
            }
        }

        return $this->query;
    }

}
