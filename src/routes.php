<?php
// Frontend
Route::group([
    'domain' => env('APP_DOMAIN', 'localhost'),
    'prefix' => 'article',
    'namespace' => 'Minhbang\Article\Controllers',
    'as' => 'article.',
    'middleware' => config('article.middlewares.frontend'),
], function () {
    Route::post('/', ['as' => 'search', 'uses' => 'FrontendController@search']);
    Route::get('category/{slug}', ['as' => 'category', 'uses' => 'FrontendController@category']);
    Route::get('{article}/{slug}', ['as' => 'show', 'uses' => 'FrontendController@show']);
});

// Backend: Admin quản lý articles
Route::group(['prefix' => 'backend', 'as' => 'backend.', 'namespace' => 'Minhbang\Article\Controllers'], function () {
    Route::group(['middleware' => config('article.middlewares.backend')], function () {
        Route::group(['prefix' => 'article', 'as' => 'article.'], function () {
            Route::get('select', ['as' => 'select', 'uses' => 'BackendController@select']);
            Route::get('of/{type}', ['as' => 'type', 'uses' => 'BackendController@index']);
            Route::get('data', ['as' => 'data', 'uses' => 'BackendController@data']);
            Route::get('{article}/preview', ['as' => 'preview', 'uses' => 'BackendController@preview']);
            Route::post('{article}/quick_update', ['as' => 'quick_update', 'uses' => 'BackendController@quickUpdate']);
            Route::post('{article}/status/{status}', ['as' => 'status', 'uses' => 'BackendController@status']);
        });
        Route::resource('article', 'BackendController');
    });
});
