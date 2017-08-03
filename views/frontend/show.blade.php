<?php
/**
 * @var \Minhbang\Article\Article $article
 * @var \Minhbang\Article\Article[] $related
 */
?>
@extends('article::layouts.frontend')

@section('content')
    <div class="article-single">
        @if(($article_image = $article->present()->featured_image('full-')))
            <div class="article-image">{!! $article_image !!}</div>
        @endif
        <div class="article-title"><h3>{{$article->title}}</h3></div>
        @if(config('article.display.show_author'))
            {!! $article->present()->metaBlock( config('article.display.show_author') ? $article->author('name'): false, false) !!}
        @endif
        <div class="article-content">
            {!! $article->content !!}
        </div>
    </div>
    @if($related)
        <div class="articles articles-related">
            <div class="articles-related-title">
                <h4>{{trans('common.related_objects', ['name' => trans('article::common.article')])}}</h4>
            </div>
            <div class="row">
                @foreach($related as $a)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        @include('article::frontend._article_summary', ['article' => $a, 'show_meta' => false])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@stop