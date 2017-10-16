<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>诗词小筑-后台管理系统 : )</title>
    <link rel="stylesheet" href="{{ mix('backend/css/app.css')  }}">
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token()
        ]); ?>
    </script>
</head>
<body>
<div id="app">
    <header class="df-header">
        <div class="logo">
            <a href="{{ url('admin') }}">诗词❤️小筑</a>
        </div>
    </header>
    <div class="df-main">
        <el-menu class="df-nav" theme="dark">
            <a href="{{ url('admin') }}">
                <el-menu-item index="{{ url('admin') }}">
                    <i class="fa fa-dashboard"></i>Dashboard
                </el-menu-item>
            </a>
            <a href="{{ url('admin/poem') }}">
                <el-menu-item index="{{ url('admin/poem') }}">
                    <i class="fa fa-book"></i>诗文管理
                </el-menu-item>
            </a>
            <a href="{{ url('admin/appreciation') }}">
                <el-menu-item index="{{ url('admin/appreciation') }}">
                    <i class="fa fa-pencil-square-o"></i>品鉴管理
                </el-menu-item>
            </a>
            <a href="{{ url('admin/category') }}">
                <el-menu-item index="{{ url('admin/category') }}">
                    <i class="fa fa-cubes"></i>分类管理
                </el-menu-item>
            </a>
            <a href="{{ url('admin/user') }}">
                <el-menu-item index="{{ url('admin/user') }}">
                    <i class="fa fa-users"></i>用户管理
                </el-menu-item>
            </a>
        </el-menu>
        <div class="df-content">
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ mix('backend/js/app.js') }}"></script>
</body>
</html>