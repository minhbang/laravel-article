<?php
namespace Minhbang\Article;

use Minhbang\Kit\Extensions\Datatable as BaseDatatable;
use Html as HtmlBuilder;

/**
 * Class Datatable
 * Quản lý Datatable của article
 *
 * @package Minhbang\Article
 */
class Datatable extends BaseDatatable
{
    /**
     * @return array
     */
    function columns()
    {
        return [
            'index'   => [
                'title' => '#',
                'data'  => '',
            ],
            'title'   => [
                'title' => trans('article::common.title'),
                'data'  => function (Article $model) {
                    return HtmlBuilder::linkQuickUpdate(
                        $model->id,
                        $model->title,
                        [
                            'attr'  => 'title',
                            'title' => trans("article::common.title"),
                            'class' => 'w-lg',
                        ]
                    );
                },
            ],
            'author'  => [
                'title' => trans('article::common.user'),
                'data'  => function (Article $model) {
                    return $model->author;
                },
            ],
            'status'  => [
                'title' => trans('common.status'),
                'data'  => function (Article $model) {
                    return $this->html->statusFormatted($model);
                },
            ],
            'actions' => [
                'title' => trans('common.actions'),
                'data'  => function (Article $model) {
                    return HtmlBuilder::tableActions(
                        "{$this->zone}.article",
                        ['article' => $model->id],
                        $model->title,
                        $this->name,
                        [
                            'renderPreview' => 'modal-large',
                            'renderEdit'    => 'link',
                            'renderShow'    => 'link',
                        ]
                    );
                },
            ],
        ];
    }

    /**
     * @return array
     */
    function zones()
    {
        return [
            'backend' => [
                'table'   => [
                    'id'        => 'article-manage',
                    'row_index' => true,
                ],
                'columns' => [
                    'index'   => 'min-width text-right',
                    'title',
                    'author'  => 'min-width',
                    'status'  => 'min-width',
                    'actions' => 'min-width',
                ],
                'search'  => 'articles.title',
            ],
            'manage'  => [
                'table'   => [
                    'id'        => 'article-manage',
                    'row_index' => true,
                ],
                'columns' => [
                    'index'   => 'min-width text-right',
                    'title',
                    'status'  => 'min-width',
                    'actions' => 'min-width',
                ],
                'search'  => 'articles.title',
            ],
        ];
    }
}