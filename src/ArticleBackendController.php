<?php
namespace Minhbang\LaravelCategory;

use Minhbang\LaravelKit\Extensions\BackendController;
use Request;

class CategoryController extends BackendController
{
    /**
     * Quản lý category
     *
     * @var \Minhbang\LaravelCategory\Category
     */
    protected $manager;

    public function __construct()
    {
        parent::__construct(config('category.middlewares'));
        $this->manager = app('category');
    }

    /**
     * @param string|null $type
     * @return \Illuminate\View\View
     */
    public function index($type = null)
    {
        if ($type) {
            $this->manager->switchType($type);
        }
        $max_depth = $this->manager->max_depth;
        $nestable = $this->manager->nestable();
        $types = $this->manager->types;
        $current = $this->manager->root->slug;
        $this->buildHeading(
            [trans('category::common.manage'), "[{$types[$current]}]"],
            'fa-sitemap',
            ['#' => trans('category::common.category')]
        );
        return view('category::index', compact('max_depth', 'nestable', 'types', 'current'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return $this->_create();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Illuminate\View\View
     */
    public function createChildOf(CategoryItem $category)
    {
        return $this->_create($category);
    }

    /**
     * @param null|\Minhbang\LaravelCategory\CategoryItem $parent
     * @return \Illuminate\View\View
     */
    protected function _create($parent = null)
    {
        if ($parent) {
            $parent_title = $parent->title;
            $url = route('backend.category.storeChildOf', ['category' => $parent->id]);
        } else {
            $parent_title = '- ROOT -';
            $url = route('backend.category.store');
        }
        $category = new CategoryItem();
        $method = 'post';
        return view(
            'category::form',
            compact('parent_title', 'url', 'method', 'category')
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\LaravelCategory\CategoryItemRequest $request
     * @return \Illuminate\View\View
     */
    public function store(CategoryItemRequest $request)
    {
        return $this->_store($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\LaravelCategory\CategoryItemRequest $request
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Illuminate\View\View
     */
    public function storeChildOf(CategoryItemRequest $request, CategoryItem $category)
    {
        return $this->_store($request, $category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Minhbang\LaravelCategory\CategoryItemRequest $request
     * @param null|\Minhbang\LaravelCategory\CategoryItem $parent
     * @return \Illuminate\View\View
     */
    public function _store($request, $parent = null)
    {
        $category = new CategoryItem();
        $category->fill($request->all());
        $category->save();
        if ($parent) {
            $category->makeChildOf($parent);
        } else {
            $category->makeChildOf($this->manager->root);
        }
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.create_object_success', ['name' => trans('category::common.item')])
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Illuminate\View\View
     */
    public function show(CategoryItem $category)
    {
        return view('category::show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Illuminate\View\View
     */
    public function edit(CategoryItem $category)
    {
        $parent_title = $category->isRoot() ? '- ROOT -' : $category->parent->title;
        $url = route('backend.category.update', ['category' => $category->id]);
        $method = 'put';
        return view('category::form', compact('parent_title', 'url', 'method', 'category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Minhbang\LaravelCategory\CategoryItemRequest $request
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Illuminate\View\View
     */
    public function update(CategoryItemRequest $request, CategoryItem $category)
    {
        $category->fill($request->all());
        $category->save();
        return view(
            '_modal_script',
            [
                'message'    => [
                    'type'    => 'success',
                    'content' => trans('common.update_object_success', ['name' => trans('category::common.item')])
                ],
                'reloadPage' => true,
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Minhbang\LaravelCategory\CategoryItem $category
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(CategoryItem $category)
    {
        $category->delete();
        return response()->json(
            [
                'type'    => 'success',
                'content' => trans('common.delete_object_success', ['name' => trans('category::common.category')]),
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Laracasts\Presenter\Exceptions\PresenterException
     */
    public function data()
    {
        return response()->json(['html' => $this->manager->nestable()]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function move()
    {
        if ($category = $this->getNode('element')) {
            if ($leftNode = $this->getNode('left')) {
                $category->moveToRightOf($leftNode);
            } else {
                if ($rightNode = $this->getNode('right')) {
                    $category->moveToLeftOf($rightNode);
                } else {
                    if ($destNode = $this->getNode('parent')) {
                        $category->makeChildOf($destNode);
                    } else {
                        return $this->dieAjax();
                    }
                }
            }
            return response()->json(
                [
                    'type'    => 'success',
                    'content' => trans('common.order_object_success', ['name' => trans('category::common.item')]),
                ]
            );
        } else {
            return $this->dieAjax();
        }
    }

    /**
     * @param string $name
     * @return null|\Minhbang\LaravelCategory\CategoryItem
     */
    protected function getNode($name)
    {
        $id = Request::input($name);
        if ($id) {
            if ($node = CategoryItem::find($id)) {
                return $node;
            } else {
                return $this->dieAjax();
            }
        } else {
            return null;
        }
    }

    /**
     * Kết thúc App, trả về message dạng JSON
     *
     * @return mixed
     */
    protected function dieAjax()
    {
        return die(json_encode(
            [
                'type'    => 'error',
                'content' => trans('category::common.not_found')
            ]
        ));
    }
}
