@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>诗文详情
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <div class="df-poemView">
        <div class="title">{{ $poem['title'] }}</div>
        <div class="info">
            <div class="item">
                <i class="fa fa-user"></i><a href="/admin{{ $poem['profileUrl'] }}">{{ $poem['authorName'] }}</a>
            </div>
            <div class="item">
                <i class="fa fa-eye"></i>{{ $poem['pageviews_count'] }}
            </div>
            <div class="item">
                <i class="fa fa-clock-o"></i>{{ $poem['publish_time'] }}
            </div>
        </div>
        <div class="main">
            <div class="body">{!! $poem['body'] !!}</div>
        </div>
    </div>
@endsection