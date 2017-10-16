<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreAppreciation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\AppreciationRepository as Appreciation;
use App\Repositories\Eloquent\PoemRepository as Poem;

class AppreciationController extends Controller
{
    protected $appreciation;
    protected $poem;

    /**
     * AppreciationController constructor.
     * @param Appreciation $appreciation
     * @param Poem $poem
     */
    public function __construct(Appreciation $appreciation, Poem $poem)
    {
        $this->appreciation = $appreciation;
        $this->poem = $poem;
    }


    /**
     * 返回品鉴列表的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $appreciations = collection($this->appreciation->index())->toJson();
        return view('admin.appreciation.index', ['appreciations' => $appreciations]);
    }

    /**
     * 返回显示品鉴详情的视图
     *
     * @param $appreciation
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($appreciation)
    {
        $appreciation = $this->appreciation->show($appreciation);
        if ($appreciation instanceof JsonResponse && $appreciation->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.appreciation.view', ['appreciation' => $appreciation]);
    }

    /**
     * 根据关键字查找品鉴
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->appreciation->index($request->query("query"));
    }

    /**
     * 返回创建品鉴的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.appreciation.create');
    }

    /**
     * 添加诗文
     *
     * @param StoreAppreciation $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppreciation $request)
    {
        return $this->appreciation->store($request);
    }


    /**
     * 返回编辑品鉴的视图
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->appreciation->edit($id);
        if ($data instanceof JsonResponse && $data->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.appreciation.edit', ['appreciation' => $data['appreciation'], 'poem' => $data['poem']]);
    }

    /**
     * 更新品鉴
     *
     * @param StoreAppreciation $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAppreciation $request, $id)
    {
        return $this->appreciation->renew($request, $id);
    }

    /**
     * 删除品鉴
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->appreciation->destroy($id);
    }

    /**
     * 删除品鉴集合
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleDestroy(Request $request)
    {
        return $this->appreciation->destroy($request->all());
    }
}
