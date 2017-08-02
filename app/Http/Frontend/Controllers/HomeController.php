<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PoemRepository as Poem;

class HomeController extends Controller
{
    protected $poem;

    /**
     * HomeController constructor.
     * @param $poem
     */
    public function __construct(Poem $poem)
    {
        $this->poem = $poem;
    }

    /**
     * 获取首页内容
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $poems = $this->poem->hotPoems();
        return response()->json(['poem' => $poems]);
    }
}
