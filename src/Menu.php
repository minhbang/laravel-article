<?php namespace Minhbang\Article;
/**
 * Class Menu
 *
 * @package Minhbang\Article
 */
class Menu
{
    /**
     * @return array
     */
    public static function all()
    {
        $menus = [
            'backend.sidebar.content.article' => [
                'priority' => 1,
                'url'      => 'route:backend.article.index',
                'label'    => 'trans:article::common.article',
                'icon'     => 'fa-newspaper-o',
                'active'   => 'backend/article*',
            ],
            'manage.sidebar.article'          => [
                'priority' => 1,
                'url'      => '#',
                'label'    => 'trans:article::common.article',
                'icon'     => 'fa-newspaper-o',
                'active'   => 'manage/article*',
            ],
        ];

        foreach (config('article.types') as $i => $type) {
            $menus["manage.sidebar.article.{$type}"] = [
                'priority' => $i,
                'url'      => route('manage.article.index', ['type' => $type]),
                'label'    => "trans:article::common.types.{$type}",
                'icon'     => 'fa-files',
                'active'   => "manage/article/of/{$type}",
            ];
        }

        return $menus;
    }
}