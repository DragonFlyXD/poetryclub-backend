<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HTTP ERROR - 诗词小筑 :)</title>
    <link rel="stylesheet" href="{{ mix('backend/css/app.css')  }}">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'monaco', 'menlo', serif, sans-serif;
            font-size: 16px;
            background-color: #fff;
        }

        .error {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .main {
            padding: 50px;
            border: 1px solid #EFF2F7;
            border-radius: 3px;
            font-size: 2em;
            text-align: center;
            color: #8492A6;
        }

        .main .rocket {
            font-size: 4em;
            color: #42b983;
        }
    </style>
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token()
        ]); ?>
    </script>
</head>
<body>
    <div id="app" style="height: 100%">
        <div class="error">
            <div class="main">
                <div class="title">@yield('title')</div>
                <p class="body">@yield('body')</p>
                <i class="fa fa-rocket rocket"></i>
            </div>
        </div>
    </div>
</div>
<script src="{{ mix('backend/js/app.js') }}"></script>
</body>
</html>