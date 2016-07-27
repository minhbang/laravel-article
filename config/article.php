<?php

return [
    'types'          => ['news', 'page'],
    // Category setting
    'category'       => [
        'title'     => 'article::common.types',
        'max_depth' => 5,
    ],

    // Hình đại diện của bài viết, lưu trong thư mục con của <upload_path>
    'featured_image' => [
        'dir'       => 'images/articles',
        'width'     => 490,
        'height'    => 240,
        'width_sm'  => 110,
        'height_sm' => 80,
    ],

    // Html Presenter
    'html'           => \Minhbang\Article\Html::class,

    // Datatable Manager
    'datatable'      => \Minhbang\Article\Datatable::class,

    // Access Control
    'access_control' => \Minhbang\Security\SimpleAccessControl::class,

    /**
     * Tự động add các route
     */
    'add_route'      => true,

    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares'    => [
        'frontend' => [],
        'manage'   => [],
        'backend'  => 'role:sys.admin',
    ],
    // Định nghĩa menus cho category
    'menus'              => [\Minhbang\Article\Menu::class, 'all'],
];