<?php namespace Minhbang\Article\Widgets;

use Minhbang\Article\Article;
use Minhbang\Layout\WidgetTypes\WidgetType;

/**
 * Class ArticleWidget
 *
 * @package Minhbang\Article\Widgets
 */
class ArticleWidget extends WidgetType
{
    /**
     * @param \Minhbang\Layout\Widget|string $widget
     *
     * @return string
     */
    public function titleBackend($widget)
    {
        $title = ($article = $this->getArticle($widget)) ? $article->title : $widget;

        return parent::titleBackend($title);
    }

    /**
     * @return array
     */
    public function formOptions()
    {
        return ['width' => 'large'] + parent::formOptions();
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return Article|null
     */
    protected function getArticle($widget)
    {
        return empty($widget->data['article_id']) ? null : Article::find($widget->data['article_id']);
    }

    /**
     * @return string
     */
    protected function formView()
    {
        return 'article::widget.article_form';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function content($widget)
    {
        $article = $this->getArticle($widget);
        $limit_title = -1;
        $limit_summary = -1;

        return view('article::widget.article_output', compact('widget', 'article', 'limit_title', 'limit_summary'))->render();
    }

    /**
     * @return array
     */
    protected function dataAttributes()
    {
        return [
            [
                'name' => 'article_id',
                'title' => __('Article'),
                'rule' => 'required|integer',
                'default' => null,
            ],
            // Common Article Params
            [
                'name' => 'show_title',
                'title' => __('Show article title?'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_image',
                'title' => __('Show article featured image?'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_author',
                'title' => __('Show article author?'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_datetime',
                'title' => __('Show article datetime?'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_summary',
                'title' => __('Show artile summary?'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_readmore',
                'title' => __('Show readmore link?'),
                'rule' => 'integer',
                'default' => 0,
            ],
        ];
    }
}