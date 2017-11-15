<?php

namespace App\Repositories\Eloquent;


class PermissionRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Permission';
    }

    /**
     * permission列表
     *
     * @param string $query
     * @return mixed
     */
    public function index($query = '')
    {
        // 获取分页数据
        if (!$query) {
            $paginate = $this->model
                ->orderBy('created_at', 'desc')
                ->paginate(10)
                ->toArray();
        } else {
            // 若有查询参数
            $paginate = $this->model
                ->where('name', 'like', "%$query%")
                ->paginate(10)
                ->toArray();
        }

        if (!$paginate['total']) {
            return $paginate;
        }

        $paginate['data'] = collection($paginate['data'])
            ->map(function ($item) {
                $item['publish_time'] = $this->transformTime($item['created_at']);
                return $item;
            });

        return $paginate;
    }

    /**
     * 查找指定permission
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function show($id)
    {
        $permission = $this->find($id);
        if ($permission) {
            $permission['publish_time'] = $this->transformTime($permission['created_at']);
            return $permission;
        } else {
            return $this->errorNotFound();
        }
    }

    /**
     * 存储permission
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        $permission = $this->create([
            'name' => $request->name,
            'display_name' => $request->get('display_name', null),
            'description' => $request->get('description', null)
        ]);
        return $this->respondWith(['created' => !!$permission, 'permission' => $permission]);
    }

    /**
     * 更新permission
     *
     * @param $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function renew($request, $id)
    {
        $result = $this->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
            'description' => $request->description
        ], $id);
        return $this->respondWith(['updated' => !!$result]);
    }

    /**
     * 删除permission
     *
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($ids)
    {
        return $this->respondWith(['deleted' => !!$this->delete($ids)]);
    }
}