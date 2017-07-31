<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\UserRepository as User;

/**
 * Class LoginController
 * @package App\Http\Backend\Controllers
 */
class LoginController extends Controller
{
    /**
     * @var User
     */
    protected $user;

    /**
     * LoginController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.login');
    }

    /**
     * 登录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function login(Request $request)
    {
        return $this->user->login($request, true);
    }

}
