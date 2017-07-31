<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {
        return view('admin.home');
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        // 注销用户 session 数据
        auth()->logout();
        // 重定向至登录页
        return redirect()->intended('admin/login');
    }

}
