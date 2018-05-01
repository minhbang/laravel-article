<?php

namespace Minhbang\Article\Controllers;

use CategoryManager;
use Illuminate\Http\Request;
use Minhbang\Article\Article;
use Minhbang\Category\Category;
use Minhbang\Kit\Extensions\Controller;

/**
 * Todo: tag action
 * Class FrontendController
 *
 * @package Minhbang\Article
 */
class FrontendController extends Controller
{
    /**
     * @var \Minhbang\Category\Root
     */
    public $categoryManager;

    /**
     * FrontendController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryManager = CategoryManager::of(Article::class);
    }

    /**
     * @return string
     */
    public function tag()
    {
        return '';
    }

    /**
     * Kết quả tìm kiếm
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        $q = $request->get('q');
        $articles = $q ? Article::ready('read')->queryDefault()->withAuthor()->searchKeyword($q, [
            'title',
            'slug',
            'summary',
            'content',
        ])->paginate(5) : null;
        $this->buildHeading(__('Search'), 'fa-search', ['#' => __('Search')]);

        return view('article::frontend.search', compact('articles', 'q'));
    }

    /**
     * Danh mục bài viết
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function category($slug)
    {
        abort_unless($slug && ($category = Category::findBySlug($slug)), 404, __('Category not found.'));
        CategoryManager::current($category);
        $articles =
            Article::queryDefault()->ready('read')->withAuthor()->categorized($category)->orderUpdated()->paginate(setting('display.category_page_limit', 7));
        $this->buildHeading($category->title, 'fa-sitemap', $this->getBreadcrumbs($category));
        $view = "article::frontend.category-{$category->slug}";
        $view = view()->exists($view) ? $view : 'article::frontend.category';

        return view($view, compact('articles', 'category'));
    }

    /**
     * Xem $article
     *
     * @param \Minhbang\Article\Article $article
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function show(Article $article, $slug)
    {
        abort_unless(($article->slug == $slug) && $article->isReady('read'), 404, __('No articles found!'));
        $related = $article->getRelated();
        $breadcrumbs =
            $article->category ? $this->buildBreadcrumbs($this->getBreadcrumbs($article->category, $article)) : [];
        $this->buildHeading($article->title, 'fa-newspaper-o', $breadcrumbs);
        $article->updateHit();

        return view('article::frontend.show', compact('article', 'latest', 'related'));
    }

    /**
     * Tạo $breadcrumbs từ $category path
     *
     * @param \Minhbang\Category\Category $category
     * @param \Minhbang\Article\Article $article
     *
     * @return array
     */
    public function getBreadcrumbs($category, $article = null)
    {
        $noArticle = is_null($article);
        $breadcrumbs = [];
        $paths = $category->getRoot1Path(['slug', 'title'], ! $noArticle);
        foreach ($paths as $cat) {
            $breadcrumbs[route('article.category', ['slug' => $cat->slug])] = $cat->title;
        }
        $breadcrumbs['#'] = $noArticle ? $category->title : $article->title;

        return $breadcrumbs;
    }
}
