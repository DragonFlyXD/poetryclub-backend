<?php

namespace App\Http\Backend\Controllers;

use App\Http\Controller;

class HomeController extends Controller
{

    public function index()
    {
        // 统计用户、诗文与品鉴的总数
        $data['user_count'] = (new \App\Http\Frontend\Models\User())->count();
        $data['poem_count'] = (new \App\Http\Frontend\Models\Poem())->newQueryWithoutScope('show')->count();
        $data['appreciation_count'] = (new \App\Http\Frontend\Models\Appreciation())->newQueryWithoutScope('show')->count();
        // 获取最近添加的诗文与品鉴,最近注册的用户
        $data['users'] = (new \App\Http\Frontend\Models\User())->latest()->limit(10)->get();
        $data['poems'] = (new \App\Http\Frontend\Models\Poem())->newQueryWithoutScope('show')->latest()->limit(10)->get();
        $data['appreciations'] = (new \App\Http\Frontend\Models\Appreciation())->newQueryWithoutScope('show')->latest()->limit(10)->get();
        return view('admin.home', ['data' => $data]);
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
