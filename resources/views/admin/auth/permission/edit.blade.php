@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>编辑Permission
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <permission-form permission="{{ $permission }}"></permission-form>
@endsection