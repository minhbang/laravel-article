<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Article\Article $article
 * @var int $limit_title
 * @var int $limit_summary
 */
?>
@if($article)
    {!! $widget->data['show_readmore'] ? '':'<a href="'.$article->url.'">' !!}
    @if($widget->data['show_image'])
        <div class="article-image">{!! $article->present()->featured_image !!}</div>
    @endif
    @if($widget->data['show_title'])
        <h3 class="article-title">{{mb_string_limit($article->title, $limit_title)}}</h3>
    @endif
    @if($widget->data['show_datetime'] || $widget->data['show_author'])
        <div class="article-meta">
            @if($widget->data['show_datetime'])
                <span class="article-datetime">{!! $article->present()->updatedAt !!}</span>
            @endif
            @if($widget->data['show_author'])
                <span class="article-author">{!! $article->present()->author !!}</span>
            @endif
        </div>
    @endif
    @if($widget->data['show_summary'])
        <div class="article-summary">{{mb_string_limit($article->summary, $limit_summary)}}</div>
    @endif
    @if($widget->data['show_readmore'])
        <div class="article-readmore"><a href="{{$article->url}}">{{trans( 'common.read_more' )}} »</a></div>
    @endif
    {!! $widget->data['show_readmore'] ? '':'</a>' !!}
@endif