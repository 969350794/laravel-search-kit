<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit\Config;

use A969350794\LaravelSearchKit\Contracts\SearchDefinition;
use A969350794\LaravelSearchKit\Support\FilterFactory;

class SearchConfig
{
    public static function filters(SearchDefinition|string $definition, array $data): array
    {
        $rules = is_string($definition) ? $definition::rules() : $definition->rules();

        $filters = [];
        foreach ($rules as $key => $rule) {
            $filters[] = FilterFactory::make($key, $rule, $data);
        }

        return array_filter($filters);
    }
}
