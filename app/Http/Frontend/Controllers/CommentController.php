<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Repositories\Eloquent\CommentRepository as Comment;
use App\Repositories\Eloquent\PoemRepository as Poem;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    protected $comment;
    protected $poem;
    protected $appreciation;

    /**
     * CommentController constructor.
     * @param Comment $comment
     * @param Poem $poem
     * @param Appreciation $appreciation
     */
    public function __construct(Comment $comment, Poem $poem, Appreciation $appreciation)
    {
        $this->comment = $comment;
        $this->poem = $poem;
        $this->appreciation = $appreciation;
    }

    /**
     * 存储评论信息
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->comment->store($request);
    }

    /**
     * 获取指定诗文的所有评论信息
     *
     * @param $poem
     * @return mixed
     */
    public function poem($poem)
    {
        return $this->poem->getModelCommentsById($poem);
    }

    /**
     * 获取指定品鉴的所有评论信息
     *
     * @param $appreciation
     * @return mixed
     */
    public function appreciation($appreciation)
    {
        return $this->appreciation->getModelCommentsById($appreciation);
    }

}
