@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-users"></i>用户管理
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <user-table
        paginate="{{ $users }}"
    ></user-table>
@endsection