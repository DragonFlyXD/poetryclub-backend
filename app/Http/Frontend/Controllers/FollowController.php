<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Repositories\Eloquent\UserRepository as User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    protected $user;

    /**
     * FollowController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 获取用户关注列表
     *
     * @param $user
     * @return mixed
     */
    public function followers($user)
    {
        return $this->user->followers($user);
    }

    /**
     * 获取用户被关注者列表
     *
     * @param $user
     * @return mixed
     */
    public function followings($user)
    {
        return $this->user->followings($user);
    }

    /**
     * 关注用户
     *
     * @param Request $request
     * @return mixed
     */
    public function follow(Request $request)
    {
        return $this->user->follow($request->get('user'));
    }

    /**
     * 获取用户的关注状态
     *
     * @param $user
     * @return mixed
     */
    public function followed($user)
    {
        return $this->user->followed($user);
    }

}
