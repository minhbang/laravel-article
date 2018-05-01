<?php

return [
    // Hình đại diện của bài viết, lưu trong thư mục con của <upload:>
    'featured_image' => [
        'dir' => 'images/articles',
        'width' => 490,
        'height' => 294,
        'width_sm' => 110,
        'height_sm' => 80,
    ],

    'display' => [
        'show_time' => false,
        'show_author' => false,
    ],
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares' => [
        'frontend' => ['web'],
        'backend' => ['web', 'auth'],
    ],

    // Định nghĩa menus cho category
    'menus' => [
        'backend.sidebar.content.article' => [
            'priority' => 1,
            'url' => 'route:backend.article.index',
            'label' => '__:Article',
            'icon' => 'fa-newspaper-o',
            'active' => 'backend/article*',
        ],
    ],
    'menuTypes' => [
        'article' => [
            'title' => '__::Article',
            'icon' => 'newspaper-o',
            'class' => \Minhbang\Article\Menus\ArticleMenu::class,
        ],
        'article_category' => [
            'title' => '__::Article category',
            'icon' => 'sitemap',
            'class' => \Minhbang\Article\Menus\ArticleCategoryMenu::class,
        ],
    ],

    'widgets' => [
        'article' => [
            'title' => '__::Article',
            'description' => '__::Display article summary block',
            'icon' => 'newspaper-o',
            'class' => \Minhbang\Article\Widgets\ArticleWidget::class,
        ],
        'article_category' => [
            'title' => '__::Article / Category',
            'description' => '__::Display article category list',
            'icon' => 'sitemap',
            'class' => \Minhbang\Article\Widgets\ArticleCategoryWidget::class,
        ],
        'latest_articles' => [
            'title' => '__::Article / List',
            'description' => '__::Display latest article list',
            'icon' => 'list',
            'class' => \Minhbang\Article\Widgets\LatestArticlesWidget::class,
        ],
    ],
];
