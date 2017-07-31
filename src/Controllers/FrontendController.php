<?php

namespace Minhbang\Article\Controllers;

use Minhbang\Category\Category;
use Minhbang\Kit\Extensions\Controller;
use Minhbang\Article\Article;
use CategoryManager;
use Request;

/**
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
     * Show kết quả tìm kiếm
     */
    public function search()
    {
        $q = Request::get('q');
        $articles = $q ? Article::searchKeyword($q, [''])->paginate(10) : null;

        return view('article::frontend.search', compact('articles', 'q'));
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function category($slug)
    {
        $category = Category::findBySlug($slug);
        abort_unless($category, 404, trans('category::common.not_fount'));

        return $this->showCategory($this->getBreadcrumbs($category), $category);
    }

    /**
     * @param \Minhbang\Category\Category $category
     * @param array $breadcrumbs
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function showCategory($category = null, $breadcrumbs = [])
    {
        $category = $category ?: $this->categoryManager->node();
        $query = Article::queryDefault()->isReady('read')->withAuthor()->categorized($category ?: $this->categoryManager->root())->orderUpdated();
        $latest = $query->first();
        $articles = $latest ? $query->except($latest->id)->paginate(6) : null;
        if ($breadcrumbs) {
            $this->buildBreadcrumbs($breadcrumbs);
        }
        $view = "article::frontend.index-{$category->slug}";
        $view = view()->exists($view) ? $view : 'article::frontend.index';

        return view($view, compact('latest', 'articles', 'category'));
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
        abort_unless(($article->slug == $slug) && $article->isReady('read'), 404, trans('article::common.not_found'));
        $related = $article->getRelated();

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
        $breadcrumbs = [route('article.index', ['type' => $this->type]) => $this->typeName];
        $paths = $category->getRoot1Path(['id', 'title'], ! $noArticle);
        foreach ($paths as $cat) {
            $breadcrumbs[route('article.category', ['type' => $this->type, 'category' => $cat->id])] = $cat->title;
        }
        $breadcrumbs['#'] = $noArticle ? $category->title : $article->title;

        return $breadcrumbs;
    }
}
