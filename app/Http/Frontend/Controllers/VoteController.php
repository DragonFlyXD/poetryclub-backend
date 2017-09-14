<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;

class VoteController extends Controller
{
    protected $poem;
    protected $appreciation;

    /**
     * VoteController constructor.
     * @param Poem $poem
     * @param Appreciation $appreciation
     */
    public function __construct(Poem $poem, Appreciation $appreciation)
    {
        $this->poem = $poem;
        $this->appreciation = $appreciation;
    }

    /**
     * 点赞诗文
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function votePoem(Request $request)
    {
        return $this->poem->vote($request->get('poem'));
    }

    /**
     * 获取该诗文的点赞状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function VotedByPoem($poem)
    {
        return $this->poem->voted($poem);
    }

    /**
     * 点赞品鉴
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function voteAppreciation(Request $request)
    {
        return $this->appreciation->vote($request->get('appreciation'));
    }

    /**
     * 获取该品鉴的点赞状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function votedByAppreciation($appreciation)
    {
        return $this->appreciation->voted($appreciation);
    }
}
