<?php

return [
    'types'          => ['news', 'page'],
    // Category setting
    'category'       => [
        'title'     => 'article::common.types',
        'max_depth' => 5,
    ],

    // Hình đại diện của bài viết, lưu trong thư mục con của <my_upload:>
    'featured_image' => [
        'dir'       => 'images/articles',
        'width'     => 490,
        'height'    => 240,
        'width_sm'  => 110,
        'height_sm' => 80,
    ],

    // Html Presenter
    'presenter'      => \Minhbang\Article\Presenter::class,
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares'    => [
        'frontend' => [],
        'manage'   => [],
        'backend'  => ['web', 'role:sys.admin'],
    ],

    // Định nghĩa menus cho category
    'menus'          => [
        'backend.sidebar.content.article' => [
            'priority' => 1,
            'url'      => 'route:backend.article.index',
            'label'    => 'trans:article::common.article',
            'icon'     => 'fa-newspaper-o',
            'active'   => 'backend/article*',
        ],
    ],
];