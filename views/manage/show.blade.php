@extends('manage.layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-7">
            <table class="table table-hover table-striped table-bordered table-detail">
                <tr>
                    <td>{{ trans('article::common.title') }}</td>
                    <td><strong>{{ $article->title }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('article::common.slug') }}</td>
                    <td><strong>{{ $article->slug }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('article::common.summary') }}</td>
                    <td>{{ $article->summary }}</td>
                </tr>
                <tr>
                    <td>{{ trans('article::common.content') }}</td>
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
                    <td>{{ trans('article::common.type') }}</td>
                    <td><strong>{{ $typeName }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('article::common.category_id') }}</td>
                    <td><strong>{{ $article->category->title }}</strong></td>
                </tr>

                <tr>
                    <td>{{ trans('article::common.tags') }}</td>
                    <td>{!! $html->tagsHtml($article) !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('common.status') }}</td>
                    <td>{!! $html->statusFormatted($article) !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('article::common.featured_image') }}</td>
                    <td>{!! $html->featured_image($article) !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('article::common.user') }}</td>
                    <td><strong>{{ $article->user->username }}</strong></td>
                </tr>
                <tr>
                    <td>{{ trans('common.created_at') }}</td>
                    <td>{!! $html->createdAt($article) !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('common.updated_at') }}</td>
                    <td>{!! $html->updatedAt($article) !!}</td>
                </tr>
                <tr>
                    <td>{{ trans('common.published_at') }}</td>
                    <td>{!! $html->publishedAt($article) !!}</td>
                </tr>
            </table>
        </div>
    </div>
@stop