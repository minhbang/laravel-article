<?php

namespace Minhbang\Article\Controllers;

use CategoryManager;
use DataTables;
use Illuminate\Http\Request;
use Minhbang\Article\Article;
use Minhbang\Article\ArticleTransformer;
use Minhbang\Article\Request as ArticleRequest;
use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Minhbang\Kit\Traits\Controller\CheckDatatablesInput;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Session;
use Status;

/**
 * Class BackendController
 * Dánh cho admin quản lý Article
 *
 * @package Minhbang\Article
 */
class BackendController extends BaseController
{
    use QuickUpdateActions;
    use CheckDatatablesInput;

    /**
     * @var \Minhbang\Category\Root
     */
    public $categoryManager;

    /**
     * BackendController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->categoryManager = CategoryManager::of(Article::class);
    }

    /**
     * Phục vụ data cho selectize articles
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function select(Request $request)
    {
        return response()->json(Article::forSelectize($request->get('title'))->get()->all());
    }

    /**
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     *
     * @return \Illuminate\View\View
     */
    public function index(Builder $builder)
    {
        $typeName = $this->categoryManager->typeName();
        $this->buildHeading(
            [__('Manage'), $typeName],
            'fa-newspaper-o',
            ['#' => $typeName]
        );
        $builder->ajax(route('backend.article.data'));

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'class' => 'min-width text-center'],
            [
                'data' => 'title',
                'name' => 'title',
                'title' => __('Title'),
            ],
            [
                'data' => 'author',
                'name' => 'users.username',
                'title' => __('Author'),
                'class' => 'min-width',
            ],
            [
                'data' => 'updated_at',
                'name' => 'updated_at',
                'title' => __('Updated at'),
                'class' => 'min-width',
            ],
            [
                'data' => 'status',
                'name' => 'status',
                'title' => __('Status'),
                'class' => 'min-width',
                'orderable' => false,
                'searchable' => false,
            ],
        ])->addAction([
            'data' => 'actions',
            'name' => 'actions',
            'title' => __('Actions'),
            'class' => 'min-width',
        ]);

        return view('article::backend.index', compact('typeName', 'html'));
    }

    /**
     * Danh sách Article theo định dạng của Datatables.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $this->filterDatatablesParametersOrAbort($request);
        $query =
            Article::queryDefault()->ready('update')->withAuthor()->orderUpdated()->categorized($this->categoryManager->node());
        if ($request->has('search_form')) {
            $query = $query
                ->searchWhereBetween('articles.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('articles.updated_at', 'mb_date_vn2mysql');
        }

        return DataTables::of($query)->setTransformer(new ArticleTransformer())->make(true);
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create()
    {
        $article = new Article();
        $url = route('backend.article.store');
        $method = 'post';
        $allTags = Article::usedTagNames();
        $typeName = $this->categoryManager->typeName();
        $categories = $this->categoryManager->selectize();
        $selectize_statuses = Status::of(Article::class)->groupByLevel();
        $this->buildHeading(
            [__('Create'), $typeName],
            'plus-sign',
            [
                route('backend.article.index') => $typeName,
                '#' => __('Create'),
            ]
        );

        return view('article::backend.form', compact('article', 'url', 'method', 'allTags', 'categories', 'selectize_statuses'));
    }

    /**
     * @param \Minhbang\Article\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ArticleRequest $request)
    {
        $article = new Article();
        $article->fill($request->all());
        $article->fillFeaturedImage($request, true);
        $article->user_id = user('id');
        //$article->fillStatus($request->get('s'));
        $article->save();
        Session::flash(
            'message',
            [
                'type' => 'success',
                'content' => __('Create new <strong>:name</strong> success', ['name' => $this->categoryManager->typeName()]),
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
        $typeName = $this->categoryManager->typeName();
        $this->buildHeading(
            [__('View detail'), $typeName],
            'list',
            [
                route('backend.article.index') => $typeName,
                '#' => __('View detail'),
            ],
            [
                [
                    route('backend.article.edit', ['article' => $article->id]),
                    __('Edit'),
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
        abort_unless($article->isReady('update'), 403, __('You do not have permission to access this feature!'));
        $url = route('backend.article.update', ['article' => $article->id]);
        $method = 'put';
        $allTags = Article::usedTagNames();
        $typeName = $this->categoryManager->typeName();
        $categories = $this->categoryManager->selectize();
        $selectize_statuses = Status::of(Article::class)->groupByLevel();
        $this->buildHeading(
            [__('Update'), $typeName],
            'edit',
            [
                route('backend.article.index') => $typeName,
                '#' => __('Edit'),
            ]
        );

        return view('article::backend.form', compact('article', 'categories', 'url', 'method', 'allTags', 'selectize_statuses'));
    }

    /**
     * @param \Minhbang\Article\Request $request
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->timestamps = false; // TODO Set tạm thời
        abort_unless($article->isReady('update'), 403, __('You do not have permission to access this feature!'));
        $article->fill($request->all());
        $article->fillFeaturedImage($request, true);
        //$article->fillStatus($request->get('s'));
        $article->save();
        Session::flash(
            'message',
            [
                'type' => 'success',
                'content' => __('Update <strong>:name</strong> success', ['name' => $this->categoryManager->typeName()]),
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
        abort_unless($article->isReady('delete'), 403, __('You do not have permission to access this feature!'));
        $article->delete();

        return response()->json(
            [
                'type' => 'success',
                'content' => __('Delete <strong>:name</strong> success', ['name' => $this->categoryManager->typeName()]),
            ]
        );
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param string $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Article $article, $status)
    {
        abort_unless($article->isReady('update'), 403, __('You do not have permission to access this feature!'));
        $result = $article->update(['status' => $status]) ? 'success' : 'error';

        return response()->json(['type' => $result, 'content' => trans("common.status_{$result}")]);
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
                'label' => __('Title'),
            ],
        ];
    }
}
