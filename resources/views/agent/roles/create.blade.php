@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/roles")}}" class="cur">角色管理</a>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <div class="box-body col-xs-6">
                            <div class="form-group {{ $errors->has('role_name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 角色
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="role_name"  placeholder="角色" value="{{ old('role_name')}}">
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
                                        <option value="yes">启用</option>
                                        <option value="no">停用</option>
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
                                                <input type="checkbox" id="{{$v['action_code']}}" name="actions[]" value="{{$v['action_code']}}" vid="{{$v['id']}}">
                                                {{$v['action_name']}}
                                            </label>

                                            @if ($v['cat_arr'])
                                                @foreach($v['cat_arr'] as $k2 => $v2)
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" id="{{$v2['action_code']}}" name="actions[]" value="{{$v2['action_code']}}" vid="{{$v2['id']}}">
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
                                    <textarea name="role_desc" class="form-control" cols="30" rows="3">{{ old('role_desc')}}</textarea>
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