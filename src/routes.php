<?php
Route::group(
    ['prefix' => 'article', 'namespace' => 'Minhbang\LaravelArticle'],
    function () {
        Route::get('{slug}', ['as' => 'article.index', 'uses' => 'ArticleFrontendController@index']);
        Route::get('{article}/{slug}', ['as' => 'article.show', 'uses' => 'ArticleFrontendController@show']);
        Route::get(
            '{type}/{article}/{slug}',
            ['as' => 'article.show_with_type', 'uses' => 'ArticleFrontendController@show_with_type']
        );
    }
);

Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\LaravelArticle'],
    function () {
        Route::get('article/of/{type}', ['as' => 'backend.article.type', 'uses' => 'ArticleBackendController@index']);
        Route::get('article/data', ['as' => 'backend.article.data', 'uses' => 'ArticleBackendController@data']);
        Route::get(
            'article/{article}/preview',
            ['as' => 'backend.article.preview', 'uses' => 'ArticleBackendController@preview']
        );
        Route::post('article/{article}/quick_update', ['as' => 'backend.article.quick_update', 'uses' => 'ArticleBackendController@quickUpdate']);
        Route::resource('article', 'ArticleBackendController');
    }
);