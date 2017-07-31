@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-book"></i>诗文管理
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <poem-table
            paginate="{{ $poems }}"
    ></poem-table>
@endsection