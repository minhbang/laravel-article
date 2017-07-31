<?php

namespace Minhbang\Article\Controllers;

use Minhbang\Article\ArticleTransformer;
use Minhbang\Kit\Extensions\BackendController as BaseController;
use Minhbang\Kit\Traits\Controller\QuickUpdateActions;
use Minhbang\Article\Request as ArticleRequest;
use Request;
use Session;
use Minhbang\Article\Article;
use CategoryManager;
use Minhbang\Kit\Extensions\DatatableBuilder as Builder;
use Datatables;
use Status;

/**
 * Class BackendController
 * Dánh cho admin quản lý Article
 *
 * @package Minhbang\Article
 */
class BackendController extends BaseController {
    use QuickUpdateActions;
    /**
     * @var \Minhbang\Category\Root
     */
    public $categoryManager;

    /**
     * BackendController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->categoryManager = CategoryManager::of( Article::class );
    }

    /**
     * Phục vụ data cho selectize articles
     * @return \Illuminate\Http\JsonResponse
     */
    public function select() {
        return response()->json( Article::forSelectize( Request::get( 'title' ) )->get()->all() );
    }

    /**
     * @param \Minhbang\Kit\Extensions\DatatableBuilder $builder
     *
     * @return \Illuminate\View\View
     */
    public function index( Builder $builder ) {
        $typeName = $this->categoryManager->typeName();
        $this->buildHeading(
            [ trans( 'common.manage' ), $typeName ],
            'fa-newspaper-o',
            [ '#' => $typeName ]
        );
        $builder->ajax( route( 'backend.article.data' ) );

        $html = $builder->columns( [
            [ 'data' => 'id', 'name' => 'id', 'title' => 'ID', 'class' => 'min-width text-center' ],
            [
                'data'  => 'title',
                'name'  => 'title',
                'title' => trans( 'article::common.title' ),
            ],
            [ 'data' => 'author', 'name' => 'author', 'title' => trans( 'article::common.user' ), 'class' => 'min-width', ],
            [
                'data'  => 'updated_at',
                'name'  => 'updated_at',
                'title' => trans( 'common.updated_at' ),
                'class' => 'min-width',
            ],
            [
                'data'       => 'status',
                'name'       => 'status',
                'title'      => trans( 'common.status' ),
                'class'      => 'min-width',
                'orderable'  => false,
                'searchable' => false,
            ],
        ] )->addAction( [
            'data'  => 'actions',
            'name'  => 'actions',
            'title' => trans( 'common.actions' ),
            'class' => 'min-width',
        ] );

        return view( 'article::backend.index', compact( 'typeName', 'html' ) );
    }


    /**
     * Danh sách Article theo định dạng của Datatables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function data() {
        // TODOL giải quyết mặc định order updated: ->orderUpdated()
        $query = Article::queryDefault()->withAuthor()->orderUpdated()->categorized( $this->categoryManager->node() );
        if ( Request::has( 'search_form' ) ) {
            $query = $query
                ->searchWhereBetween( 'articles.created_at', 'mb_date_vn2mysql' )
                ->searchWhereBetween( 'articles.updated_at', 'mb_date_vn2mysql' );
        }

        return Datatables::of( $query )->setTransformer( new ArticleTransformer() )->make( true );
    }

    /**
     * @return \Illuminate\View\View
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function create() {
        $article = new Article();
        $url = route( 'backend.article.store' );
        $method = 'post';
        $allTags = Article::usedTagNames();
        $typeName = $this->categoryManager->typeName();
        $categories = $this->categoryManager->selectize();
        $selectize_statuses = Status::of( Article::class )->groupByLevel();
        $this->buildHeading(
            [ trans( 'common.create' ), $typeName ],
            'plus-sign',
            [
                route( 'backend.article.index' ) => $typeName,
                '#'                              => trans( 'common.create' ),
            ]
        );

        return view( 'article::backend.form', compact( 'article', 'url', 'method', 'allTags', 'categories', 'selectize_statuses' ) );
    }

    /**
     * @param \Minhbang\Article\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store( ArticleRequest $request ) {
        $article = new Article();
        $article->fill( $request->all() );
        $article->fillFeaturedImage( $request );
        $article->user_id = user( 'id' );
        //$article->fillStatus($request->get('s'));
        $article->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans( 'common.create_object_success', [ 'name' => $this->categoryManager->typeName() ] ),
            ]
        );

        return redirect( route( 'backend.article.index' ) );
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function show( Article $article ) {
        $typeName = $this->categoryManager->typeName();
        $this->buildHeading(
            [ trans( 'common.view_detail' ), $typeName ],
            'list',
            [
                route( 'backend.article.index' ) => $typeName,
                '#'                              => trans( 'common.view_detail' ),
            ],
            [
                [
                    route( 'backend.article.edit', [ 'article' => $article->id ] ),
                    trans( 'common.edit' ),
                    [ 'type' => 'primary', 'size' => 'sm', 'icon' => 'edit' ],
                ],
            ]
        );

        return view( 'article::backend.show', compact( 'article', 'typeName') );
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function preview( Article $article ) {
        return view( 'article::backend.preview', compact( 'article') );
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\View\View
     */
    public function edit( Article $article ) {
        $url = route( 'backend.article.update', [ 'article' => $article->id ] );
        $method = 'put';
        $allTags = Article::usedTagNames();
        $typeName = $this->categoryManager->typeName();
        $categories = $this->categoryManager->selectize();
        $selectize_statuses = Status::of( Article::class )->groupByLevel();
        $this->buildHeading(
            [ trans( 'common.update' ), $typeName ],
            'edit',
            [
                route( 'backend.article.index' ) => $typeName,
                '#'                              => trans( 'common.edit' ),
            ]
        );

        return view( 'article::backend.form', compact( 'article', 'categories', 'url', 'method', 'allTags', 'selectize_statuses' ) );
    }

    /**
     * @param \Minhbang\Article\Request $request
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update( ArticleRequest $request, Article $article ) {
        $article->fill( $request->all() );
        $article->fillFeaturedImage( $request );
        //$article->fillStatus($request->get('s'));
        $article->save();
        Session::flash(
            'message',
            [
                'type'    => 'success',
                'content' => trans( 'common.update_object_success', [ 'name' => $this->categoryManager->typeName() ] ),
            ]
        );

        return redirect( route( 'backend.article.index' ) );
    }

    /**
     * @param \Minhbang\Article\Article $article
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy( Article $article ) {
        $article->delete();

        return response()->json(
            [
                'type'    => 'success',
                'content' => trans( 'common.delete_object_success', [ 'name' => $this->categoryManager->typeName() ] ),
            ]
        );
    }

    /**
     * @param \Minhbang\Article\Article $article
     * @param string $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status( Article $article, $status ) {
        $result = $article->update( [ 'status' => $status ] ) ? 'success' : 'error';

        return response()->json( [ 'type' => $result, 'content' => trans( "common.status_{$result}" ) ] );
    }

    /**
     * Các attributes cho phép quick-update
     *
     * @return array
     */
    protected function quickUpdateAttributes() {
        return [
            'title' => [
                'rules' => 'required|max:255',
                'label' => trans( 'article::common.title' ),
            ],
        ];
    }
}
