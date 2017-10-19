@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-plus"></i>添加用户
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <create-user></create-user>
@endsection