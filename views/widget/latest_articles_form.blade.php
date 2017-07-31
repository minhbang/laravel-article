<?php /** @var \Minhbang\Layout\Widget $widget */ ?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($data,['class' => 'form-modal form-row-full','url' => $url, 'method' => 'put']) !!}
    <div class="row">
        <div class="col-sm-9">
            <div class="flex-col-inner">
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group{{ $errors->has('title') ? ' has-error':'' }}">
                            {!! Form::label('label', trans('layout::widget.title'), ['class' => 'control-label']) !!}
                            {!! Form::text('title', null, ['class' => 'form-control']) !!}
                            <p class="help-block">{!! $errors->has('title') ? $errors->first('title'): trans('layout::widget.title_hint')  !!}</p>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group {{ $errors->has('show_link_category') ? ' has-error':'' }}">
                            {!! Form::label('show_link_category',  $labels['show_link_category'], ['class'=> 'control-label']) !!}
                            <br>
                            {!! Form::checkbox('show_link_category', 1, null,['class'=>'switch', 'data-on-text'=>trans('common.yes'), 'data-off-text'=>trans('common.no')]) !!}
                            @if($errors->has('show_link_category'))
                                <p class="help-block">{{ $errors->first('show_link_category') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('subtitle') ? ' has-error':'' }}">
                    {!! Form::label('label', trans('layout::widget.subtitle'), ['class' => 'control-label']) !!}
                    {!! Form::text('subtitle', null, ['class' => 'form-control']) !!}
                    @if($errors->has('subtitle'))
                        <p class="help-block">{{ $errors->first('subtitle') }}</p>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('css') ? ' has-error':'' }}">
                    {!! Form::label('label', trans('layout::widget.css'), ['class' => 'control-label']) !!}
                    {!! Form::text('css', null, ['class' => 'form-control']) !!}
                    @if($errors->has('css'))
                        <p class="help-block">{{ $errors->first('css') }}</p>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group {{ $errors->has("category_id") ? ' has-error':'' }}">
                            {!! Form::label("category_id", $labels['category_id'], ['class' => "control-label"]) !!}
                            {!! Form::select('category_id', $widget->typeInstance()->getCategories(), null, ['prompt' =>trans( 'category::common.select_category' ), 'class' => 'form-control selectize-tree']) !!}
                            @if($errors->has('category_id'))
                                <p class="help-block">{{ $errors->first('category_id') }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group{{ $errors->has('limit') ? ' has-error':'' }}">
                            {!! Form::label('label', $labels['limit'], ['class' => 'control-label']) !!}
                            {!! Form::text('limit', null, ['class' => 'form-control']) !!}
                            @if($errors->has('limit'))
                                <p class="help-block">{{ $errors->first('limit') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('item_css') ? ' has-error':'' }}">
                    {!! Form::label('label', $labels['item_css'], ['class' => 'control-label']) !!}
                    {!! Form::text('item_css', null, ['class' => 'form-control']) !!}
                    <p class="help-block">{!! $errors->has('item_css') ? $errors->first('item_css'): trans('article::widget.latest_articles.item_css_hint')  !!}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3 gray-bg">
            @include('article::widget._common_article_params')
        </div>
    </div>
    {!! Form::close() !!}
@stop