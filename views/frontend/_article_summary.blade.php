<?php
/** @var \Minhbang\Article\Article $article */
$featured = isset($featured) ? $featured : false;
?>
<div class="article-item">
    <a href="{{$article->url}}">
        @if(($article_image = $article->present()->featured_image))
            <div class="article-image">{!! $article_image !!}</div>
        @endif
        <h3 class="article-title">{{$article->title}}</h3>
        @if($show_meta) {!! $article->present()->metaBlock(config('article.display.show_author'), false) !!} @endif
        <div class="article-summary">{{$article->present()->summary($featured ? -1: null)}}</div>
    </a>
</div>