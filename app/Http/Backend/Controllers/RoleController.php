<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
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
        return view('admin.auth.role.index');
    }

}
