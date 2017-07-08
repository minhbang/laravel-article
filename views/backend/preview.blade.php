@extends('kit::backend.layouts.modal')
@section('content')
    @php /** @var \Minhbang\Article\Presenter $presenter */  @endphp
    <div class="article-page">
        <div class="meta-top">
            {{ $presenter->formatDatetime($article->updated_at) }}
        </div>
        <div class="page-header">
            <h1>{{$article->title}}</h1>
        </div>
        <p class="summary">
            {{ $article->summary }}
        </p>

        <div class="content">
            {!! $article->content !!}
        </div>
    </div>
@stop