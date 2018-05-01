<?php
/**
 * @var \Minhbang\Article\Article $article
 */
?>
@extends('kit::backend.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <table class="table table-hover table-striped table-bordered table-detail">
                <tr>
                    <td>{{ __('Title') }}</td>
                    <td><strong>{{ $article->title }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('Slug') }}</td>
                    <td><strong>{{ $article->slug }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('Summary') }}</td>
                    <td>{{ $article->summary }}</td>
                </tr>
                <tr>
                    <td>{{ __('Content') }}</td>
                    <td>{!! $article->content !!}</td>
                </tr>
            </table>
        </div>
        <div class="col-lg-5">
            <table class="table table-hover table-striped table-bordered table-detail">
                <tr>
                    <td>ID</td>
                    <td><strong>{{ $article->id}}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('Article type') }}</td>
                    <td><strong>{{ $typeName }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('Category') }}</td>
                    <td><strong>{{ $article->category->title }}</strong></td>
                </tr>

                <tr>
                    <td>{{ __('Tags') }}</td>
                    <td>{!! $article->present()->tagsHtml !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Status') }}</td>
                    <td>{!! $article->present()->status !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Featured image') }}</td>
                    <td>{!! $article->present()->featured_image !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Author') }}</td>
                    <td><strong>{{ $article->user->username }}</strong></td>
                </tr>
                <tr>
                    <td>{{ __('Created at') }}</td>
                    <td>{!! $article->present()->createdAt !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Updated at') }}</td>
                    <td>{!! $article->present()->updatedAt !!}</td>
                </tr>
                <tr>
                    <td>{{ __('Published at') }}</td>
                    <td>{!! $article->present()->publishedAt !!}</td>
                </tr>
            </table>
        </div>
    </div>
@stop