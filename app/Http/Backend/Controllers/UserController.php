<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
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

    public function index()
    {
        $users = collect($this->user->index())->toJson();
        return view('admin.user.index', ['users' => $users]);
    }

    public function search(Request $request)
    {
        return $this->user->index($request->query('query'));
    }


    public function profile()
    {
        $default = '<span class="default">未设置</span>';
        return view('admin.user.profile', ["user" => user('web'), 'default' => $default]);
    }
}
