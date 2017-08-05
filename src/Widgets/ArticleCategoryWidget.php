<?php namespace Minhbang\Article\Widgets;

use Minhbang\Category\Widgets\CategoryWidgetType;
use Minhbang\Category\Category;
use CategoryManager;
use Minhbang\Article\Article;

/**
 * Class ArticleCategoryWidget
 *
 * @package Minhbang\Article\Widgets
 */
class ArticleCategoryWidget extends CategoryWidgetType
{
    protected function categoryType()
    {
        return Article::class;
    }
}