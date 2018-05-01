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
        return [
            'default' => __('Default'),
            'style1' => __('Style 1'),
            'style2' => __('Style 2'),
            'style3' => __('Style 3'),
            'slider' => __('Slider'),
        ];
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
            $title = '<a href="' . Article::getCategoryUrl($category) . '">' . $title . '</a>';
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
            if ( ! view()->exists($view)) {
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
                'title' => __('Category'),
                'rule' => 'required|integer',
                'default' => null,
            ],
            [
                'name' => 'show_link_category',
                'title' => __('Show category link'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'limit',
                'title' => __('Articles limit'),
                'rule' => 'required|integer|min:1',
                'default' => 3,
            ],
            [
                'name' => 'item_css',
                'title' => __('Item CSS'),
                'rule' => 'max:255',
                'default' => '',
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
            [
                'name' => 'category_title',
                'title' => __('Title is category title?'),
                'rule' => 'integer',
                'default' => 0,
            ],
            [
                'name' => 'template',
                'title' => __('Template'),
                'rule' => 'required',
                'default' => 'default',
            ],
        ];
    }
}