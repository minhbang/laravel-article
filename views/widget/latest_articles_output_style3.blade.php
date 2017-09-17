<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Article\Article[]|\Illuminate\Database\Eloquent\Collection $articles
 * @var int $limit_title
 * @var int $limit_summary
 */
?>
@if($articles)
    <?php $rows = $articles->split(ceil($articles->count() / 2));?>
    @foreach($rows as $row)
        <div class="row">
            @foreach($row as $article)
                <div class="col-lg-6">
                    <div class="article">
                        @include('article::widget.article_output', compact('widget', 'article', 'limit_title', 'limit_summary'))
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@endif