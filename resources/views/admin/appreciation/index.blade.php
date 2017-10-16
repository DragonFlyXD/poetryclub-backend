@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-pencil-square-o"></i>品鉴管理
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <appreciation-table
            paginate="{{ $appreciations }}"
    ></appreciation-table>
@endsection