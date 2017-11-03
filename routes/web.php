<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'admin', 'namespace' => 'Backend\Controllers', 'middleware' => 'cors'], function () {
    // 后台登录
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', 'LoginController@index');
        Route::post('login', 'LoginController@login');
    });

    Route::group(['middleware' => 'auth'], function () {
        // 后台首页
        Route::get('/', 'HomeController@index');

        // 用户相关
        Route::get('user', 'UserController@index');
        Route::get('user/create', 'UserController@create');
        Route::post('user/register', 'UserController@register');
        Route::get('user/search', 'UserController@search');
        Route::put('user/{user}', 'UserController@update');
        Route::get('user/{user}', 'UserController@profile');
        Route::get('user/{user}/edit', 'UserController@edit');
        Route::post('user/{user}/active', 'UserController@active');

        // 诗文相关
        Route::post('poem/destroy', 'PoemController@multipleDestroy');    // 多选删除
        Route::get('poem/search', 'PoemController@search');
        Route::resource('poem', 'PoemController');

        // 品鉴相关
        Route::post('appreciation/destroy', 'AppreciationController@multipleDestroy');    // 多选删除
        Route::get('appreciation/search', 'AppreciationController@search');
        Route::resource('appreciation', 'AppreciationController');

        // 分类相关
        Route::post('category/destroy', 'CategoryController@multipleDestroy');    // 多选删除
        Route::get('category/{category}/restore', 'CategoryController@restore')->where('category', '\d+');  // 恢复被软删除的分类
        Route::get('category/search', 'CategoryController@search');
        Route::resource('category', 'CategoryController');

        // 权限相关
        Route::get('auth/role', 'RoleController@index');
        Route::get('auth/permission', 'PermissionController@index');

        // 退出登录
        Route::get('logout', 'HomeController@logout');
    });
});

