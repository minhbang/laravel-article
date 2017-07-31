<?php namespace Minhbang\Article\Widgets;

use Minhbang\Category\Widgets\CategoryWidget;
use Minhbang\Category\Category;
use CategoryManager;
use Minhbang\Article\Article;
/**
 * Class ArticleCategoryWidget
 *
 * @package Minhbang\Article\Widgets
 */
class ArticleCategoryWidget extends CategoryWidget {
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
     * @return string
     */
    protected function formView() {
        return 'article::widget.article_category_form';
    }

    /**
     * @return array
     */
    public function dataFixed() {
        return [ 'category_type' => 'article' ];
    }

    /**
     * @param \Minhbang\Layout\Widget $widget
     *
     * @return string
     */
    protected function getCategoryTree( $widget ) {
        return ( $category = $this->getCategory( $widget ) ) ? $category->present()->tree( null, $widget->data['max_depth'] ) : '';
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
    protected function dataAttributes() {
        $dataAttributes = parent::dataAttributes();
        $dataAttributes[] = [
            'name'    => 'category_id',
            'title'   => trans( 'article::widget.latest_articles.category_id' ),
            'rule'    => 'required|integer',
            'default' => null,
        ];

        return $dataAttributes;
    }
}