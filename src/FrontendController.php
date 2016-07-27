<?php
namespace Minhbang\Article;

use Minhbang\Kit\Extensions\Controller;

/**
 * Class FrontendController
 *
 * @package Minhbang\Article
 */
class FrontendController extends Controller
{
    /**
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function index($slug)
    {
        // TODO: fix lỗi liên quan đến 'đã refactor Category'
        app('category-manager')->switchType($slug);
        $articles = Article::queryDefault()->withAuthor()->categorized(app('category-manager')->root)
            ->orderUpdated()->simplePaginate(7);
        return view('article::frontend.index', compact('slug', 'articles'));
    }

    /**
     * @param string $type
     * @param \Minhbang\Article\Article $article
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show_with_type($type, Article $article, $slug)
    {
        return $this->show($article, $slug);
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show($article, $slug)
    {
        if ($article->slug != $slug) {
            abort(404, trans('article::common.not_found'));
        }
        $latest = [];
        $related = [];
        $latest_title = '';
        $related_title = '';
        if ($category = $article->category) {
            $category = $category->getRoot();
            $tagNames = $article->tagNames();
            $query = Article::queryDefault()->categorized($category)->except($article->id)->recently(5);

            $latest_query = $query;
            $related_query = clone $query;

            $latest = $latest_query->orderUpdated()->get();
            $related = $related_query->withAnyTag($tagNames)->orderByMatchedTag($tagNames)->orderUpdated()->get();

            $category_type = app('category-manager')->getTypeName($category->slug);
            $latest_title = trans('common.latest_objects', ['name' => $category_type]);
            $related_title = trans('common.related_objects', ['name' => $category_type]);
        }
        return view('article::frontend.show', compact('article', 'latest', 'related', 'latest_title', 'related_title'));
    }

}
