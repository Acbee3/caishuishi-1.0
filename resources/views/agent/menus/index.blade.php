@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/menus")}}" class="cur">菜单位置管理</a>

            <a href="{{ URL::route('agent.menus.create') }}" class="addUser" >添加菜单位置</a>
        </div>


        <!-- 列表内容 -->
        <table id="tableWrapper">
            {{--<colgroup>
                <col width="150">
                <col width="230">
                <col width="230">
                <col width="300">
                <col>
            </colgroup>--}}
            <thead>
            <tr>
                <th class="width8">ID</th>
                <th class="width20">菜单名称</th>
                <th class="width20">菜单代码</th>
                <th class="width16">启用状态</th>
                <th class="width20">应用此菜单角色</th>
                <th class="width16">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->menu_name}}</td>
                    <td>{{$v->menu_code}}</td>
                    <td>@if( $v->status == 'yes') 正常 @else <i>停用</i> @endif</td>
                    <td>{{$v->role_ids}}</td>
                    <td>
                        <a href="{{ URL::route('agent.menus.edit',array('id'=>$v->id)) }}" class="iconfont editor">&#xe606;</a>
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