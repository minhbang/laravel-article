<?php
/** @var \Minhbang\Article\Article $article */
$featured = isset($featured) ? $featured : false;
?>
<div class="article-item">
    <a href="{{$article->url}}">
        <div class="article-image">{!! $article->present()->featured_image !!}</div>
        <h3 class="article-title">{{mb_string_limit($article->title, $featured ? -1: 30)}}</h3>
        @if($show_meta) {!! $article->present()->metaBlock !!} @endif
        <div class="article-summary">{{$article->present()->summary($featured ? -1: null)}}</div>
    </a>
</div>