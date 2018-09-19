@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/menuactions")}}" class="cur">菜单路由管理</a>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <div class="box-body col-xs-6">
                            <div class="form-group {{ $errors->has('menu_id') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 菜单位置
                                </label>

                                <div class="col-sm-10">
                                    <select name="menu_id" class="form-control" style="width:auto;">
                                        <option value="">请选择</option>
                                        @foreach($menus as $key=>$v)
                                            <option value="{{$v->id}}" >{{$v->menu_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('action_name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 链接名称
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="action_name"  placeholder="链接名称" value="{{ old('action_name')}}">
                                    @if ($errors->has('action_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('action_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('action_route') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 链接路由
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="action_route"  placeholder="链接路由" value="{{ old('action_route')}}">
                                    @if ($errors->has('action_route'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('action_route') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 启用状态
                                </label>

                                <div class="col-sm-10">
                                    <select name="status" class="form-control" style="width:auto;">
                                        <option value="yes">启用</option>
                                        <option value="no">停用</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 父级ID
                                </label>

                                <div class="col-sm-10">
                                    <select name="parent_id" class="form-control" style="width:auto;">
                                        <option value="0">请选择</option>
                                        @foreach($menu_actions as $key=>$v)
                                            <option value="{{$v->id}}">@if($v->parent_id != 0)&nbsp;&nbsp;@endif {{$v->action_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 菜单应用角色
                                </label>

                                <div class="col-sm-10">
                                    <div class="checkbox">
                                        @foreach($rolelist as $k => $v)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="role_ids[]" value="{{$v['id']}}" >
                                                {{$v['role_name']}}
                                            </label>
                                        @endforeach
                                    </div>

                                </div>
                            </div>

                            <div class="form-group form-group-btns">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-instagram form-control blue">提　　交</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection