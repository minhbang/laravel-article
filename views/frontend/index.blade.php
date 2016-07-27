@extends('article::layouts.frontend')

@section('content')
    <div class="main-heading">
        <span class="text-primary">{{$typeName}}</span> {{isset($category) ? $category->title : ''}}
    </div>
    @if(is_null($articles))
        <br>
        <div class="alert alert-warning">{{trans('article::common.empty', ['type' => $typeName])}}</div>
    @else
        <div class="articles">
            {!! $html->renderFeatured($latest) !!}
            {!! $html->renderList($articles) !!}
            <nav class="text-center">
                {!! $articles->links() !!}
            </nav>
        </div>
    @endif
@stop