<?php

namespace Minhbang\Article;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package Minhbang\Article
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @param \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'article');
        $this->loadViewsFrom(__DIR__ . '/../views', 'article');
        $this->publishes(
            [
                __DIR__ . '/../views'                           => base_path('resources/views/vendor/article'),
                __DIR__ . '/../lang'                            => base_path('resources/lang/vendor/article'),
                __DIR__ . '/../config/article.php'             => config_path('article.php'),
                __DIR__ . '/../database/migrations/' .
                '2015_09_18_161102_create_articles_table.php' =>
                    database_path('migrations/2015_09_18_161102_create_articles_table.php'),
            ]
        );

        if (config('article.add_route') && !$this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }
        // pattern filters
        $router->pattern('article', '[0-9]+');
        // model bindings
        $router->model('article', 'Minhbang\Article\Article');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/article.php', 'article');
    }
}