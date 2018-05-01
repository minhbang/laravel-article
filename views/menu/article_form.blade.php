<?php
/**
 * @var \Minhbang\Menu\Menu $menu
 * @var array $params
 */
?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($params,['class' => 'form-horizontal form-modal','url' => $url, 'method' => 'put']) !!}
    <div class="form-group">
        <label class="col-xs-3 control-label">{{ __('Menu') }}</label>
        <div class="col-xs-9">
            <p class="form-control-static text-primary">{{ $menu->label }}</p>
        </div>
    </div>
    <div class="form-group {{ $errors->has("article_id") ? ' has-error':'' }}">
        {!! Form::label("article_id", $labels['article_id'], ['class' => "col-xs-3 control-label"]) !!}
        <div class="col-xs-9">
            @include('article::suport.select_article', ['name' => 'article_id', 'selected_id' => $params['article_id']])
            @if($errors->has('article_id'))
                <p class="help-block">{{ $errors->first('article_id') }}</p>
            @endif
        </div>
    </div>
    {!! Form::close() !!}
@stop