<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['namespace' => 'Frontend\Controllers', 'middleware' => 'cors'], function () {
    // 自动部署
    Route::any('deploy', 'DeployController@deploy');
    // 检索搜索内容
    Route::get('scout', 'SearchController@index');
    // 返回分类列表
    Route::get('category', 'CategoryController@index');
    /* 社会化登录相关 */
    Route::get('oauth/github', 'AuthController@github');
    Route::get('oauth/github/callback', 'AuthController@githubCallback');
    /* 用户相关 */
    // 用户注册、用户登录
    Route::post('user/register', 'UserController@register');
    Route::post('user/login', 'UserController@login');
    // 获取指定用户信息
    Route::get('user/{name}', 'UserController@show');
    // 获取关注者列表、被关注者列表
    Route::get('user/{user}/followers', 'FollowController@followers');
    Route::get('user/{user}/followings', 'FollowController@followings');
    Route::group(['prefix' => 'user', 'middleware' => 'auth:api'], function () {
        // 获取用户数据
        Route::get('/', 'UserController@profile');
        // 关注用户
        Route::post('follow', 'FollowController@follow');
        // 获取关注状态
        Route::get('{user}/followed', 'FollowController@followed');
        // 更新头像
        Route::post('avatar', 'UserController@avatar');
        // 更新个人信息
        Route::put('update', 'UserController@update');
        // 重置密码、用户退出
        Route::post('reset', 'UserController@reset');
        Route::post('logout', 'UserController@logout');
    });

    /* 私信相关 */
    Route::group(['prefix' => 'inbox', 'middleware' => 'auth:api'], function () {
        // 获取私信列表、
        Route::get('/', 'InboxController@index');
        // 获取对话列表
        Route::get('inbox/{dialog}', 'InboxController@show')->where('dialog', '\d+');
        // 存储私信内容、对话内容
        Route::post('inbox', 'InboxController@store');
        Route::post('inbox/{dialog}', 'InboxController@dialog')->where('dialog', '\d+');
        // 删除对话内容、对话
        Route::delete('inbox/{dialog}/dialog/{id}', 'InboxController@delete')->where(['dialog' => '\d+', 'id' => '\d+']);
        Route::delete('inbox/{dialog}', 'InboxController@destroy')->where('dialog', '\d+');
    });

    /* 邮箱相关 */
    Route::group(['prefix' => 'email'], function () {
        // 验证token值
        Route::get('verify', 'EmailController@verify');
        // 发送网站建议反馈邮件
        Route::post('feedback', 'EmailController@feedback');
    });

    /* 诗文相关 */
    // 获取诗文列表、指定诗文
    Route::get('poem', 'PoemController@index');
    Route::get('poem/{poem}', 'PoemController@show')->where('poem', '\d+');
    // 获取指定诗文的所有评论信息
    Route::get('poem/{poem}/comments', 'CommentController@poem')->where('poem', '\d+');
    // 获取指定诗文的评分信息
    Route::get('poem/{poem}/rating', 'RatingController@poem')->where('poem', '\d+');
    Route::group(['prefix' => 'poem', 'middleware' => 'auth:api'], function () {
        // 存储诗文信息
        Route::post('/', 'PoemController@store');
        // 点赞、收藏诗文
        Route::post('vote', 'VoteController@votePoem');
        Route::post('favorite', 'FavoriteController@favorPoem');
        // 获取诗文的点赞、收藏、评分的状态
        Route::get('{poem}/voted', 'VoteController@votedByPoem')->where('poem', '\d+');
        Route::get('{poem}/favored', 'FavoriteController@favoredByPoem')->where('poem', '\d+');
        Route::get('{poem}/rated', 'RatingController@ratedByPoem')->where('poem', '\d+');
        // 存储诗文评分信息、评论信息
        Route::post('comment', 'CommentController@store');
        Route::post('rating', 'RatingController@store');
    });

    /* 品鉴相关 */
    // 获取品鉴列表、指定品鉴
    Route::get('appreciation', 'AppreciationController@index');
    Route::get('appreciation/{appreciation}', 'AppreciationController@show')->where('appreciation', '\d+');
    // 获取指定品鉴的所有评论信息
    Route::get('appreciation/{appreciation}/comments', 'AppreciationController@store');
    // 获取指定品鉴的评分
    Route::get('appreciation/{appreciation}/rating', 'RatingController@appreciation')->where('appreciation', '\d+');
    Route::group(['prefix' => 'appreciation', 'middleware' => 'auth:api'], function () {
        // 存储品鉴信息
        Route::post('/', 'AppreciationController@store');
        // 点赞、收藏品鉴
        Route::post('vote', 'VoteController@voteAppreciation');
        Route::post('favorite', 'FavoriteController@favorAppreciation');
        // 获取品鉴的点赞、收藏、评分的状态
        Route::get('{appreciation}/voted', 'VoteController@votedByAppreciation')->where('appreciation', '\d+');
        Route::get('{appreciation}/favored', 'FavoriteController@favoredByAppreciation')->where('appreciation', '\d+');
        Route::get('{appreciation}/rated', 'RatingController@ratedByAppreciation')->where('appreciation', '\d+');
        // 存储品鉴评分信息、评论信息
        Route::post('comment', 'CommentController@store');
        Route::post('rating', 'RatingController@store');
    });
});