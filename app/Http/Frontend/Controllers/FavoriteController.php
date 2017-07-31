<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    protected $poem;
    protected $appreciation;

    /**
     * FavoriteController constructor.
     * @param Poem $poem
     * @param Appreciation $appreciation
     */
    public function __construct(Poem $poem, Appreciation $appreciation)
    {
        $this->poem = $poem;
        $this->appreciation = $appreciation;
    }

    /**
     * 收藏诗文
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favorPoem(Request $request)
    {
        return $this->poem->favorite($request->get('poem'));
    }

    /**
     * 获取诗文的收藏状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favoredByPoem($poem)
    {
        return $this->poem->favored($poem);
    }

    /**
     * 收藏品鉴
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favorAppreciation(Request $request)
    {
        return $this->appreciation->favorite($request->get('appreciation'));
    }

    /**
     * 获取该品鉴的收藏状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function favoredByAppreciation($appreciation)
    {
        return $this->appreciation->favored($appreciation);
    }
}
