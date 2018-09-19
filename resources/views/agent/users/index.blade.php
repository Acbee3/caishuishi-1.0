@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/users")}}" class="cur">用户管理</a>

            <a href="{{ URL::route('agent.users.create') }}" class="addUser">添加用户</a>
        </div>


        <!-- 列表内容 -->
        <table id="tableWrapper">
            {{--<colgroup>
                <col width="50">
                <col width="130">
                <col width="130">
                <col width="130">
                <col width="200">
                <col width="70">
                <col width="130">
            </colgroup>--}}
            <thead>
            <tr>
                <th class="width2">ID</th>
                <th class="width8">用户名</th>
                <th class="width6">工号</th>
                <th class="width10">手机号</th>
                <th class="width12">邮件</th>
                <th class="width6">状态</th>
                <th class="width10">角色</th>
                <th class="width10">代账公司</th>
                <th class="width6">登录次数</th>
                <th class="width12">登录时间</th>
                <th class="width12">创建时间</th>
                <th class="width6">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->name}}</td>
                    <td>{{$v->job_number}}</td>
                    <td>{{$v->phone}}</td>
                    <td>{{$v->email}}</td>
                    <td>@if( $v->status == 'yes') 正常 @else <i>停用</i> @endif</td>
                    <td title="权限: {{$v->rs_list}}">{{$v->rs_name}}</td>
                    <td>{{$v->at_name}}</td>
                    <td>{{$v->logintimes}}</td>
                    <td>{{$v->login_at}}</td>
                    <td>{{$v->created_at}}</td>
                    <td>
                        <a href="{{ URL::route('agent.users.edit',array('id'=>$v->id)) }}" class="iconfont editor">&#xe606;</a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <!-- 分页 -->
        <nav>
            @if (!empty($data))
                {{ $data->links() }}
            @endif
        </nav>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection