<?php namespace Minhbang\Article\Widgets;

use Minhbang\Category\Category;
use Minhbang\Layout\WidgetTypes\WidgetType;
use Minhbang\Article\Article;
use CategoryManager;

/**
 * Class LatestArticlesWidget
 *
 * @package Minhbang\Article\Widgets
 */
class LatestArticlesWidget extends WidgetType {
    /**
     * @param \Minhbang\Layout\Widget|string $widget
     *
     * @return string
     */
    public function titleBackend( $widget ) {
        $title = ( $category = $this->getCategory( $widget ) ) ? $category->title : $widget;

        return parent::titleBackend( $title );
    }

    /**
     * @return array
     */
    public function getCategories() {
        return CategoryManager::of( Article::class )->selectize();
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return Category|null
     */
    protected function getCategory( $widget ) {
        return empty( $widget->data['category_id'] ) ? null : Category::find( $widget->data['category_id'] );
    }

    /**
     * @return array
     */
    public function formOptions() {
        return [ 'width' => 'large' ] + parent::formOptions();
    }

    /**
     * @return string
     */
    protected function formView() {
        return 'article::widget.latest_articles_form';
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function title( $widget ) {
        $title = parent::title( $widget );
        $category = $this->getCategory( $widget );
        if ( $widget->data['show_link_category'] && $category ) {
            $title = '<a class="link-category" href="' . Article::getCategoryUrl( $category ) . '">' . $title . '</a>';
        }

        return $title;
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function content( $widget ) {
        if ( $category = $this->getCategory( $widget ) ) {
            $articles = Article::categorized( $category )->take( $widget->data['limit'] )->get();
            $limit_title = 30;
            $limit_summary = 250;

            return view( 'article::widget.latest_articles_output', compact( 'widget', 'articles', 'limit_title', 'limit_summary' ) );
        } else {
            return '';
        }
    }

    /**
     * @return array
     */
    protected function dataAttributes() {
        return [
            [ 'name' => 'category_id', 'title' => trans( 'article::widget.latest_articles.category_id' ), 'rule' => 'required|integer', 'default' => null ],
            [ 'name' => 'show_link_category', 'title' => trans( 'article::widget.latest_articles.show_link_category' ), 'rule' => 'integer', 'default' => 0 ],
            [ 'name' => 'limit', 'title' => trans( 'article::widget.latest_articles.limit' ), 'rule' => 'required|integer|min:1', 'default' => 3 ],
            [ 'name' => 'item_css', 'title' => trans( 'article::widget.latest_articles.item_css' ), 'rule' => 'max:255', 'default' => '' ],
            // Common Article Params
            [ 'name' => 'show_title', 'title' => trans( 'article::widget.article.show_title' ), 'rule' => 'integer', 'default' => 0 ],
            [ 'name' => 'show_image', 'title' => trans( 'article::widget.article.show_image' ), 'rule' => 'integer', 'default' => 0 ],
            [ 'name' => 'show_author', 'title' => trans( 'article::widget.article.show_author' ), 'rule' => 'integer', 'default' => 0 ],
            [ 'name' => 'show_datetime', 'title' => trans( 'article::widget.article.show_datetime' ), 'rule' => 'integer', 'default' => 0 ],
            [ 'name' => 'show_summary', 'title' => trans( 'article::widget.article.show_summary' ), 'rule' => 'integer', 'default' => 0 ],
            [ 'name' => 'show_readmore', 'title' => trans( 'article::widget.article.show_readmore' ), 'rule' => 'integer', 'default' => 0 ],
        ];
    }

}