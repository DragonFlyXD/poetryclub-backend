<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreCategory;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\CategoryRepository as Category;

class CategoryController extends Controller
{

    protected $category;

    /**
     * CategoryController constructor.
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * 返回显示分类列表的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = collection($this->category->index('', true))->toJson();
        return view('admin.category.index', ['categories' => $categories]);
    }

    /**
     * 根据关键字搜索分类
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->category->index($request->query('query'), true);
    }

    /**
     * 存储分类
     *
     * @param StoreCategory $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategory $request)
    {
        return $this->category->store($request);
    }

    /**
     * 获取指定ID的分类
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->category->show($id);
    }


    /**
     * 更新分类
     *
     * @param StoreCategory $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCategory $request, $id)
    {
        return $this->category->renew($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
