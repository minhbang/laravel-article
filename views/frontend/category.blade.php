<?php
/**
 * @var \Minhbang\Category\Category $category
 * @var \Minhbang\Article\Article $latest
 * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
 */
?>
@extends('article::layouts.frontend')

@section('content')
    <div class="content-heading">{{$category->title}}</div>
    @if($latest)
        <div class="article-featured">
            @include('article::frontend._article_summary', ['article' => $latest, 'show_meta' => false, 'featured'=> true])
        </div>
    @endif
    @if($articles)
        <div class="articles">
            <div class="row">
                @foreach($articles as $article)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        @include('article::frontend._article_summary', ['article' => $article, 'show_meta' => false])
                    </div>
                @endforeach
            </div>
            <nav class="text-center">
                {!! $articles->links() !!}
            </nav>
        </div>
    @endif
@stop