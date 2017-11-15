<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StorePermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\PermissionRepository as Permission;

class PermissionController extends Controller
{
    protected $permission;

    /**
     * PermissionController constructor.
     * @param $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     *
     * 返回permission列表的视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $permissions = collect($this->permission->index())->toJson();
        return view('admin.auth.permission.index', ['permissions' => $permissions]);
    }

    /**
     * 返回显示permission详情的视图
     *
     * @param $permission
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($permission)
    {
        $permission = $this->permission->show($permission);
        if ($permission instanceof JsonResponse && $permission->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.auth.permission.view', ['permission' => $permission]);
    }

    /**
     * 根据关键字查找permission
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->permission->index($request->query("query"));
    }

    /**
     * 返回创建permission的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.permission.create');
    }

    /**
     * 添加permission
     *
     * @param StorePermission $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePermission $request)
    {
        return $this->permission->store($request);
    }


    /**
     * 返回编辑permission的视图
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = $this->permission->show($id);
        if ($permission instanceof JsonResponse && $permission->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.auth.permission.edit', ['permission' => $permission]);
    }

    /**
     * 更新permission
     *
     * @param StorePermission $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePermission $request, $id)
    {
        return $this->permission->renew($request, $id);
    }

    /**
     * 删除permission
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->permission->destroy($id);
    }

    /**
     * 删除permission集合
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleDestroy(Request $request)
    {
        return $this->permission->destroy($request->all());
    }

}
