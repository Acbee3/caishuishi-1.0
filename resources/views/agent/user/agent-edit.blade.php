@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/department/index")}}" class="cur">员工管理</a>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        <input type="hidden" name="id" value="{{$model->id}}">
                        {{ csrf_field() }}
                        <div class="box-body col-xs-6">
                            <div class="form-group twoRow {{ $errors->has('name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 登录名
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control"placeholder="登录名" value="{{  $model->name}}" disabled="disabled">
                                    <input type="hidden" class="form-control" name="name"  placeholder="登录名" value="{{  $model->name}}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group twoRow {{ $errors->has('password') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 密码
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="password"  placeholder="密码" value="">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group twoRow {{ $errors->has('true_name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 姓名
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="true_name"  placeholder="姓名" value="{{ old('true_name') ? old('true_name') : $model->true_name}}">
                                    @if ($errors->has('true_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('true_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group twoRow {{ $errors->has('phone') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 手机
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone"  placeholder="手机号" value="{{ old('phone') ? old('phone') : $model->phone}}">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('department') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 部门
                                </label>

                                <div class="col-sm-10">
                                    <select name="department" class="form-control roleSelect" style="width:auto;height: 36px; border-radius: inherit;">
                                        <option value="">请选择</option>
                                        @foreach($department as $key=>$v)
                                            <option value="{{$v->id}}" @if($model->department_id == $v->id) selected @endif >{{$v->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('role_id') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 角色
                                </label>

                                <div class="col-sm-10">
                                    <select name="role_id" class="form-control roleSelect" style="width:auto;height: 36px;border-radius: inherit;">
                                        <option value="">请选择</option>
                                        @foreach($roles as $key=>$v)
                                            <option value="{{$v->id}}" @if($model->role_id == $v->id) selected @endif >{{$v->role_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 启用状态
                                </label>

                                <div class="col-sm-10">
                                    <select name="status" class="form-control roleSelect" style="width:auto;height: 36px;border-radius: inherit;">
                                        <option value="yes" @if($model->status == "yes") selected @endif>启用</option>
                                        <option value="no" @if($model->status == "no") selected @endif>停用</option>
                                    </select>
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