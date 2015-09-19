<?php
namespace Minhbang\LaravelArticle;

use Minhbang\LaravelKit\Extensions\BackendController;
use Conner\Tagging\Tag;
use Minhbang\LaravelKit\Traits\Controller\QuickUpdateActions;
use Request;
use Datatable;
use Session;
use Html;

class ArticleBackendController extends BackendController
{
    use QuickUpdateActions;

    /** @var  \Minhbang\LaravelCategory\Category */
    protected $typeManager;

    /**
     * ArticleBackendController constructor.
     */
    public function __construct()
    {
        parent::__construct(config('article.middlewares.backend'));
        $this->typeManager = app('category')->manage('article-backend');
    }

    /**
     * Danh sách Article theo định dạng của Datatables.
     *
     * @return \Datatable JSON
     */
    public function data()
    {
        /** @var \Minhbang\LaravelArticle\Article $query */
        $query = Article::queryDefault()->withAuthor()->orderUpdated()->categorized($this->typeManager->root);
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
                        'backend/article',
                        $model->id,
                        $model->title,
                        $this->typeManager->getTypeName(),
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
     * @param string|null $type
     * @return \Illuminate\View\View
     */
    public function index($type = null)
    {
        if ($type) {
            $this->typeManager->switchType($type);
        }
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
        $typeName = $this->typeManager->getTypeName();
        $this->buildHeading(
            [trans('common.manage'), $typeName],
            'fa-newspaper-o',
            ['#' => $typeName]
        );
        $this->menuInfo($this->typeManager->getTypeSlug());
        return view('article::backend.index', compact('tableOptions', 'options', 'table', 'typeName'));
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
        $allTags = implode(',', Tag::lists('name')->all());
        $typeName = $this->typeManager->getTypeName();
        $categories = $this->typeManager->selectize();
        $this->buildHeading(
            [trans('common.create'), $typeName],
            'plus-sign',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.create'),
            ]
        );
        $this->menuInfo($this->typeManager->getTypeSlug());
        return view(
            'article::backend.form',
            compact('article', 'url', 'method', 'tags', 'allTags', 'categories')
        );
    }

    /**
     * @param \Minhbang\LaravelArticle\ArticleRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(ArticleRequest $request)
    {
        $article = new Article();
        $article->fill($request->all());
        $article->fillImage($request);
        $article->user_id = user('id');
        $article->save();
        $article->fillTags($request);
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.create_object_success', ['name' => $this->typeManager->getTypeName()]),
            ]
        );
        return redirect(route('backend.article.index'));
    }

    /**
     * @param \Minhbang\LaravelArticle\Article $article
     * @return \Illuminate\View\View
     */
    public function show(Article $article)
    {
        $typeName = $this->typeManager->getTypeName();
        $this->buildHeading(
            [trans('common.view_detail'), $typeName],
            'list',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.view_detail'),
            ]
        );
        $this->menuInfo($this->typeManager->getTypeSlug());
        return view('article::backend.show', compact('article', 'typeName'));
    }

    /**
     * @param \Minhbang\LaravelArticle\Article $article
     * @return \Illuminate\View\View
     */
    public function preview(Article $article)
    {
        return view('article::backend.preview', compact('article'));
    }

    /**
     * @param \Minhbang\LaravelArticle\Article $article
     * @return \Illuminate\View\View
     */
    public function edit(Article $article)
    {
        $url = route('backend.article.update', ['article' => $article->id]);
        $method = 'put';
        $tags = implode(',', $article->tagNames());
        $allTags = implode(',', Tag::lists('name')->all());
        $typeName = $this->typeManager->getTypeName();
        $categories = $this->typeManager->selectize();
        $this->buildHeading(
            [trans('common.update'), $typeName],
            'edit',
            [
                route('backend.article.index') => $typeName,
                '#'                            => trans('common.edit'),
            ]
        );
        $this->menuInfo($this->typeManager->getTypeSlug());
        return view(
            'article::backend.form',
            compact('article', 'categories', 'url', 'method', 'tags', 'allTags')
        );
    }

    /**
     * @param \Minhbang\LaravelArticle\ArticleRequest $request
     * @param \Minhbang\LaravelArticle\Article $article
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->fillImage($request);
        $article->save();
        $article->fillTags($request);
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans('common.update_object_success', ['name' => $this->typeManager->getTypeName()]),
            ]
        );
        return redirect(route('backend.article.index'));
    }

    /**
     * @param \Minhbang\LaravelArticle\Article $article
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => $this->typeManager->getTypeName()]),
            ]
        );
    }

    /**
     * Các attributes cho phéo quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes()
    {
        return [
            'title' => [
                'rules' => 'required|max:255',
                'label' => trans('article::common.title')
            ],
        ];
    }
}
