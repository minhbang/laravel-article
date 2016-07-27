@extends('backend.layouts.modal')
@section('content')
    <div class="article-page">
        <div class="meta-top">
            {{ $article->present()->updatedAt }}
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