@extends('layouts.email')
@section('content')
    <div class="text-line" style="
        position: relative;
        margin-bottom: 20px;
        padding: 0 200px;
        font-size: 1em;
        text-align: center;
        letter-spacing: 1px;
        color: #42b983;
        ">网站反馈
    </div>
    <section style="
        text-align: left;
    ">
        <h2 style="
            margin-bottom: 20px;
        ">反馈主题: <span style="color: #42b983;">{{ $subject or '咔咔咔,不能无主题。' }}</span></h2>
        {!! $body or '咔咔咔,不能无内容。' !!}
    </section>
@endsection