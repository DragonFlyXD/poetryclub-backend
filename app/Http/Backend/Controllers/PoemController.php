<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StorePoem;
use Illuminate\Http\JsonResponse;
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
    }


    /**
     * 返回诗文列表的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $poems = collection($this->poem->index())->toJson();
        return view('admin.poem.index', ['poems' => $poems]);
    }

    /**
     * 返回显示诗文详情的视图
     *
     * @param $poem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($poem)
    {
        $poem = $this->poem->show($poem);
        if ($poem instanceof JsonResponse && $poem->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.poem.view', ['poem' => $poem]);
    }

    /**
     * 根据关键字查找诗文
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->poem->index($request->query("query"));
    }

    /**
     * 返回创建诗文的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.poem.create');
    }

    /**
     * 添加诗文
     *
     * @param StorePoem $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePoem $request)
    {
        return $this->poem->store($request);
    }


    /**
     * 返回编辑诗文的视图
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poem = $this->poem->edit($id);
        if ($poem instanceof JsonResponse && $poem->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.poem.edit', ['poem' => $poem]);
    }

    /**
     * 更新诗文
     *
     * @param StorePoem $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePoem $request, $id)
    {
        return $this->poem->renew($request, $id);
    }

    /**
     * 删除诗文
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->poem->destroy($id);
    }

    /**
     * 删除诗文集合
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleDestroy(Request $request)
    {
        return $this->poem->destroy($request->all());
    }
}
