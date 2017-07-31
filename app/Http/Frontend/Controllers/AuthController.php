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
}
