<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\App;

class CategoryRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Category';
    }

    /**
     * 获取分类列表
     *
     * @param string $query
     * @param bool $isPaginate
     * @return mixed
     */
    public function index($query = '', $isPaginate = false)
    {
        // 是否需要分页展示
        if ($isPaginate) {
            if (!$query) {
                // 若无查询参数
                $categories = $this->model
                    ->withTrashed()
                    ->orderBy('created_at', 'desc')->paginate(10)->toArray();
            } else {
                // 若有查询参数
                $categories = $this->model
                    ->where('name', 'like', "%$query%")
                    ->paginate(10)
                    ->toArray();
            }

            $categories['data'] = collection($categories['data'])->map(function ($category) {
                $category['publish_time'] = $this->transformTime($category['created_at']);
                return $category;
            })->all();

        } else {
            if (!$query) {
                // 若无查询参数
                $categories = $this->model
                    ->withTrashed()
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->toArray();
            } else {
                // 若有查询参数
                $categories = $this->model
                    ->where('name', 'like', "%$query%")
                    ->get()
                    ->toArray();
            }
        }

        return $categories;
    }

    /**
     * 获取指定分类
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->find($id) ?: $this->errorNotFound();
    }

    /**
     * 更新分类
     *
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function renew($request, $id)
    {
        $result = $this->update([
            'name' => $request->get('name')
        ], $id);

        return $this->respondWith(['updated' => !!$result]);
    }

    /**
     * 新添分类
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        $category = $this->create([
            'name' => $request->get('name')
        ]);

        return $this->respondWith(['created' => !!$category, 'category' => $category]);
    }

    /**
     * 删除分类
     *
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($ids)
    {
        return $this->respondWith(['deleted' => !!$this->delete($ids)]);
    }

    /**
     * 回复被软删除的分类
     *
     * @param $poem
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function restore($poem)
    {
        $result = $this->model->withTrashed()->find($poem)->restore();
        return $this->respondWith(['restored' => $result]);
    }
}