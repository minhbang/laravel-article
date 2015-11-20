<?php
return [
    // Hình đại diện của bài viết, lưu trong thư mục con của <upload_path>
    'featured_image' => [
        'dir'       => 'images/articles',
        'width'     => 490,
        'height'    => 294,
        'width_sm'  => 110,
        'height_sm' => 80,

    ],
    /**
     * Tự động add các route
     */
    'add_route'      => true,
    /**
     * Category types của Article, chú ý phải add những type này vào category config
     */
    'types'          => ['article'],
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares'    => [
        'frontend' => null,
        'backend'  => 'admin',
    ],
];