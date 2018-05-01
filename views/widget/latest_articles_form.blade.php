<?php /** @var \Minhbang\Layout\Widget $widget */ ?>
@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($data,['class' => 'form-modal form-row-full','url' => $url, 'method' => 'put']) !!}
    <div class="row">
        <div class="col-sm-9">
            <div class="flex-col-inner">
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group {{ $errors->has("category_id") ? ' has-error':'' }}">
                            {!! Form::label("category_id", $labels['category_id'], ['class' => "control-label"]) !!}
                            {!! Form::select('category_id', $widget->typeInstance()->getCategories(), null, ['prompt' =>__('Select category...' ), 'class' => 'form-control selectize-tree']) !!}
                            @if($errors->has('category_id'))
                                <p class="help-block">{{ $errors->first('category_id') }}</p>
                            @endif
                        </div>
                        @include('layout::widgets._common_fields', ['disabled_title' => $data['category_title']])
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group{{ $errors->has('limit') ? ' has-error':'' }}">
                            {!! Form::label('label', $labels['limit'], ['class' => 'control-label']) !!}
                            {!! Form::text('limit', null, ['class' => 'form-control']) !!}
                            @if($errors->has('limit'))
                                <p class="help-block">{{ $errors->first('limit') }}</p>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('category_title') ? ' has-error':'' }}">
                            {!! Form::label('category_title',  $labels['category_title'], ['class'=> 'control-label']) !!}
                            <br>
                            {!! Form::checkbox('category_title', 1, null,['class'=>'switch', 'data-on-text'=>__('Yes'), 'data-off-text'=>__('No')]) !!}
                            @if($errors->has('category_title'))
                                <p class="help-block">{{ $errors->first('category_title') }}</p>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('show_link_category') ? ' has-error':'' }}">
                            {!! Form::label('show_link_category',  $labels['show_link_category'], ['class'=> 'control-label']) !!}
                            <br>
                            {!! Form::checkbox('show_link_category', 1, null,['class'=>'switch', 'data-on-text'=>__('Yes'), 'data-off-text'=>__('No')]) !!}
                            @if($errors->has('show_link_category'))
                                <p class="help-block">{{ $errors->first('show_link_category') }}</p>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('template') ? ' has-error':'' }}">
                            {!! Form::label('template',  $labels['template'], ['class'=> 'control-label']) !!}
                            {!! Form::select('template', $widget->typeInstance()->getTemplates(), null, ['class' => 'form-control selectize']) !!}
                            @if($errors->has('template'))
                                <p class="help-block">{{ $errors->first('template') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="form-group{{ $errors->has('item_css') ? ' has-error':'' }}">
                    {!! Form::label('label', $labels['item_css'], ['class' => 'control-label']) !!}
                    {!! Form::text('item_css', null, ['class' => 'form-control']) !!}
                    <p class="help-block">{!! $errors->has('item_css') ? $errors->first('item_css'): __('Separated with <code>|</code>, will repeat, exp: <code>col-md-4 wow fadeInLeft|col-md-4 wow zoomIn|col-md-4 wow fadeInRight</code>')  !!}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3 gray-bg">
            @include('article::widget._common_article_params')
        </div>
    </div>
    {!! Form::close() !!}
@stop
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $('input[name="category_title"]').on('switchChange.bootstrapSwitch', function (event, state) {
                $('input[name="title"]').attr('disabled', state ? 'disabled' : null);
            });
        });
    </script>
@endpush