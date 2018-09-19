<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>财税狮用户管理系统| Dashboard</title>
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

  <link rel="stylesheet" href="{{url("admin/css/_all-skins.min.css")}}">
  <link rel="stylesheet" href="{{url("admin/css/skin-yellow.min.css")}}">

  <!-- Morris chart -->
  <link rel="stylesheet" href="{{url("admin\morris.js\morris.css")}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{url("admin\jvectormap\jquery-jvectormap.css")}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{url("admin\bootstrap-datepicker\dist\css\bootstrap-datepicker.min.css")}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{url("admin\bootstrap-daterangepicker\daterangepicker.css")}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{url("admin\bootstrap-wysihtml5\bootstrap3-wysihtml5.min.css")}}">

  <link rel="stylesheet" href="{{url("admin/css/style.css")}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- jQuery 3 -->
  <script src="{{url("admin\js\jquery.min.js")}}"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>CFS</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>智能财税系统</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/admin/img/avatar5.png" class="user-image" alt="User Image">
              <span class="hidden-xs">{{\Auth()->guard('admin')->user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/admin/img/avatar5.png" class="img-circle" alt="User Image">
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/admin/admin/update" class="btn btn-default btn-flat">修改密码</a>
                </div>
                <div class="pull-right">
                  <a href="/admin/login/logout" class="btn btn-default btn-flat">退出</a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="@if(in_array(request()->path(),["admin/index/index"])) active @endif treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>仪表盘</span>
          </a>
          <ul class="treeview-menu">
            <li class="@if(request()->path()== "admin/index/index" ) active @endif"><a href="/admin/index/index"><i class="fa fa-circle-o"></i> 仪表盘</a></li>
          </ul>
        </li>

        <li class="@if(in_array(request()->path(),["admin/agent/index"])) active @endif treeview">
          <a href="#">
            <i class="fa fa-user-o"></i>
            <span>代账公司管理</span>
          </a>
          <ul class="treeview-menu">
            <li class="@if(request()->path()== "admin/agent/index" )active @endif"><a href="/admin/agent/index"><i class="fa fa-circle-o"></i> 代账公司列表</a></li>
            <li class="@if(request()->path()== "admin/company/index" )active @endif"><a href="/admin/company/index"><i class="fa fa-circle-o"></i> 企业主列表</a></li>

          </ul>
        </li>


        <li class="@if(in_array(request()->path(),["admin/user/index"])) active @endif treeview">
          <a href="#">
            <i class="fa fa-user-o"></i>
            <span>用户管理</span>
          </a>
          <ul class="treeview-menu">
            <li class="@if(request()->path()== "admin/user/index" )active @endif"><a href="/admin/user/index"><i class="fa fa-circle-o"></i> 用户列表</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  @yield('content')
  <!-- /.content-wrapper -->


  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2018-2020 <a href="#">财税狮</a>.</strong> All rights
    reserved.
  </footer>
</div>
<!-- ./wrapper -->


<!-- jQuery UI 1.11.4 -->
<script src="{{url("admin\js\jquery-ui.min.js")}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url("admin\js\bootstrap.min.js")}}"></script>
<!-- Sparkline -->
<script src="{{url("admin/jquery-sparkline/dist/jquery.sparkline.min.js")}}"></script>
<!-- jvectormap -->
<script src="{{url("admin/jvectormap/jquery-jvectormap-1.2.2.min.js")}}"></script>
<script src="{{url("admin/jvectormap/jquery-jvectormap-world-mill-en.js")}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{url("admin/jquery-knob/dist/jquery.knob.min.js")}}"></script>
<!-- daterangepicker -->
<script src="{{url("admin/moment/min/moment.min.js")}}"></script>
<script src="{{url("admin/bootstrap-daterangepicker/daterangepicker.js")}}"></script>
<!-- datepicker -->
<script src="{{url("admin/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js")}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{url("admin\bootstrap-wysihtml5\bootstrap3-wysihtml5.all.min.js")}}"></script>
<!-- Slimscroll -->
<script src="{{url("admin\jquery-slimscroll\jquery.slimscroll.min.js")}}"></script>
<!-- FastClick -->
<script src="{{url("admin/fastclick/lib/fastclick.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{url("admin\js\adminlte.min.js")}}"></script>

<script type='text/javascript' src='{{ asset('/admin/js/ajaxfileupload.js') }}'></script>

<script>
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>
</body>
</html>
