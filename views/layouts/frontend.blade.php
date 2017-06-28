@extends('layouts.column2')

@section('body-class', 'layout-article')

@section('sidebar')
    <div class="panel panel-primary">
        <div class="panel-heading">{{trans('category::common.category')}} {{$typeName}}</div>
        <div id="categories-tree"></div>
    </div>
@stop

@section('script')
    <script type="text/javascript">
        var category_route = '{{route('article.category', ['type' => $type, 'category' => '__ID__'])}}',
            categories_data = {!! $categoryManager->tree( isset($category) ? $category : null) !!};

        $(document).ready(function () {
            var categories_tree = $('#categories-tree');
            categories_tree.treeview({
                data: categories_data,
                //selectedBackColor: '#00c853',
                levels: 1
            });
            categories_tree.on('click', 'li', function (e) {
                e.preventDefault();
                if ($(e.target).is('.expand-icon')) {
                    return;
                }
                window.location.href = category_route.replace('__ID__', $(this).data('id'));
            });
        });
    </script>
@stop