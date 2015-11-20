@extends('backend.layouts.main')
@section('content')
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
        <td>{!! $article->present()->tagsHtml !!}</td>
    </tr>
    <tr>
        <td>{{ trans('article::common.featured_image') }}</td>
        <td>{!! $article->present()->featured_image !!}</td>
    </tr>
    <tr>
        <td>{{ trans('article::common.user') }}</td>
        <td><strong>{{ $article->user->username }}</strong></td>
    </tr>
    <tr>
        <td>{{ trans('common.created_at') }}</td>
        <td>{!! $article->present()->createdAt !!}</td>
    </tr>
    <tr>
        <td>{{ trans('common.updated_at') }}</td>
        <td>{!! $article->present()->updatedAt !!}</td>
    </tr>
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
@stop