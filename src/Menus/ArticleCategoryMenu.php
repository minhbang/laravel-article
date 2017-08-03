<?php namespace Minhbang\Article\Menus;

use Minhbang\Category\Menus\CategoryMenuType;
use Minhbang\Article\Article;

/**
 * Class ArticleCategoryMenu
 *
 * @package Minhbang\Article\Menus
 */
class ArticleCategoryMenu extends CategoryMenuType
{
    /**
     * @return string
     */
    protected function categoryType()
    {
        return Article::class;
    }
}