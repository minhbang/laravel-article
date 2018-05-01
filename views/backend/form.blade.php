@extends('kit::backend.layouts.master')
@section('content')
    {!! Form::model($article, ['files' => true, 'url'=>$url, 'method' => $method]) !!}
    <div class="row">
        <div class="col-lg-7">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>{!! __('Main information <small>required</small>') !!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="form-group{{ $errors->has("title") ? ' has-error':'' }}">
                        {!! Form::label("title", __('Title'), ['class' => "control-label"]) !!}
                        {!! Form::text("title", $article->title, ['class' => 'has-slug form-control','data-slug_target' => "#title-slug"]) !!}
                        @if($errors->has("title"))
                            <p class="help-block">{{ $errors->first("title") }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has("slug") ? ' has-error':'' }}">
                        {!! Form::label("slug", __('Slug'), ['class' => "control-label"]) !!}
                        {!! Form::text("slug", $article->slug, ['id'=>"title-slug", 'class' => 'form-control']) !!}
                        @if($errors->has("slug"))
                            <p class="help-block">{{ $errors->first("slug") }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has("summary") ? ' has-error':'' }}">
                        {!! Form::label("summary", __('Summary'), ['class' => "control-label"]) !!}
                        {!! Form::textarea("summary", $article->summary, ['class' => 'form-control']) !!}
                        @if($errors->has("summary"))
                            <p class="help-block">{{ $errors->first("summary") }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has("content") ? ' has-error':'' }}">
                        {!! Form::label("content", __('Content'), ['class' => "control-label"]) !!}
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
                    <h5>{!! __('Additional information') !!}</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12 col-md-7">
                            <div class="form-group{{ $errors->has('category_id') ? ' has-error':'' }}">
                                {!! Form::label('category_id', __('Category'), ['class' => 'control-label']) !!}
                                {!! Form::select('category_id', $categories, null, ['prompt' =>'', 'class' => 'form-control selectize-tree']) !!}
                                @if($errors->has('category_id'))
                                    <p class="help-block">{{ $errors->first('category_id') }}</p>
                                @endif
                            </div>
                            <div class="form-group">
                                {!! Form::label('tags', __('Tags'), ['class' => 'control-label']) !!}
                                {!! Form::text('tag_names', null, ['data-options'=>$allTags, 'prompt' =>'', 'class' => 'form-control selectize-tags']) !!}
                            </div>
                            <div class="form-group{{ $errors->has('status') ? ' has-error':'' }}">
                                {!! Form::label('status',  __('Status'), ['class'=> 'control-label']) !!}
                                {!! Form::select('status', $selectize_statuses, null, ['id' => 'selectize-status', 'class' => 'form-control']) !!}
                                @if($errors->has('status'))
                                    <p class="help-block">{{ $errors->first('status') }}</p>
                                @endif
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-5">
                            <div class="form-group form-image{{ $errors->has('image') ? ' has-error':'' }}">
                                {!! Form::label('image', __('Featured image'), ['class' => 'control-label']) !!}
                                {!! Form::selectImage('image', ['thumbnail' => [
                                    'url' => $article->featured_image_url,
                                    'width' => $article->config['featured_image']['width'],
                                    'height' => $article->config['featured_image']['height']
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
                <button type="submit" class="btn btn-success" style="margin-right: 15px;">{{ __('Save') }}</button>
                <a href="{{ route('backend.article.index') }}" class="btn btn-white">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('#selectize-status').selectize_status();
        $('.wysiwyg').mbEditor({
            //upload image
            imageUploadURL: '{!! route('image.store') !!}',
            imageMaxSize: {{setting('system.max_image_size') * 1024 * 1024 }}, //bytes
            mbButtons: {
                insertImages: {
                    url: '{!! route('image.browse') !!}',
                    label: '{{__('Insert image')}}'
                }
            }
        });
    });
</script>
@endpush
