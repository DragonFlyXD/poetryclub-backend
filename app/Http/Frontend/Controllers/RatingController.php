<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;
use App\Repositories\Eloquent\RatingRepository as Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    protected $poem;
    protected $appreciation;
    protected $rating;

    /**
     * RatingController constructor.
     * @param Poem $poem
     * @param Appreciation $appreciation
     * @param Rating $rating
     */
    public function __construct(Poem $poem, Appreciation $appreciation, Rating $rating)
    {
        $this->poem = $poem;
        $this->appreciation = $appreciation;
        $this->rating = $rating;
    }

    /**
     * 存储评分
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->rating->store($request);
    }

    /**
     * 获取指定诗文的平均评分
     *
     * @param $poem
     * @return mixed
     */
    public function poem($poem)
    {
        $rating = $this->poem->getRatingsByModel($this->poem->find($poem));
        return response()->json(['rating' => $rating]);
    }

    /**
     * 获取指定诗文的评分状态
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function ratedByPoem($poem)
    {
        return $this->poem->rated($poem);
    }

    /**
     * 获取指定品鉴的平均评分
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function appreciation($appreciation)
    {
        $rating = $this->appreciation->getRatingsByModel($this->appreciation->find($appreciation));
        return response()->json(['rating' => $rating]);
    }

    /**
     * 获取指定品鉴的评分状态
     *
     * @param $appreciation
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function ratedByAppreciation($appreciation)
    {
        return $this->appreciation->rated($appreciation);
    }
}
