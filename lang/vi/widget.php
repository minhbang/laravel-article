<?php
return [
    'article' => [
        'title' => 'Bài viết',
        'description' => 'Hiển thị tóm tắc nội dung bài viết',
        'id' => 'Bài viết',
        'show_title' => 'Hiển thị Tiêu đề bài viết?',
        'show_image' => 'Hiển thị Ảnh bìa bài viết?',
        'show_author' => 'Hiển thị Tác giả bài viết?',
        'show_datetime' => 'Hiển thị Thời gian bài viết?',
        'show_summary' => 'Hiển thị Tóm tắc bài viết?',
        'show_readmore' => 'Hiển thị link đọc thêm?',
    ],
    'article_category' => [
        'title' => 'Bài viết / Danh mục',
        'description' => 'Hiển thị Danh sách Danh mục Bài viết',
    ],
    'latest_articles' => [
        'title' => 'Bài viết / Danh sách',
        'description' => 'Hiển thị danh sách Bài viết mới nhất',
        'category_id' => 'Danh mục',
        'show_link_category' => 'Link đến Danh mục',
        'limit' => 'Số bài viết hiển thị',
        'item_css' => 'Css các item',
        'item_css_hint' => 'Phân các bằng dấu <code>|</code>, hết sẽ lặp lại, vd: <code>col-md-4 wow fadeInLeft|col-md-4 wow zoomIn|col-md-4 wow fadeInRight</code>',
        'category_title' => 'Tiêu đề là tên Danh mục',
        'template' => 'Cách hiển thị',
        // Danh sách template
        'templates' => [
            'default' => 'Mặc định',
            'style1' => 'Style 1',
            'style2' => 'Style 2',
            'style3' => 'Style 3',
            'slider' => 'Slider',
        ],
    ],
];