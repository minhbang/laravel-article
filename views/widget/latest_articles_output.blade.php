<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Article\Article[] $articles
 * @var int $limit_title
 * @var int $limit_summary
 */
$css = empty( $widget->data['item_css'] ) ? [] : explode( '|', $widget->data['item_css'] );
$css_len = count( $css );
$has_col = ! empty( $css[0] ) && str_is( "* col-*", ' ' . $css[0] );
?>
@if($articles)
    @if($has_col)
        <div class="row">
            @endif
            @foreach($articles as $i => $article)
                <div class="article{{ $css_len ? ' '.$css[$i % $css_len]: '' }}">
                    @include('article::widget.article_output', compact('widget', 'article', 'limit_title', 'limit_summary'))
                </div>
            @endforeach
            @if($has_col)
        </div>
    @endif
@endif