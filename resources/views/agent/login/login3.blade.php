<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>登录</title>

{{--css内容区--}}
<!--公用-->
    <link rel="stylesheet" href="/common/css/reset.css">
    <link rel="stylesheet" href="/common/fonts/iconfont.css">
    <link rel="stylesheet" href="/common/layui/css/layui.css">
    <link rel="stylesheet" href="/common/css/table.css">
    <!--登录-->
    <link rel="stylesheet" href="/css/login/login.css">

    <!--[if lt IE 9]>
    <script src="/common/js/html5shiv.min.js"></script>
    <script src="/common/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="login">
    <form method="POST" action="{{ url('agent/login/login') }}">
        <!-- 登录窗口 -->

        <div class="loginWindow">
            <div class="header">
                <div class="personal active">
                    <span>普通登录</span>
                </div>
                <!-- <div class="enterprise">
                  <span>企业登录</span>
                </div> -->
            </div>

            <div class="content">
                <input type="text" placeholder="用户名/手机号" name="phone" value="{{ old('phone') }}" required>
                <input type="password" placeholder="密码" name="password" required>
                <div class="messageNum">
                    <input type="text">
                    <a href="javascript:;">短信验证码</a>
                </div>
                <div class="forget">
                    <div>
                        <input type="checkbox" class="fuxuan">
                        <span>记住密码</span>
                    </div>
                    <a href="javascript:;">忘记密码?</a>
                </div>
            </div>
            <div class="denglu">
                {{csrf_field()}}
                <button type="submit" @click="loginBtnClick" :disabled="disabled">@{{ btnlabel }}</button>
            </div>
        </div>
    </form>
</div>

{{--<script src="/common/js/vue.min.js"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
{{--<script src="/common/js/jquery-2.2.4.js"></script>--}}
<script src="/common/layui/layui.js" charset="utf-8"></script>
<script src="/common/vue-resource/dist/vue-resource.js"></script>
<script>

    layui.use('layer', function () {
        var errors = '{{ !empty($errors->first()) ? $errors->first() : '' }}';
        if (errors != '') {
            layer.msg(errors, {icon: 2, time: 2000, area: ['240px', ''],});
        }
    });

    new Vue({
        'el': '.login',
        data: {
            disabled: false,
            btnlabel: '登录'
        },
        methods: {
            loginBtnClick: function () {
                this.disabled = true;
                this.btnlabel = '登录中...';
                return false;
            }
        }
    })
</script>
</body>
</html>