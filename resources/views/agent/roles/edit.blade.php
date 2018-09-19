@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/roles")}}" class="cur">角色管理</a>
        </div>


                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <div class="box-body">
                            <div class="form-group {{ $errors->has('role_name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 角色
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="role_name"  placeholder="角色" value="{{ $model->role_name}}">
                                    @if ($errors->has('role_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role_name') }}</strong>
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

                            <div class="form-group {{ $errors->has('role_list') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 权限列表
                                </label>

                                <div class="col-sm-10">
                                    <div class="checkbox">
                                        @foreach($actionlist as $k => $v)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="{{$v['action_code']}}" name="actions[]" value="{{$v['action_code']}}" vid="{{$v['id']}}" @if($v['action_checked'] == 'true') checked @endif >
                                                {{$v['action_name']}}
                                            </label>

                                            @if ($v['cat_arr'])
                                                @foreach($v['cat_arr'] as $k2 => $v2)
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" id="{{$v2['action_code']}}" name="actions[]" value="{{$v2['action_code']}}" vid="{{$v2['id']}}" @if($v2['action_checked'] == 'true') checked @endif >
                                                        {{$v2['action_name']}}
                                                    </label>
                                                @endforeach
                                            @endif
                                            <br>
                                        @endforeach
                                    </div>

                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('role_desc') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;"></span> 描述
                                </label>

                                <div class="col-sm-10">
                                    <textarea name="role_desc" class="form-control" cols="30" rows="3">{{ $model->role_desc}}</textarea>
                                    @if ($errors->has('role_desc'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('role_desc') }}</strong>
                                    </span>
                                    @endif
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
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection