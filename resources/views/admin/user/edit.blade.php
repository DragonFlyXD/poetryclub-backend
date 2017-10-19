@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>编辑用户
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <edit-user user="{{ $user }}"></edit-user>
@endsection