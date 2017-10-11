@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-cubes"></i>分类管理
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <category-table paginate="{{ $categories }}"></category-table>
@endsection