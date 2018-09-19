@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/rolelists")}}" class="cur">角色权限控制管理</a>

            <a href="{{ URL::route('agent.rolelists.create') }}" class="addUser" >添加权限控制</a>
        </div>

        <!-- 列表内容 -->
        <table id="tableWrapper">
            <thead>
            <tr>
                <th class="width10">ID</th>
                <th class="width20">权限名称</th>
                <th class="width20">权限代码</th>
                <th class="width20">启用状态</th>
                <th class="width10">父级ID</th>
                <th class="width10">排序</th>
                <th class="width10">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data as $v)
                <tr>
                    <td>{{$v->id}}</td>
                    <td>{{$v->action_name}}</td>
                    <td>{{$v->action_code}}</td>
                    <td>@if( $v->status == 'yes') 已启用 @else <i>停用</i> @endif</td>
                    <td>{{$v->parent_id}}</td>
                    <td>{{$v->sort_order}}</td>
                    <td class="iconMenu">
                        <a href="{{ URL::route('agent.rolelists.edit',array('id'=>$v->id)) }}" class="iconfont editor">&#xe606;</a>
                        @if ( $v->action_code == 'all' || $v->parent_id != '0')

                        @else
                            <a href="{{ URL::route('agent.rolelists.addchild',array('id'=>$v->id, 'parent_id'=>$v->id)) }}" class="iconfont addProject">&#xe60c;</a>
                        @endif

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