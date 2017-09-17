<?php
/**
 * @var \Minhbang\Layout\Widget $widget
 * @var \Minhbang\Article\Article[]|\Illuminate\Database\Eloquent\Collection $articles
 * @var int $limit_title
 * @var int $limit_summary
 */
$options = ['featured_image_ver' => 'full', 'format_datetime' => ['template' => ':day<br>:date']];
$limit_title = 0;
?>
@if($articles)
    <div id="article-slider-{{$widget->id}}" class="article-slider">
        @foreach($articles as $article)
            <div class="article">
                @include('article::widget.article_output', compact('widget', 'article', 'limit_title', 'limit_summary', 'options'))
            </div>
        @endforeach
    </div>
    @push('scripts')
        <script type="text/javascript">
            $(function () {
                $('#article-slider-{{$widget->id}}').bxSlider({speed: 500, pause: 5000, auto: true});
            });
        </script>
    @endpush
@endif
