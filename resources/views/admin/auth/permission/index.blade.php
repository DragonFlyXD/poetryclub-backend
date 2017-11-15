@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{ url()->current() }}">
            <i class="fa fa-ban"></i>Permission
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <permission-table paginate="{{ $permissions }}"></permission-table>
@endsection