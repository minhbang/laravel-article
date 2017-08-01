<?php
/**
 * @var \Illuminate\Contracts\Pagination\LengthAwarePaginator $articles
 * @var string $q
 */
?>
@extends('article::layouts.frontend')

@section('content')
    <div class="content-heading">
        {{trans('common.search_result')}}: <span>{{$q}}</span>
    </div>
    {!! Form::open(['method' => 'get']) !!}
    <div class="input-group">
        {!! Form::text('q', $q, ['class' => 'form-control', 'placeholder' => trans('common.keyword').'...']) !!}
        <span class="input-group-btn"><button class="btn" type="submit"><i class="fa fa-search"></i></button></span>
    </div>
    {!! Form::close() !!}
    @if(empty($q))
        <br>
        <div class="alert alert-info">{{trans('article::common.query_empty')}}</div>
    @else
        @if(is_null($articles))
            <br>
            <div class="alert alert-warning">{{trans('article::common.not_fount')}}</div>
        @else
            <div class="articles articles-list">
                @foreach($articles as $article)
                    @include('article::frontend._article_summary', ['article' => $article, 'show_meta' => false])
                @endforeach
                <nav class="text-center">
                    {!! $articles->appends(['q' => $q])->links() !!}
                </nav>
            </div>
        @endif
    @endif
@stop