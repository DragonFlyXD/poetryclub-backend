<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use App\Http\Requests\StoreRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\RoleRepository as Role;

class RoleController extends Controller
{
    protected $role;

    /**
     * RoleController constructor.
     * @param $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     *
     *  返回role列表的视图
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roles = collect($this->role->index())->toJson();
        return view('admin.auth.role.index', ['roles' => $roles]);
    }

    /**
     * 返回显示role详情的视图
     *
     * @param $role
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($role)
    {
        $role = $this->role->show($role);
        if ($role instanceof JsonResponse && $role->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.auth.role.view', ['role' => $role]);
    }

    /**
     * 根据关键字查找role
     *
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request)
    {
        return $this->role->index($request->query("query"));
    }

    /**
     * 返回创建role的视图
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.auth.role.create');
    }

    /**
     * 添加role
     *
     * @param StoreRole $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $request)
    {
        return $this->role->store($request);
    }


    /**
     * 返回编辑role的视图
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->role->show($id);
        if ($role instanceof JsonResponse && $role->getStatusCode() === 404) {
            return abort('404');
        }
        return view('admin.auth.role.edit', ['role' => $role]);
    }

    /**
     * 更新role
     *
     * @param StoreRole $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRole $request, $id)
    {
        return $this->role->renew($request, $id);
    }

    /**
     * 删除role
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->role->destroy($id);
    }

    /**
     * 删除role集合
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function multipleDestroy(Request $request)
    {
        return $this->role->destroy($request->all());
    }

}
