<?php

namespace Thahulive\Easeblog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class EaseBlogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->publishes([
            __DIR__ . '/Database/migrations' => base_path('/database/migrations'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'easeblog');

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/easeblog'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/Config/easeblog.php', 'easeblog'
        );
    }
}
