<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\UserRepository as User;

class PasswordController extends Controller
{
    protected $user;

    /**
     * PasswordController constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 重置密码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function reset(Request $request)
    {
        return $this->user->resetPassword($request);
    }

    /**
     * 忘记密码
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function forget(Request $request)
    {
        return $this->user->forgetPassword($request);
    }

    /**
     * 邮箱重置密码
     *
     * @param Request $request
     * @param $token
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function email(Request $request, $token)
    {
        return $this->user->verifyPassword($request, $token);
    }
}
