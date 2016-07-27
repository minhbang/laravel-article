<?php
namespace Minhbang\Article\Controllers;

use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Minhbang\Article\Request as ArticleRequest;
use Request;
use Session;
use Response;
use View;
use Minhbang\Article\Article;
use CategoryManager;
use MenuManager;

/**
 * Class BackendController
 * Dánh cho admin quản lý Article
 *
 * @package Minhbang\Article
 */
class BackendController extends BaseController
{
    use QuickUpdateActions;
    /**
     * @var \Minhbang\Article\Html
     */
    protected $html;

    /**
     * @var \Minhbang\Article\Article
     */
    protected $model;

    /**
     * @var \Minhbang\Category\Type
     */
    public $categoryManager;
    /**
     * All article types
     *
     * @var array
     */
    protected $types;

    /**
     * BackendController constructor.
     *
     * @param \Minhbang\Article\Article $article
     */
    public function __construct(Article $article)
    {
        parent::__construct();
        $this->model = $article;
        $this->categoryManager = CategoryManager::of(Article::class);
        $this->types = config('article.types');
        
    }

    /**
     * @return \Minhbang\Article\Datatable
     */
    protected function getDatatable()
    {
        return $this->newClassInstance(config('article.datatable'), $this);
    }

    /**
     * Init web actions
     */
    protected function initWeb()
    {
        parent::initWeb();
        $this->html = $this->newClassInstance(config('article.html'));
        View::share('html', $this->html);
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->getDatatable()->share('backend');
        $typeName = $this->categoryManager->title();
        $this->buildHeading(
            [trans('common.manage'), $typeName],
            'fa-newspaper-o',
            ['#' => $typeName],
            [
                [route($this->route_prefix . 'backend.article.create'), trans('common.create'), ['type' => 'success', 'icon' => 'plus-sign']],
            ]
        );

        return view('article::backend.index', compact('typeName'));
    }


    /**
     * Danh sách Article theo định dạng của Datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $query = Article::queryDefault()->withAuthor()->orderUpdated()->categorized($this->categoryManager->root());
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('articles.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('articles.updated_at', 'mb_date_vn2mysql');
        }

        return $this->getDatatable()->make('backend', $query);
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create()
    {
        $article = $this->model;
        $url = route('backend.article.store');
        $method = 'post';
        $tags = '';
        $allTags = Article::allTagNames();
        $typeName = $this->categoryManager->title();
        $categories = $this->categoryManager->selectize();
        $this->buildHeading(
            [trans('common.create'), $typeName],
            'plus-sign',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.create'),
            ]
        );

        return view(
            'article::backend.form',
            compact('article', 'url', 'method', 'tags', 'allTags', 'categories')
        );
    }

    /**
     * @param \Minhbang\Article\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ArticleRequest $request)
    {
        $article = $this->model;
        $article->fill($request->all());
        $article->fillFeaturedImage($request);
        $article->user_id = user('id');
        $article->fillStatus($request->get('s'));
        $article->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.create_object_success', ['name' => $this->categoryManager->title()]),
            ]
        );

        return redirect(route('backend.article.index'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function show(Article $article)
    {
        $typeName = $this->categoryManager->title();
        $this->buildHeading(
            [trans('common.view_detail'), $typeName],
            'list',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.view_detail'),
            ],
            [
                [
                    route('backend.article.edit', ['article' => $article->id]),
                    trans('common.edit'),
                    ['type' => 'primary', 'size' => 'sm', 'icon' => 'edit'],
                ],
            ]
        );

        return view('article::backend.show', compact('article', 'typeName'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function preview(Article $article)
    {
        return view('article::backend.preview', compact('article'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function edit(Article $article)
    {
        $url = route('backend.article.update', ['article' => $article->id]);
        $method = 'put';
        $tags = implode(',', $article->tagNames());
        $allTags = Article::allTagNames();
        $typeName = $this->categoryManager->title();
        $categories = $this->categoryManager->selectize();
        $this->buildHeading(
            [trans('common.update'), $typeName],
            'edit',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.edit'),
            ]
        );

        return view(
            'article::backend.form',
            compact('article', 'categories', 'url', 'method', 'tags', 'allTags')
        );
    }

    /**
     * @param \Minhbang\Article\Request $request
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->fillFeaturedImage($request);
        $article->fillStatus($request->get('s'));
        $article->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.update_object_success', ['name' => $this->categoryManager->title()]),
            ]
        );

        return redirect(route('backend.article.index'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return Response::json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => $this->categoryManager->title()]),
            ]
        );
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Article $article, $status)
    {
        $result = $article->updateStatus($status, false, 'published_at') ? 'success' : 'error';

        return Response::json(['type' => $result, 'content' => trans("common.status_{$result}")]);
    }

    /**
     * Các attributes cho phép quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes()
    {
        return [
            'title' => [
                'rules' => 'required|max:255',
                'label' => trans('article::common.title'),
            ],
        ];
    }
}
