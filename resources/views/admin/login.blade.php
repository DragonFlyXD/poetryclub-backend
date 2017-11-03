<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>诗词小组-后台登录 : )</title>
    <link rel="stylesheet" href="{{ mix('backend/css/app.css')  }}">
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token()
        ]); ?>
    </script>
</head>
<body>
    <div id="app" style="height: 100%">
        <div class="df-login">
            <dot></dot>
            <login-form></login-form>
        </div>
    </div>
    <script src="{{ mix('backend/js/app.js') }}"></script>
</body>
</html>