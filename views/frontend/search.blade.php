<?php
/**
 * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
 * @var string $q
 */
?>
@extends('article::layouts.frontend')

@section('content')
    <div class="content-heading">
        {{__('Search result')}}: <span>{{$q}}</span>
    </div>
    {!! Form::open(['method' => 'post']) !!}
    <div class="input-group">
        {!! Form::text('q', $q, ['class' => 'form-control', 'placeholder' => __('Keyword').'...']) !!}
        <span class="input-group-btn"><button class="btn" type="submit"><i class="fa fa-search"></i></button></span>
    </div>
    {!! Form::close() !!}
    @if(empty($q))
        <br>
        <div class="alert alert-info">{{__('Enter keywords to search for articles!')}}</div>
    @else
        @if(is_null($articles))
            <br>
            <div class="alert alert-warning">{{__('No articles found!')}}</div>
        @else
            <div class="articles articles-list">
                @foreach($articles as $article)
                    @include('article::frontend._article_summary', ['article' => $article, 'show_meta' => config('article.display.show_time')])
                @endforeach
                <nav class="text-center">
                    {!! $articles->appends(['q' => $q])->links() !!}
                </nav>
            </div>
        @endif
    @endif
@stop