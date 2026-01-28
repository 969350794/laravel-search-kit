<?php

declare(strict_types=1);

namespace A969350794\LaravelSearchKit;

use Illuminate\Support\ServiceProvider;

class LaravelSearchKitServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // 可以在这里注册服务容器绑定
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 发布配置文件（可选）
        $this->publishes([
            __DIR__ . '/../config/search-kit.php' => config_path('search-kit.php'),
        ], 'search-kit-config');
    }
}
