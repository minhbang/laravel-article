<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Article\Article $article
 * @var int $limit_title
 * @var int $limit_summary
 */
$default = ['featured_image_ver' => '', 'format_datetime' => []];
$options = (isset($options) ? (array) $options : []) + $widget->data + $default;
?>
@if($article)
    {!! $options['show_readmore'] ? '':'<a href="'.$article->url.'">' !!}
    @if($options['show_image'])
        <div class="article-image">{!! $article->present()->featured_image($options['featured_image_ver']) !!}</div>
    @endif
    @if($options['show_title'])
        <h3 class="article-title">{{mb_string_limit($article->title, $limit_title)}}</h3>
    @endif
    @if($options['show_datetime'] || $options['show_author'])
        <div class="article-meta">
            @if($options['show_datetime'])
                <span class="article-datetime">{!! $article->present()->updatedAt($options['format_datetime']) !!}</span>
            @endif
            @if($options['show_author'])
                <span class="article-author">{!! $article->present()->author !!}</span>
            @endif
        </div>
    @endif
    @if($options['show_summary'])
        <div class="article-summary">{{mb_string_limit($article->summary, $limit_summary)}}</div>
    @endif
    @if($options['show_readmore'])
        <div class="article-readmore"><a href="{{$article->url}}">{{trans( 'common.read_more' )}} »</a></div>
    @endif
    {!! $options['show_readmore'] ? '':'</a>' !!}
@endif