<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .text-line::before {
            position: absolute;
            top: 10px;
            left: 0;
            height: 1px;
            width: 38%;
            background-color: #42b983;
            content: '';
        }

        .text-line::after {
            position: absolute;
            top: 10px;
            right: 0;
            height: 1px;
            width: 38%;
            background-color: #42b983;
            content: '';
        }
    </style>
</head>
<body style="
        margin: 0;
        padding: 0;
        min-height: 100%;
        font-family: 'monaco', 'manlo', Serif, sans-serif;
        font-size: 16px;
        background-color: #fff;
        ">
<div class="wrapper" style="
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);">
    <h1 class="header" style="text-align: center"><a style="
            text-decoration: none;
            color: #42b983;
            " href="http://www.dragonflyxd.com" target="_blank">诗词小筑</a></h1>
    <div class="content" style="
        margin-top: 20px;
        font-size: .8em;
        text-align: center;
        color: #8492A6;
        ">
        @yield('content')
    </div>
</div>
</body>
</html>