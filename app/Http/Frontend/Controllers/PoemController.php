<?php

namespace App\Http\Frontend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StorePoem;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PoemRepository as Poem;

class PoemController extends Controller
{
    protected $poem;

    /**
     * PoemController constructor.
     * @param Poem $poem
     */
    public function __construct(Poem $poem)
    {
        $this->poem = $poem;
        $this->middleware('auth:api', ['except' => ['index', 'show']]);
    }

    /**
     * 获取诗文列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->poem->index(request()->query('query'));
    }

    /**
     * 存储诗文
     *
     * @param StorePoem $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoem $request)
    {
        return $this->poem->store($request);
    }

    /**
     * 获取诗文
     *
     * @param $poem
     * @return \Illuminate\Http\Response
     */
    public function show($poem)
    {
        return $this->poem->show($poem);
    }

    /**
     * 更新诗文
     *
     * @param StorePoem $request
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(StorePoem $request, $poem)
    {
        return $this->poem->renew($request, $poem);
    }


}
