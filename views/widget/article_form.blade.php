<?php /** @var \Minhbang\Layout\Widget $widget */ ?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($data,['class' => 'form-modal form-row-full','url' => $url, 'method' => 'put']) !!}
    <div class="row">
        <div class="col-sm-9">
            <div class="form-group {{ $errors->has("article_id") ? ' has-error':'' }}">
                {!! Form::label("article_id", $labels['article_id'], ['class' => "control-label"]) !!}
                @include('article::suport.select_article', ['name' => 'article_id', 'selected_id' => $data['article_id']])
                @if($errors->has('article_id'))
                    <p class="help-block">{{ $errors->first('article_id') }}</p>
                @endif
            </div>
            @include('layout::widgets._common_fields')
        </div>
        <div class="col-sm-3  gray-bg">
            @include('article::widget._common_article_params')
        </div>
    </div>
    {!! Form::close() !!}
@stop