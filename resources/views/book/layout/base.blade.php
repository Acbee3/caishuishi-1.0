<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width">
    <title>@yield('title','进入账簿')</title>
    <link rel="stylesheet" href="/common/css/reset.css?v=2018082202">
    <link rel="stylesheet" href="/common/fonts/iconfont.css?v=2018083001">
    <link rel="stylesheet" href="/common/layui/css/layui.css">
    <link rel="stylesheet" href="/common/css/table.css">
    @yield('css')
    <!--[if lt IE 9]>
    <script src="/common/js/html5shiv.min.js"></script>
    <script src="/common/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
@yield('content')
{{--<script src="/common/js/vue.min.js"></script>--}}
{{--<script src="/common/js/jquery.min.js"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/1.7.2/jquery.min.js"></script>
{{--<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>--}}
<script src="/common/layui/layui.js" charset="utf-8"></script>
<script src="/common/vue-resource/dist/vue-resource.js"></script>
@yield('script')
<script type="text/javascript">
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });
    layui.use('form', function () {
        var form = layui.form;
    });
</script>
</body>
@yield('stuff')
</html>