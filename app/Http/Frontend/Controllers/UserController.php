<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreProfile;
use App\Http\Requests\UserRegister as Register;
use App\Repositories\Eloquent\UserRepository as User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 获取用户个人信息
     *
     * @return mixed
     */
    public function profile()
    {
        return $this->user->getUserProfile();
    }

    /**
     * 用户注册
     *
     * @param Register $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function register(Register $request)
    {
        return $this->user->register($request);
    }

    /**
     * 用户登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login(Request $request)
    {
        return $this->user->login($request);
    }

    /**
     * 根据用户名查找用户
     *
     * @param $name
     * @return mixed
     */
    public function show($name)
    {
        return $this->user->show($name);
    }

    /**
     * 根据昵称查找用户
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->user->getUserByNickname($request->query('query'));
    }

    /**
     * 更改用户头像
     *
     * @param Request $request
     * @return JsonResponse|mixed|string
     */
    public function avatar(Request $request)
    {
        return $this->user->changeAvatar($request);
    }

    /**
     * 修改个人信息
     *
     * @param StoreProfile $request
     * @return JsonResponse|mixed
     */
    public function update(StoreProfile $request)
    {
        return $this->user->updateUserProfile($request);
    }

    /**
     * 退出登录
     *
     * @return JsonResponse|mixed
     */
    public function logout()
    {
        return $this->user->logout();
    }
}
