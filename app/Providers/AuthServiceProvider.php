<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Mockery\Generator\StringManipulation\Pass\Pass;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 注册passport所需的路由
        Passport::routes();
        Passport::$pruneRevokedTokens = true;
        // 设置访问令牌的过期时间  默认一年
//        Passport::tokensExpireIn(Carbon::now()->addDays(15));
        // 设置刷新过期的访问令牌的时间   默认一年
//        Passport::refreshTokensExpireIn(Carbon::now()->addDays(30));
        // 当用户请求新的令牌时或刷新已存在令牌时,删除老的已失效的令牌
//        Passport::pruneRevokedTokens();
    }
}
