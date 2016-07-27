@extends('article::layouts.frontend')

@section('content')
    <div class="article">
        <div class="title">{{$article->title}}</div>
        {!! $html->metaBlock($article, $article->author('name'), false) !!}
        <div class="content">
            {!! $article->content !!}
        </div>
    </div>
    {!! $html->renderListLink($related, trans('common.related_objects', ['name' => $typeName])) !!}
@stop