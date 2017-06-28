<?php
namespace Minhbang\Article\Controllers\Backend;

use Minhbang\Article\Request as ArticleRequest;
use Request;
use Minhbang\Article\Article;
use CategoryManager;

/**
 * Class ManageController
 *
 * @package Minhbang\Article\Controllers
 */
class ManageController extends Controller
{
    /**
     * @var \Minhbang\Article\Presenter
     */
    protected $html;
    /**
     * @var \Minhbang\Article\Article
     */
    protected $model;
    /**
     * All article types
     *
     * @var array
     */
    protected $types;
    /**
     * @var string Current article type
     */
    protected $type;

    /**
     * ManageController constructor.
     *
     * @param \Minhbang\Article\Article $article
     */
    public function __construct(Article $article)
    {
        $this->model = $article;
        $this->types = config('article.types');
        $this->html = $this->newClassInstance(config('article.html'));
        view()->share('html', $this->html);
        parent::__construct();
    }

    /**
     * @param string $type Article type
     *
     * @return \Illuminate\View\View
     */
    public function index($type)
    {
        $this->setType($type);
        $this->getDatatable()->share();
        $typeName = $this->getCategoryManager()->title();
        $this->buildHeading(
            [trans('common.manage'), $typeName],
            'fa-newspaper-o',
            ['#' => $typeName],
            [
                [
                    route($this->route_prefix . 'manage.article.create'),
                    trans('common.create'),
                    ['type' => 'primary', 'icon' => 'plus-sign'],
                ],
            ]

        );

        return view('article::manage.index', compact('typeName'));
    }

    /**
     * Danh sách Article theo định dạng của Datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data()
    {
        $query = Article::queryDefault()->withAuthor()->orderUpdated()->categorized($this->getCategoryManager()->root());
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('articles.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('articles.updated_at', 'mb_date_vn2mysql');
        }

        return $this->getDatatable()->make($query);
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create()
    {
        $article = $this->model;
        $url = route('manage.article.store');
        $method = 'post';
        $tags = '';
        $allTags = Article::allTagNames();
        $typeName = $this->getCategoryManager()->title();
        $categories = $this->getCategoryManager()->selectize();
        $this->buildHeading(
            [trans('common.create'), $typeName],
            'plus-sign',
            [
                route('manage.article.index', ['type' => $this->getType()]) => $typeName, '#' => trans('common.create'),
            ]
        );

        return view(
            'article::manage.form',
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
        session()->flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.create_object_success', ['name' => $this->getCategoryManager()->title()]),
            ]
        );

        return redirect(route('manage.article.index', ['type' => $this->getType()]));
    }

    /**
     * Xem chi tiết Article
     *
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function show(Article $article)
    {
        $typeName = $this->getCategoryManager()->title();
        $this->buildHeading(
            [trans('common.view_detail'), $typeName],
            'list',
            [
                route('manage.article.index', ['type' => $this->getType()]) => $typeName,
                '#'                                                         => trans('common.view_detail'),
            ],
            [
                [
                    route('manage.article.edit', ['article' => $article->id]),
                    trans('common.edit'),
                    ['type' => 'primary', 'size' => 'sm', 'icon' => 'edit'],
                ],
            ]
        );

        return view('article::manage.show', compact('article', 'typeName'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function preview(Article $article)
    {
        return view('article::manage.preview', compact('article'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function edit(Article $article)
    {
        $url = route('manage.article.update', ['article' => $article->id]);
        $method = 'put';
        $tags = implode(',', $article->tagNames());
        $allTags = Article::allTagNames();
        $typeName = $this->getCategoryManager()->title();
        $categories = $this->getCategoryManager()->selectize();
        $this->buildHeading(
            [trans('common.update'), $typeName],
            'edit',
            [
                route('manage.article.index', ['type' => $this->getType()]) => $typeName,
                '#'                                                         => trans('common.edit'),
            ]
        );

        return view(
            'article::manage.form',
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
        $result = 'error';
        if (user()->can('update', $article)) {
            $article->fill($request->all());
            $article->fillFeaturedImage($request);
            $article->fillStatus($request->get('s'));
            $article->save();
            $result = 'success';
        }
        session()->flash('message', [
            'type'    => $result,
            'content' => trans("common.update_object_{$result}", ['name' => $this->getCategoryManager()->title()]),
        ]);

        return redirect(route('manage.article.index'));
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        $result = 'error';
        if (user()->can('delete', $article)) {
            $article->delete();
            $result = 'success';
        }

        return response()->json(
            [
                'type'    => $result,
                'content' => trans("common.delete_object_{$result}", ['name' => $this->getCategoryManager()->title()]),
            ]
        );
    }

    /**
     * Lưu current type vào session
     *
     * @param string $type
     */
    protected function setType($type)
    {
        if ($type && in_array($type, $this->types)) {
            $this->type = $type;
            session([$this->keyType() => $type]);
        } else {
            session()->forget($this->keyType());
            abort(404, trans('article::common.invalid_type'));
        }
    }

    /**
     * @return string
     */
    protected function getType()
    {
        if (is_null($this->type)) {
            $this->setType(session($this->keyType()));
        }

        return $this->type;
    }

    /**
     * @return string
     */
    protected function keyType()
    {
        return 'manage.article.type';
    }

    /**
     * @return \Minhbang\Category\Type
     */
    public function getCategoryManager()
    {
        return CategoryManager::of(Article::class, $this->getType());
    }

    /**
     * @return \Minhbang\Article\Datatable
     */
    protected function getDatatable()
    {
        return $this->newClassInstance(config('article.datatable'), 'manage', $this->getCategoryManager()->title(), $this->html);
    }
}
