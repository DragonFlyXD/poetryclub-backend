@extends('layouts.email')
@section('content')
    <div class="text-line" style="
        position: relative;
        font-size: 1em;
        text-align: center;
        letter-spacing: 1px;
        color: #42b983;
        ">重置密码
    </div>
    <p>嗨,远方来的朋友,<span style="color: #42b983">【{{ $name or '' }}】</span>,欢迎来到召唤峡谷。</p>
    <p>我是这儿的密纹召唤师,臭美昭著的道枚恋_(:зゝ∠)_。</p>
    <p>我感受到了你的密纹之力,让我来把它重新召唤...</p>
    <a class="center-block" href="{{ $verify_url or '#' }}" target="_blank" style="
        margin: 20px;
        border: 1px solid #42b983;
        background-color: #fff;
        display: block;
        padding: 10px;
        text-decoration: none;
        letter-spacing: 5px;
        font-size: 1em;
        border-radius: 10px;
        color:  #42b983;
        transition: all .5s;">重置您的密码</a>
    <p>最好去镇子里买瓶记忆药水,别让密纹之力再沉睡了~</p>
    <p>来日方长,日后不见...</p>
    <div class="text-line" style="
        position: relative;
        font-size: 1em;
        text-align: center;
        letter-spacing: 1px;
        color: #42b983;
        ">很高兴遇见你
    </div>
@endsection