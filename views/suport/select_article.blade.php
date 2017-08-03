<?php

use Minhbang\Article\Article;

$articles = ['' => trans('article::common.select_article')] + Article::forSelectize()->pluck('title', 'id')->all();
if ($article = empty($selected_id) ? null : Article::find($selected_id)) {
    $articles = $articles + [$selected_id => $article->title];
}
?>
{!! Form::select($name, $articles, $selected_id, ['class' => 'form-control']) !!}
@push('scripts')
    <script type="text/javascript">
        $(function () {
            $('#{{$name}}').selectize(
                {
                    valueField: 'id',
                    labelField: 'title',
                    searchField: 'title',
                    options: [],
                    create: false,
                    load: function (query, callback) {
                        if (!query.length) return callback();
                        $.ajax({
                            url: '{{route($route_prefix."backend.article.select")}}',
                            type: 'GET',
                            dataType: 'json',
                            data: {title: query},
                            error: function () {
                                callback();
                            },
                            success: function (res) {
                                callback(res);
                            }
                        });
                    }
                }
            );
        });
    </script>
@endpush