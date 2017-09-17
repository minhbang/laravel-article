<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Article\Article[]|\Illuminate\Database\Eloquent\Collection $articles
 * @var int $limit_title
 * @var int $limit_summary
 */
?>
@if($articles)
    <?php $latest = $articles->shift();?>
    <div class="row">
        <div class="col-sm-6">
            <div class="article article-latest">
                @include(
                    'article::widget.article_output',
                    compact('widget') + ['limit_title' => 0, 'limit_summary' => $limit_summary *2, 'article' => $latest,
                        'options' =>['show_summary' => true]
                    ]
                )
            </div>
        </div>
        <div class="col-sm-6">
            @foreach($articles as $article)
                <div class="article">
                    @include('article::widget.article_output', compact('widget', 'article', 'limit_title', 'limit_summary'))
                </div>
            @endforeach
        </div>
    </div>
@endif