<?php namespace Minhbang\Article;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use CategoryManager;
use MenuManager;
use Authority;
use Kit;
use Status;
use Layout;
use Minhbang\Status\Managers\Simple;

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
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'article');
        $this->loadViewsFrom(__DIR__.'/../views', 'article');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
                __DIR__.'/../views' => base_path('resources/views/vendor/article'),
                __DIR__.'/../lang' => base_path('resources/lang/vendor/article'),
                __DIR__.'/../config/article.php' => config_path('article.php'),
            ]);

        $class = Article::class;
        // pattern filters
        $router->pattern('article', '[0-9]+');
        // model bindings
        $router->model('article', $class);
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        Kit::alias($class, 'article');
        Kit::title($class, trans('article::common.article'));
        Status::register($class, Simple::class);
        CategoryManager::register($class);
        MenuManager::registerMenuTypes(config('article.menuTypes'));
        MenuManager::addItems(config('article.menus'));
        Authority::permission()->registerCRUD($class);
        Kit::writeablePath('upload:'.config('article.featured_image.dir'), 'trans::article::common.featured_image_dir');
        Layout::registerWidgetTypes(config('article.widgets'));
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/article.php', 'article');
    }
}
