<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\UserRepository as User;

class HomeController extends Controller
{
    protected $poem;
    protected $user;

    /**
     * HomeController constructor.
     * @param Poem $poem
     * @param User $user
     */
    public function __construct(Poem $poem, User $user)
    {
        $this->poem = $poem;
        $this->user = $user;
    }

    /**
     * 获取首页内容
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->poem->hotPoems();
    }
}
