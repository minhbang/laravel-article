<?php
namespace Minhbang\Article;

use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Minhbang\Article\Request as ArticleRequest;
use Request;
use Datatable;
use Session;
use Html;

/**
 * Class BackendController
 *
 * @package Minhbang\Article
 */
class BackendController extends BaseController
{
    use QuickUpdateActions;

    /** @var  \Minhbang\Category\Manager */
    protected $typeManager;

    /**
     * @var string Loại bài viết hiện tại
     */
    protected $type;
    /**
     * @var string[] Danh sách các types hợp lệ của article
     */
    protected $types;

    /**
     * ArticleBackendController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->types = config('article.types');
        $this->switchType();
    }

    /**
     * @return string
     */
    protected function resource()
    {
        return 'article';
    }

    /**
     * @param null|string $type
     */
    protected function switchType($type = null)
    {
        $key = 'backend.article.type';
        $type = $type ?: session($key, current($this->types));
        if (!in_array($type, $this->types)) {
            Session::forget($key);
            abort(404, trans('category::type.invalid'));
        }
        session([$key => $type]);
        $this->typeManager = app('category-manager')->root($type, $key);
        $this->type = $type;
    }

    /**
     * @param string|null $type
     *
     * @return \Illuminate\View\View
     */
    public function index($type = null)
    {
        $this->switchType($type);
        $tableOptions = [
            'id'        => 'article-manage',
            'row_index' => true,
        ];
        $options = [
            'aoColumnDefs' => [
                ['sClass' => 'min-width text-right', 'aTargets' => [0]],
                ['sClass' => 'min-width', 'aTargets' => [-1, -2]],
            ],
        ];
        $table = Datatable::table()
            ->addColumn(
                '',
                trans('article::common.title'),
                trans('article::common.user'),
                trans('common.actions')
            )
            ->setOptions($options)
            ->setCustomValues($tableOptions);
        $typeName = $this->typeManager->typeName();
        $this->buildHeading(
            [trans('common.manage'), $typeName],
            'fa-newspaper-o',
            ['#' => $typeName]
        );
        $this->menuInfo($this->type);
        return view('article::backend.index', compact('tableOptions', 'options', 'table', 'typeName'));
    }


    /**
     * Danh sách Article theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var \Minhbang\Article\Article $query */
        $query = Article::queryDefault()->withAuthor()->orderUpdated()->categorized($this->typeManager->typeRoot());
        if (Request::has('search_form')) {
            $query = $query
                ->searchWhereBetween('articles.created_at', 'mb_date_vn2mysql')
                ->searchWhereBetween('articles.updated_at', 'mb_date_vn2mysql');
        }
        return Datatable::query($query)
            ->addColumn(
                'index',
                function (Article $model) {
                    return $model->id;
                }
            )
            ->addColumn(
                'title',
                function (Article $model) {
                    return Html::linkQuickUpdate(
                        $model->id,
                        $model->title,
                        [
                            'attr'  => 'title',
                            'title' => trans("article::common.title"),
                            'class' => 'w-lg',
                        ]
                    );
                }
            )
            ->addColumn(
                'author',
                function ($model) {
                    return $model->author;
                }
            )
            ->addColumn(
                'actions',
                function (Article $model) {
                    return Html::tableActions(
                        'backend.article',
                        ['article' => $model->id],
                        $model->title,
                        $this->typeManager->typeName(),
                        [
                            'renderPreview' => 'modal-large',
                            'renderEdit'    => 'link',
                            'renderShow'    => 'link',
                        ]
                    );
                }
            )
            ->searchColumns('articles.title')
            ->make();
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
        $tags = '';
        $allTags = Article::allTagNames();
        $typeName = $this->typeManager->typeName();
        $categories = $this->typeManager->selectize();
        $this->buildHeading(
            [trans('common.create'), $typeName],
            'plus-sign',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.create'),
            ]
        );
        $this->menuInfo($this->type);
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
        $article = new Article();
        $article->fill($request->all());
        $article->fillFeaturedImage($request);
        $article->user_id = user('id');
        $article->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.create_object_success', ['name' => $this->typeManager->typeName()]),
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
        $typeName = $this->typeManager->typeName();
        $this->buildHeading(
            [trans('common.view_detail'), $typeName],
            'list',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.view_detail'),
            ]
        );
        $this->menuInfo($this->type);
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
        $typeName = $this->typeManager->typeName();
        $categories = $this->typeManager->selectize();
        $this->buildHeading(
            [trans('common.update'), $typeName],
            'edit',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.edit'),
            ]
        );
        $this->menuInfo($this->type);
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
        $article->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.update_object_success', ['name' => $this->typeManager->typeName()]),
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
        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => $this->typeManager->typeName()]),
            ]
        );
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
