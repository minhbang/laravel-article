<?php
/**
 * @var \Minhbang\Category\Category $category
 * @var \Minhbang\Article\Article $latest
 * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
 */
$items = $articles->items();
$latest = array_shift($items);
?>
@extends('article::layouts.frontend')

@section('content')
    @if($latest)
        <div class="article-featured">
            @include('article::frontend._article_summary', ['article' => $latest, 'show_meta' => config('article.display.show_time'), 'featured'=> true])
        </div>
    @endif
    @if($items)
        <div class="articles">
            <div class="row">
                @foreach($items as $article)
                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        @include('article::frontend._article_summary', ['article' => $article, 'show_meta' => config('article.display.show_time')])
                    </div>
                @endforeach
            </div>
            <nav class="text-center">
                {!! $articles->links() !!}
            </nav>
        </div>
    @endif
@stop