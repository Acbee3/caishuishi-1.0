<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>财税狮智能财税业务平台</title>
    <link rel="stylesheet" href="{{url("agent/common/css/reset.css")}}">
    <link rel="stylesheet" href="{{url("agent/common/fonts/iconfont.css")}}">

    <!--header-->
    <link rel="stylesheet" href="{{url("agent/common/css/header.css")}}">
    <link rel="stylesheet" href="{{url("agent/common/layui/css/layui.css")}}">
    <link rel="stylesheet" href="{{url("agent/views/css/main.css")}}">
    <link rel="stylesheet" href="{{url("agent/common/css/table.css")}}">
    <link rel="stylesheet" href="{{url("agent/common/css/agent_center_table.css?v=2018082301")}}">
    <link rel="stylesheet" href="{{url("agent/common/css/agent/zTreeStyle.css")}}" type="text/css">
    <link rel="stylesheet" href="{{url("agent/common/css/agent/officer.css")}}">

    <link rel="shortcut icon" href="{{url("agent/logoicon.png")}}">

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="{{url("agent/common/js/html5shiv.min.js")}}">
    <link rel="stylesheet" href="{{url("agent/common/js/respond.min.js")}}">
    <![endif]-->

    {{--<script src="{{url("agent/common/js/vue.min.js")}}"></script>--}}
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="{{url("agent/common/js/vue-resource.js")}}"></script>
    {{--<script src="{{url("agent/common/js/jquery-2.2.4.js")}}"></script>--}}
    <script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <!--<script src="https://cdn.bootcss.com/echarts/2.2.0/echarts.js"></script>-->
    <!-- 替换echarts需要测试  可能会影响功能  -->
    <script src="{{url("agent/common/js/echarts.min.js")}}"></script>
    <script src="{{url("agent/common/js/layer.js")}}"></script>

    <script type="text/javascript" src="{{url("agent/js/agent/jquery.ztree.core-3.5.js")}}"></script>
    <script type="text/javascript" src="{{url("agent/js/agent/jquery.ztree.exedit-3.5.js")}}"></script>
    <script type="text/javascript" src="{{url("agent/js/agent/roletree.js")}}"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });
    </script>
    <style type="text/css">
        .container_agerant table td {font-size: 12px!important;}
    </style>
</head>
<body>
<div class="container_agerant" style="width: 100%; height: 100%;">
    <table id="tableWrapper">
        <colgroup>
            <col width="220">
            <col width="500">
        </colgroup>
        <thead>
        <tr>
            <th>客户编码</th>
            <th>客户名称</th>
            @foreach($role_lists as $v)
                <th>角色: {{$v->role_name}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody class="auth_data_list">
        @foreach($data as $v)
            <tr>
                <td>{{$v->company_code}}</td>
                <td>{{$v->company_name}}</td>
                @foreach($v->roles_arr as $vr)
                    <td class="add_auth_user"><span class="names_{{$vr->id}}_{{$v->id}}">{{$vr->set_names}}</span><div class="fr auth_btn" rid="{{$vr->id}}" cid="{{$v->id}}">+</div></td>
                @endforeach
            </tr>
        @endforeach

        </tbody>
    </table>

    <!-- 分页 -->
    <nav>
        @if (!empty($links_data))
            {{ $links_data->links() }}
        @endif
    </nav>
</div>
<div id="auth_info_box" style="display: none;">
    <div class="auth_checked_h2">已授权人员：</div>
    <div class="auth_checked_con"></div>
    <div class="auth_cancheck_h2">可授权人员：</div>
    <div class="checkbox"></div>
</div>
</body>
</html>