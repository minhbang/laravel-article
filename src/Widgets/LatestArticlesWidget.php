<?php namespace Minhbang\Article\Widgets;

use CategoryManager;
use Minhbang\Article\Article;
use Minhbang\Category\Category;
use Minhbang\Layout\WidgetTypes\WidgetType;

/**
 * Class LatestArticlesWidget
 *
 * @package Minhbang\Article\Widgets
 */
class LatestArticlesWidget extends WidgetType
{
    /**
     * Danh sách templates
     *
     * @return array
     */
    public function getTemplates()
    {
        return trans('article::widget.latest_articles.templates');
    }

    /**
     * @param \Minhbang\Layout\Widget|string $widget
     *
     * @return string
     */
    public function titleBackend($widget)
    {
        $title = $widget->title ?
            $widget->title : (($category = $this->getCategory($widget)) ? $category->title : $widget);

        return parent::titleBackend($title);
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return CategoryManager::of(Article::class)->selectize();
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
     * @return string
     */
    protected function before($widget)
    {
        return "<!--Begin Widget--><div id=\"{$widget->type}-{$widget->id}\" class=\"widget widget-{$widget->type} {$widget->data['template']} {$widget->css}\">";
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return Category|null
     */
    protected function getCategory($widget)
    {
        return empty($widget->data['category_id']) ? null : Category::find($widget->data['category_id']);
    }

    /**
     * @return string
     */
    protected function formView()
    {
        return 'article::widget.latest_articles_form';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function title($widget)
    {
        $category = $this->getCategory($widget);
        $title = parent::title($widget->data['category_title'] && $category ? $category->title : $widget);
        if ($widget->data['show_link_category'] && $category) {
            $title = '<a href="'.Article::getCategoryUrl($category).'">'.$title.'</a>';
        }

        return $title;
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function content($widget)
    {
        if ($category = $this->getCategory($widget)) {
            $articles =
                Article::ready('read')->categorized($category)->orderUpdated()->take($widget->data['limit'])->get();
            $limit_title = setting('display.title_limit', 60);
            $limit_summary = setting('display.summary_limit', 500);

            $view = "article::widget.latest_articles_output_{$widget->data['template']}";
            if (! view()->exists($view)) {
                $view = 'article::widget.latest_articles_output';
            }

            return view($view, compact('widget', 'articles', 'limit_title', 'limit_summary'))->render();
        } else {
            return '';
        }
    }

    /**
     * @return array
     */
    protected function dataAttributes()
    {
        return [
            [
                'name' => 'category_id',
                'title' => trans('article::widget.latest_articles.category_id'),
                'rule' => 'required|integer',
                'default' => null,
            ],
            [
                'name' => 'show_link_category',
                'title' => trans('article::widget.latest_articles.show_link_category'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'limit',
                'title' => trans('article::widget.latest_articles.limit'),
                'rule' => 'required|integer|min:1',
                'default' => 3,
            ],
            [
                'name' => 'item_css',
                'title' => trans('article::widget.latest_articles.item_css'),
                'rule' => 'max:255',
                'default' => '',
            ],
            // Common Article Params
            [
                'name' => 'show_title',
                'title' => trans('article::widget.article.show_title'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_image',
                'title' => trans('article::widget.article.show_image'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_author',
                'title' => trans('article::widget.article.show_author'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_datetime',
                'title' => trans('article::widget.article.show_datetime'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_summary',
                'title' => trans('article::widget.article.show_summary'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'show_readmore',
                'title' => trans('article::widget.article.show_readmore'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'category_title',
                'title' => trans('article::widget.latest_articles.category_title'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'template',
                'title' => trans('article::widget.latest_articles.template'),
                'rule' => 'required',
                'default' => 'default',
            ],
        ];
    }
}