<?php

namespace App\Repositories\Eloquent;

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
     * @return mixed
     */
    public function index($query = '')
    {
        // 若为展示分类列表
        if (!$query) {
            $paginate = $this->paginate()->toArray();
        } else {
            // 若有查询参数
            $paginate = $this->model
                ->where('name', 'like', "%$query%")
                ->paginate(10)
                ->toArray();
        }
        return $paginate;
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

        return $this->respondWith(['updated' => $result ? true : false]);
    }

    public function store($request)
    {
        $category = $this->create([
            'name' => $request->get('name')
        ]);

        if ($category) {
            return $this->respondWith(['created' => true, 'data' => $category]);
        } else {
            return $this->respondWith(['created' => false]);
        }
    }
}