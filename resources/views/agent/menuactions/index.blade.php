@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/menuactions")}}" class="cur">菜单路由管理</a>

            <a href="{{ URL::route('agent.menuactions.create') }}" class="addUser">添加菜单路由</a>
        </div>


        <!-- 列表内容 -->
        <table id="tableWrapper">
            {{--<colgroup>
                <col width="150">
                <col width="150">
                <col width="150">
                <col width="300">
                <col>
            </colgroup>--}}
            <thead>
            <tr>
                <th class="width6">ID</th>
                <th class="width12">所属菜单ID</th>
                <th class="width12">链接名称</th>
                <th class="width14">链接路由</th>
                <th class="width10">父级ID</th>
                <th class="width10">子级ID</th>
                <th class="width14">应用此链接角色</th>
                <th class="width10">启用状态</th>
                <th class="width12">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->menu_id}}</td>
                    <td>{{$v->action_name}}</td>
                    <td>{{$v->action_route}}</td>
                    <td>{{$v->parent_id}}</td>
                    <td>{{$v->child_ids}}</td>
                    <td>{{$v->role_ids}}</td>
                    <td>@if( $v->status == 'yes') 正常 @else <i>停用</i> @endif</td>
                    <td>
                        <a href="{{ URL::route('agent.menuactions.edit',array('id'=>$v->id)) }}" class="iconfont editor">&#xe606;</a>
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