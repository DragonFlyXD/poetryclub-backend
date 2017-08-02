<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\UserRepository as User;

class WorkController extends Controller
{
    protected $user;

    /**
     * WorkController constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * 获取指定用户的作品列表
     *
     * @param $user
     * @return mixed
     */
    public function index($user)
    {
        return $this->user->works($user);
    }

}
