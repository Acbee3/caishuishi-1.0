<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>财税狮管理系统</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{url("admin/css/bootstrap.min.css")}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{url("admin/css/font-awesome.min.css")}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{url("admin/css/ionicons.min.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url("admin/css/AdminLTE.min.css")}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{url("admin/css/plugins/iCheck/square/blue.css")}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>财税狮</b>PAAS平台</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">智能财税服务系统后台</p>
    <form action="{{url("admin/login/login")}}" method="post">
      {{ csrf_field() }}
      <div class="form-group has-feedback {{ !empty($errors) && $errors->has('phone') ? ' has-error' : '' }}">
        <input type="text" name="phone" class="form-control" placeholder="username" value="{{ old('phone') }}">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        @if ( !empty($errors) && $errors)
          <span class="help-block">
              <strong style="color: red;">{{ $errors->first() }}</strong>
          </span>
        @elseif( !empty($errors) && $errors->has('phone'))
          <span class="help-block">
              <strong style="color: red;">{{ $errors->first('phone') }}</strong>
          </span>
        @endif

      </div>
      <div class="form-group has-feedback {{  !empty($errors) && $errors->has('password') ? ' has-error' : '' }}">
        <input type="password" name="password" class="form-control" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        @if ( !empty($errors) && $errors->has('password'))
          <span class="help-block">
              <strong style="color: red;">{{ $errors->first('password') }}</strong>
          </span>
        @endif
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">登 录</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="{{url("admin/js/jquery.min.js")}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url("admin/js/bootstrap.min.js")}}"></script>
<!-- iCheck -->
<script src="{{url("admin/css/plugins/iCheck/icheck.min.js")}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
