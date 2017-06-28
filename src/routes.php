<?php
// Frontend
/*Route::group(
    ['prefix' => 'article', 'namespace' => 'Minhbang\Article\Controllers', 'as' => 'article.', 'middleware' => config('article.middlewares.frontend')],
    function () {
        Route::get('{slug}', ['as' => 'index', 'uses' => 'FrontendController@index']);
        Route::get('{slug}/category/{category}', ['as' => 'category', 'uses' => 'FrontendController@category']);
        Route::get('{article}/{slug}', ['as' => 'show', 'uses' => 'FrontendController@show']);
    }
);*/

// Backend: Admin quản lý articles
Route::group(
    ['prefix' => 'backend', 'as' => 'backend.', 'namespace' => 'Minhbang\Article\Controllers'],
    function () {
        Route::group(
            ['middleware' => config('article.middlewares.backend')],
            function () {
                Route::group(
                    ['prefix' => 'article', 'as' => 'article.'],
                    function () {
                        Route::get('of/{type}', ['as' => 'type', 'uses' => 'BackendController@index']);
                        Route::get('data', ['as' => 'data', 'uses' => 'BackendController@data']);
                        Route::get('{article}/preview', ['as' => 'preview', 'uses' => 'BackendController@preview']);
                        Route::post('{article}/quick_update', ['as' => 'quick_update', 'uses' => 'BackendController@quickUpdate']);
                        //Route::post('{article}/status/{status}', ['as' => 'status', 'uses' => 'BackendController@status']);
                    }
                );
                Route::resource('article', 'BackendController');
            }
        );
    }
);

// Manage: User quản lý articles
/*Route::group(
    ['prefix' => 'manage', 'namespace' => 'Minhbang\Article\Controllers'],
    function () {
        Route::group(
            ['middleware' => config('article.middlewares.manage')],
            function () {
                Route::group(
                    ['prefix' => 'article', 'as' => 'manage.article.'],
                    function () {
                        Route::get('of/{type}', ['as' => 'index', 'uses' => 'ManageController@index']);
                        Route::get('data', ['as' => 'data', 'uses' => 'ManageController@data']);
                        Route::get('{article}/preview', ['as' => 'preview', 'uses' => 'ManageController@preview']);
                    }
                );
                Route::resource('article', 'ManageController', ['except' => 'index']);
            }
        );
    }
);*/