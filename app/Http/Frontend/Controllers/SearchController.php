<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $poem;
    protected $appreciation;

    /**
     * SearchController constructor.
     * @param Poem $poem
     * @param Appreciation $appreciation
     */
    public function __construct(Poem $poem, Appreciation $appreciation)
    {
        $this->poem = $poem;
        $this->appreciation = $appreciation;
    }

    /**
     * 获取检索列表
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        // 获取检索的关键字
        $keywords = $request->query('query');
        // 检索诗文
        $poem = $this->poem->scout($keywords);
        // 检索品鉴
        $appreciation = $this->appreciation->scout($keywords);
        return response()->json(['poem' => $poem, 'appreciation' => $appreciation]);
    }
}
