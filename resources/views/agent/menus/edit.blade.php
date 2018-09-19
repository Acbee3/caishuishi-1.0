@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/menus")}}" class="cur">菜单位置管理</a>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <div class="box-body">
                            <div class="form-group {{ $errors->has('menu_name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 菜单名称
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="menu_name"  placeholder="菜单名称" value="{{ $model->menu_name}}">
                                    @if ($errors->has('menu_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('menu_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('menu_code') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 菜单代码
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="menu_code"  placeholder="菜单代码" value="{{ $model->menu_code}}">
                                    @if ($errors->has('menu_code'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('menu_code') }}</strong>
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
                                        <option value="">请选择</option>
                                        <option value="yes" @if(isset($model->status) && $model->status == 'yes') selected @endif>启用</option>
                                        <option value="no" @if(isset($model->status) && $model->status == 'no') selected @endif>停用</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('role_ids') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 菜单应用角色
                                </label>

                                <div class="col-sm-10">
                                    <div class="checkbox">
                                        <div style="width: 100%; height:auto; min-height: 40px; line-height: 40px; border-bottom: 1px solid #ccc; color:#262626; margin-bottom: 10px;">
                                            当前已授权角色：<br>
                                            {{$role_names}}
                                        </div>
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
                                    <button type="submit" class="btn btn-instagram form-control blue">更　　新</button>
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