@extends('layouts.admin')

@section('breadcrumb')
    <el-breadcrumb-item>
        <a class="custom-a" href="{{  url()->current() }}">
            <i class="fa fa-edit"></i>品鉴详情
        </a>
    </el-breadcrumb-item>
@endsection

@section('content')
    <div class="df-appreciationView">
        <div class="title">{{ $appreciation['title'] }}</div>
        @if($appreciation['poem']['title'])
            <div class="poem">
                来自诗文: <a href="/admin{{ $appreciation['poemUrl'] }}">{{ $appreciation['poem']['title'] }}</a>
            </div>
        @else
            <div class="poem">
                来自诗文: <a href="#">NULL</a>
            </div>
        @endif
        <div class="info">
            <div class="item">
                <i class="fa fa-user"></i><a href="/admin{{ $appreciation['profileUrl'] }}">{{ $appreciation['authorName'] }}</a>
            </div>
            <div class="item">
                <i class="fa fa-eye"></i>{{ $appreciation['pageviews_count'] }}
            </div>
            <div class="item">
                <i class="fa fa-clock-o"></i>{{ $appreciation['publish_time'] }}
            </div>
        </div>
        <div class="main">
            <div class="body">{!! $appreciation['body'] !!}</div>
        </div>
    </div>
@endsection