@extends('layouts.email')
@section('content')
    <div class="text-line" style="
        position: relative;
        font-size: 1em;
        text-align: center;
        letter-spacing: 1px;
        color: #42b983;
        ">邮箱验证
    </div>
    <p>呦,远方来的朋友,<span style="color: #42b983">【{{ $name or '' }}】</span>,欢迎来到诗词小筑。</p>
    <p>我是这儿的密纹鉴定师,大名鼎鼎的阔胡晓(\(^o^)/~)。</p>
    <p>你的第一次密纹熔炼,由我来守护!!!</p>
    <p>不要担心,那块石头没什么风险,当年我可是....balabala</p>
    <p>轻轻地,将手放在石头上,闭眼,低吟 Aal izz well</p>
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
        transition: all .5s;">激活邮箱</a>
    <div class="text-line" style="
        position: relative;
        font-size: 1em;
        text-align: center;
        letter-spacing: 1px;
        color: #42b983;
        ">很高兴遇见你
    </div>
    <p style="color:#42b983">
        哟,走之前,得去老壶家领本村外弱鸡山脉的地图,我很不放心你。
    </p>
    <p style="color: #42b983">
        ?,问我的名字么。...,在这个村子里,我叫阔胡晓(\(^o^)/~)。
    </p>
@endsection