@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/roles")}}" class="cur">角色管理</a>

            <a href="{{ URL::route('agent.roles.create') }}" class="addUser" >添加角色</a>
        </div>


        <!-- 列表内容 -->
        <table id="tableWrapper">
            {{--<colgroup>
                <col width="60">
                <col width="150">
                <col width="150">
                <col width="300">
                <col width="500">
            </colgroup>--}}
            <thead>
            <tr>
                <th class="width6">ID</th>
                <th class="width20">角色</th>
                <th class="width20">启用状态</th>
                <th class="width30">描述</th>
                <th class="width12">权限</th>
                <th class="width12">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->role_name}}</td>
                    <td>@if( $v->status == 'yes') 已启用 @else <i>停用</i> @endif</td>
                    <td>{{$v->role_desc}}</td>
                    <td style="word-wrap:break-word;">{{$v->role_list}}</td>
                    <td>
                        <a href="{{ URL::route('agent.roles.edit',array('id'=>$v->id)) }}" class="iconfont editor">&#xe606;</a>
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