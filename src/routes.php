<?php
Route::group(
    ['prefix' => 'article', 'namespace' => 'Minhbang\Article', 'as' => 'article.'],
    function () {
        Route::get('{slug}', ['as' => 'index', 'uses' => 'FrontendController@index']);
        Route::get('{article}/{slug}', ['as' => 'show', 'uses' => 'FrontendController@show']);
        Route::get('{type}/{article}/{slug}', ['as' => 'show_with_type', 'uses' => 'FrontendController@show_with_type']);
    }
);

Route::group(
    ['prefix' => 'backend', 'namespace' => 'Minhbang\Article'],
    function () {
        Route::group(
            ['prefix' => 'article', 'as' => 'backend.article.'],
            function () {
                Route::get('of/{type}', ['as' => 'type', 'uses' => 'BackendController@index']);
                Route::get('data', ['as' => 'data', 'uses' => 'BackendController@data']);
                Route::get('{article}/preview', ['as' => 'preview', 'uses' => 'BackendController@preview']);
                Route::post('{article}/quick_update', ['as' => 'quick_update', 'uses' => 'BackendController@quickUpdate']);
            });
        Route::resource('article', 'BackendController');
    }
);