@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>编辑Role
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <role-form role="{{ $role }}"></role-form>
@endsection