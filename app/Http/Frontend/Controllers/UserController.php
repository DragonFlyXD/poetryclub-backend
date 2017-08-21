<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
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
        $user = $this->user->register($request->all());
        // 若用户名含有非法字符
        if ($user instanceof JsonResponse) {
            return $user;
        }
        // 若创建失败
        if (is_null($user)) {
            return $this->user->respondWith(['registered' => false]);
        }
        // 发送注册验证邮件
        $this->user->registerShip($user);
        return $this->user->respondWith(['registered' => true]);
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(Request $request)
    {
        return $this->user->updateUserProfile($request->toArray());
    }

    /**
     * 用户退出登录
     */
    public function logout()
    {
        return $this->user->logout();
    }
}
