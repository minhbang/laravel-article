<?php

return [
    // Hình đại diện của bài viết, lưu trong thư mục con của <my_upload:>
    'featured_image' => [
        'dir' => 'images/articles',
        'width' => 490,
        'height' => 240,
        'width_sm' => 110,
        'height_sm' => 80,
    ],

    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares' => [
        'frontend' => ['web'],
        'backend' => ['web', 'role:sys.admin'],
    ],

    // Định nghĩa menus cho category
    'menus' => [
        'backend.sidebar.content.article' => [
            'priority' => 1,
            'url' => 'route:backend.article.index',
            'label' => 'trans:article::common.article',
            'icon' => 'fa-newspaper-o',
            'active' => 'backend/article*',
        ],
    ],
    'menuTypes' => [
        'article' => [
            'title' => 'trans::article::menu.article.title',
            'icon' => 'newspaper-o',
            'class' => \Minhbang\Article\Menus\ArticleMenu::class,
        ],
        'article_category' => [
            'title' => 'trans::article::menu.article_category.title',
            'icon' => 'sitemap',
            'class' => \Minhbang\Article\Menus\ArticleCategoryMenu::class,
        ],
    ],

    'widgets' => [
        'article' => [
            'title' => 'trans::article::widget.article.title',
            'description' => 'trans::article::widget.article.description',
            'icon' => 'newspaper-o',
            'class' => \Minhbang\Article\Widgets\ArticleWidget::class,
        ],
        'article_category' => [
            'title' => 'trans::article::widget.article_category.title',
            'description' => 'trans::article::widget.article_category.description',
            'icon' => 'sitemap',
            'class' => \Minhbang\Article\Widgets\ArticleCategoryWidget::class,
        ],
        'latest_articles' => [
            'title' => 'trans::article::widget.latest_articles.title',
            'description' => 'trans::article::widget.latest_articles.description',
            'icon' => 'list',
            'class' => \Minhbang\Article\Widgets\LatestArticlesWidget::class,
        ],
    ],
];