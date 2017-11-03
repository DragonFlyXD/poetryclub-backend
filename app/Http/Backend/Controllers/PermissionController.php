<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
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
        return view('admin.auth.permission.index');
    }

}
