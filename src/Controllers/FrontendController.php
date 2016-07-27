<?php
namespace Minhbang\Article\Controllers;

use Minhbang\Category\Category;
use Minhbang\Kit\Extensions\Controller;
use View;
use Minhbang\Article\Article;
use CategoryManager;
/**
 * Class FrontendController
 *
 * @package Minhbang\Article
 */
class FrontendController extends Controller
{
    /**
     * All article types
     *
     * @var array
     */
    protected $types;
    protected $type;
    protected $typeName;
    /**
     * @var \Minhbang\Category\Type
     */
    public $categoryManager;
    /**
     * FrontendController constructor.
     */
    public function __construct()
    {
        $this->types = config('article.types');
        parent::__construct();
        $this->categoryManager = CategoryManager::of(Article::class);
        View::share('categoryManager', $this->categoryManager);
        View::share('type', $this->type);
        View::share('typeName', $this->typeName);
        View::share('html', $this->newClassInstance(config('article.html')));
    }

    /**
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function index($slug)
    {
        $this->switchCategoryType($slug);

        return $this->showCategory($slug);
    }

    /**
     * @param string $slug
     * @param \Minhbang\Category\Category $category
     *
     * @return \Illuminate\View\View
     */
    public function category($slug, Category $category)
    {
        $this->switchCategoryType($slug);

        return $this->showCategory($slug, $this->getBreadcrumbs($category), $category);
    }

    /**
     * @param string $type
     * @param array $breadcrumbs
     * @param \Minhbang\Category\Category $category
     *
     * @return \View
     */
    protected function showCategory($type, $breadcrumbs = [], $category = null)
    {
        $query = Article::queryDefault()
            ->published()
            ->withAuthor()
            ->categorized($category ?: $this->categoryManager->root())
            ->orderUpdated();
        $latest = $query->first();
        $articles = $latest ? $query->except($latest->id)->paginate(6) : null;
        if ($breadcrumbs) {
            $this->buildBreadcrumbs($breadcrumbs);
        }
        $view = "article::frontend.index-{$type}";
        $view = view()->exists($view) ? $view : 'article::frontend.index';

        return view($view, compact('latest', 'articles', 'category'));
    }

    /**
     * Xem $article
     * @param \Minhbang\Article\Article $article
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function show($article, $slug)
    {
        abort_unless(($article->slug == $slug) && $article->isPublished(), 404, trans('article::common.not_found'));
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
        $paths = $category->getRoot1Path(['id', 'title'], !$noArticle);
        foreach ($paths as $cat) {
            $breadcrumbs[route('article.category', ['type' => $this->type, 'category' => $cat->id])] = $cat->title;
        }
        $breadcrumbs['#'] = $noArticle ? $category->title : $article->title;

        return $breadcrumbs;
    }
}
