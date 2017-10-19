@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-user"></i>用户信息
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <section class="df-user-wrapper">
        <div class="avatar-wrapper">
            <img class="avatar" src="{{ $user->avatar }}"/>
        </div>
        <div class="df-user">
            <div class="item">
                <label>ID</label>
                <p>{{ $user->id }}</p>
            </div>
            <div class="item">
                <label>用户名</label>
                <p>{{ $user->name }}</p>
            </div>
            <div class="item">
                <label>昵称</label>
                <p>{{ $user->profile->nickname }}</p>
            </div>
            <div class="item">
                <label>Email</label>
                <p>
                    @if ($user->email)
                        {{ $user->email }}
                    @else
                        <span class="default">未设置</span>
                    @endif
                </p>
            </div>
            <div class="item">
                <label>Mobile</label>
                <p>
                    @if ($user->mobile)
                        {{ $user->mobile }}
                    @else
                        <span class="default">未设置</span>
                    @endif
                </p>
            </div>
            <div class="item">
                <label>破壳日</label>
                <p>{{ $user->created_at }}</p>
            </div>
        </div>
    </section>
@endsection