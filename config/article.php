<?php
return [
    /**
     * Thư mục chứa hình ảnh bài viết, thư mục con của <upload_path>
     */
    'images_dir'  => 'images/articles',
    /**
     * Tự động add các route
     */
    'add_route'   => true,
    /**
     * Category type
     */
    'category'    => 'article',
    /**
     * Khai báo middlewares cho các Controller
     */
    'middlewares' => [
        'frontend' => null,
        'backend'  => 'admin',
    ],
];