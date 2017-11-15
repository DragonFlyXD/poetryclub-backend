<?php

namespace App\Repositories\Eloquent;

class RoleRepository extends Repository
{

    /**
     * 指定模型名称
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Http\Frontend\Models\Role';
    }

    /**
     * role列表
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
                ->paginate(10);
        } else {
            // 若有查询参数
            $paginate = $this->model
                ->where('name', 'like', "%$query%")
                ->paginate(10);
        }

        foreach ($paginate as $key => $value) {
            $role = collection($value)->merge(['perms' => $value['perms']]);
            $role['publish_time'] = $this->transformTime($value['created_at']);
            $paginate[$key] = $role;
        }

        return collection($paginate);
    }

    /**
     * 查找指定role
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function show($id)
    {
        $role = $this->with(['perms'])->find($id);
        if ($role) {
            $role['publish_time'] = $this->transformTime($role['created_at']);
            return $role;
        } else {
            return $this->errorNotFound();
        }
    }

    /**
     * 存储role
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($request)
    {
        $role = $this->create([
            'name' => $request->name,
            'display_name' => $request->get('display_name', null),
            'description' => $request->get('description', null)
        ]);
        // 若Role创建成功且存在Perms
        if ($result = !!$role) {
            $role->attachPermission($request->perms);
        }
        return $this->respondWith(['created' => $result, 'role' => $role]);
    }

    /**
     * 更新role
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
        if ($result) {
            // 更新Role与Permission的中间表
            $this->find($id)->perms()->sync($request->perms);
        }
        return $this->respondWith(['updated' => !!$result]);
    }

    /**
     * 删除role
     *
     * @param $ids
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function destroy($ids)
    {
        return $this->respondWith(['deleted' => !!$this->delete($ids)]);
    }

}