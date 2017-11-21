<?php
namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreProfile;
use App\Http\Requests\UserRegister;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\UserRepository as User;

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
     * 返回显示用户列表的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = collect($this->user->index())->toJson();
        return view('admin.user.index', ['users' => $users]);
    }

    /**
     * 查询指定关键字的用户
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->user->index($request->query('query'));
    }

    /**
     * 返回添加用户的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.user.create');
    }

    /**
     * 添加用户
     *
     * @param UserRegister $request
     * @return mixed|null
     */
    public function register(UserRegister $request)
    {
        return $this->user->register($request, true);
    }

    /**
     * 返回编辑用户的视图
     *
     * @param $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function edit($user)
    {
        $user = $this->user->edit($user);
        if ($user instanceof JsonResponse && $user->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.user.edit', ['user' => $user]);
    }

    /**
     * 返回显示用户个人信息的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $user = $this->user->findBy('name', request()->route('user'));
        return view('admin.user.profile', ["user" => $user]);
    }

    /**
     * 更新个人信息
     *
     * @param $user
     * @param StoreProfile $request
     * @return JsonResponse|mixed
     */
    public function update($user, StoreProfile $request)
    {
        return $this->user->updateUserProfile($user, $request);
    }

    /**
     * 改变用户的auth信息
     *
     * @param $user
     * @param Request $request
     * @return JsonResponse|mixed
     */
    public function auth($user, Request $request)
    {
        return $this->user->updateUserAuth($user, $request);
    }
}