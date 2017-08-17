<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\UserRepository as User;
use Overtrue\LaravelSocialite\Socialite;

class AuthController extends Controller
{

    protected $user;

    /**
     * AuthController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 跳转至 GitHub 授权页面
     *
     * @return mixed
     */
    public function github()
    {
        return $this->user->socialiteRedirect('github');
    }

    /**
     * GitHub 授权的回调
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function githubCallback()
    {
        return $this->user->socialiteCallback('github');
    }

    /**
     * 跳转至微博授权页面
     *
     * @return mixed
     */
    public function weibo()
    {
        return $this->user->socialiteRedirect('weibo');
    }

    /**
     * 微博登录授权的回调
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function weiboCallback()
    {
        return $this->user->socialiteCallback('weibo');
    }

    /**
     * 取消微博登录的回调
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function weiboCancel()
    {
        return redirect('http://www.dragonflyxd.com/user/login');
    }
}
