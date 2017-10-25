@extends('layouts.admin')

@section('content')
    <div class="df-home">
        <div class="statistics">
            <div class="box">
                <i class="fa fa-users"></i>
                <count-to :endval="{{ $data['user_count'] }}"></count-to>
            </div>
            <div class="box">
                <i class="fa fa-book"></i>
                <count-to :endval="{{ $data['poem_count'] }}"></count-to>
            </div>
            <div class="box">
                <i class="fa fa-pencil-square-o"></i>
                <count-to :endval="{{ $data['appreciation_count'] }}"></count-to>
            </div>
        </div>
        <div class="main">
            <div class="lately">
                <div class="billboard">
                    <h3><i class="fa fa-book"></i>POEM</h3>
                </div>
                @foreach($data['poems'] as $poem)
                    <div class="item">
                        <a class="title tdu" href="{{ url('/admin/poem/'.$poem['id']) }}">{{ $poem['title'] }}</a>
                        <div class="info">
                            <a class="name tdu"
                               href="{{ url('/admin/user/'.$poem['user']['name']) }}">
                                {{ $poem['profile']['nickname'] ?: $poem['user']['name'] }}
                            </a>
                            <div class="publish-time">{{ \Carbon\Carbon::parse($poem['created_at'])->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="lately">
                <div class="billboard">
                    <h3><i class="fa fa-pencil-square-o"></i>APPRECIATION</h3>
                </div>
            @foreach($data['appreciations'] as $appreciation)
                    <div class="item">
                        <a class="title tdu" href="{{ url('/admin/appreciation/'.$appreciation['id']) }}">{{ $appreciation['title'] }}</a>
                        <div class="info">
                            <a class="name tdu"
                               href="{{ url('/admin/user/'.$appreciation['user']['name']) }}">
                                {{ $appreciation['profile']['nickname'] ?: $appreciation['user']['name'] }}
                            </a>
                            <div class="publish-time">{{ \Carbon\Carbon::parse($appreciation['created_at'])->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="lately">
                <div class="billboard">
                    <h3><i class="fa fa-user-circle-o"></i>USER</h3>
                </div>
            @foreach($data['users'] as $user)
                    <div class="item">
                        <a href="{{ url('/admin/user/'.$user['name']) }}">
                            <img class="avatar" src="{{ $user['avatar'] }}" alt="avatar">
                        </a>
                        <div class="info">
                            <a class="name tdu" href="{{ url('/admin/user/'.$user['name']) }}">
                                {{ $user['profile']['nickname'] ?: $user['name'] }}
                            </a>
                            <div class="publish-time">{{ \Carbon\Carbon::parse($user['created_at'])->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
