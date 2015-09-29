@extends('backend.layouts.main')
@section('content')
    {!! Form::model($article, ['files' => true, 'url'=>$url, 'method' => $method]) !!}
    <div class="row">
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>{!! trans('article::common.main_info') !!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                            <div class="form-group{{ $errors->has("title") ? ' has-error':'' }}">
                                {!! Form::label("title", trans('article::common.title'), ['class' => "control-label"]) !!}
                                {!! Form::text("title", $article->title, ['class' => 'has-slug form-control','data-slug_target' => "#title-slug"]) !!}
                                @if($errors->has("title"))
                                    <p class="help-block">{{ $errors->first("title") }}</p>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has("slug") ? ' has-error':'' }}">
                                {!! Form::label("slug", trans('article::common.slug'), ['class' => "control-label"]) !!}
                                {!! Form::text("slug", $article->slug, ['id'=>"title-slug", 'class' => 'form-control']) !!}
                                @if($errors->has("slug"))
                                    <p class="help-block">{{ $errors->first("slug") }}</p>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has("summary") ? ' has-error':'' }}">
                                {!! Form::label("summary", trans('article::common.summary'), ['class' => "control-label"]) !!}
                                {!! Form::textarea("summary", $article->summary, ['class' => 'form-control']) !!}
                                @if($errors->has("summary"))
                                    <p class="help-block">{{ $errors->first("summary") }}</p>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has("content") ? ' has-error':'' }}">
                                {!! Form::label("content", trans('article::common.content'), ['class' => "control-label"]) !!}
                                {!! Form::textarea("content", $article->content, [
                                    'class' => 'form-control wysiwyg',
                                    'data-editor' => 'full',
                                    'data-height' => 500,
                                    'data-attribute' => 'content',
                                    'data-resource' => 'article',
                                    'data-id' => $article->id
                                ]) !!}
                                @if($errors->has("content"))
                                    <p class="help-block">{{ $errors->first("content") }}</p>
                                @endif
                            </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>{!! trans('article::common.additional_info') !!}</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12 col-md-7">
                            <div class="form-group{{ $errors->has('category_id') ? ' has-error':'' }}">
                                {!! Form::label('category_id', trans('category::common.category'), ['class' => 'control-label']) !!}
                                {!! Form::select('category_id', $categories, null, ['prompt' =>'', 'class' => 'form-control selectize-tree']) !!}
                                @if($errors->has('category_id'))
                                    <p class="help-block">{{ $errors->first('category_id') }}</p>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('tags', trans('article::common.tags'), ['class' => 'control-label']) !!}
                                {!! Form::text('tags', $tags, ['data-options'=>$allTags, 'prompt' =>'', 'class' => 'form-control selectize-tags']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-5">
                            <div class="form-group form-image{{ $errors->has('image') ? ' has-error':'' }}">
                                {!! Form::label('image', trans('article::common.image'), ['class' => 'control-label']) !!}
                                {!! Form::selectImage('image', ['thumbnail' => [
                                    'url' => $article->present()->imageUrl,
                                    'width' => setting('display.image_width_md'),
                                    'height' => setting('display.image_height_md')
                                ]]) !!}
                                @if($errors->has('image'))
                                    <p class="help-block">{{ $errors->first('image') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">
            <div class="form-group text-center">
                <button type="submit" class="btn btn-success" style="margin-right: 15px;">{{ trans('common.save') }}</button>
                <a href="{{ route('backend.article.index') }}" class="btn btn-white">{{ trans('common.cancel') }}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.wysiwyg').mbEditor({
                //upload image
                imageUploadURL: '{!! route('image.store') !!}',
                imageMaxSize: {{setting('system.max_image_size') * 1024 * 1024 }}, //bytes
                // load image
                imageManagerLoadURL: '{!! route('image.data') !!}',
                // custom options
                imageDeleteURL: '{!! route('image.delete') !!}'
            });
        });
    </script>
@stop
