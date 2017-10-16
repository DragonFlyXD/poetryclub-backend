@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>编辑品鉴
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <appreciation-form appreciation="{{ $appreciation }}" poem="{{ $poem }}"></appreciation-form>
@endsection