@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-plus"></i>创建品鉴
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <appreciation-form></appreciation-form>
@endsection