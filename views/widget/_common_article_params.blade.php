@foreach(['title', 'image', 'author', 'datetime', 'summary', 'readmore'] as $attr)
    <div class="form-group {{ $errors->has("show_{$attr}") ? ' has-error':'' }}">
        {!! Form::label("show_{$attr}",  trans("article::widget.article.show_{$attr}"), ['class'=> 'control-label']) !!}
        <br>
        {!! Form::checkbox("show_{$attr}", 1, null,['class'=>'switch', 'data-on-text'=>trans('common.yes'), 'data-off-text'=>trans('common.no')]) !!}
        @if($errors->has("show_{$attr}"))
            <p class="help-block">{{ $errors->first("show_{$attr}") }}</p>
        @endif
    </div>
@endforeach