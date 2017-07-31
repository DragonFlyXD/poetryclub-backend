@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>编辑诗文
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <data-form
        :is_edit="true"
        edit_form="{{ $poem }}"
    ></data-form>
@endsection