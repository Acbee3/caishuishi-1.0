@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/rolelists")}}" class="cur">角色权限控制管理</a>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <div class="box-body">
                            <div class="form-group {{ $errors->has('action_name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 权限名称
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="action_name"  placeholder="权限名称" value="{{ $model->action_name}}">
                                    @if ($errors->has('action_name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('action_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('action_code') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 权限代码
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="action_code"  placeholder="权限代码" value="{{ $model->action_code}}">
                                    @if ($errors->has('action_code'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('action_code') }}</strong>
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

                            <div class="form-group {{ $errors->has('sort_order') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 排序
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" style="width: 100px;" name="sort_order"  placeholder="排序" value="{{ $model->sort_order}}">
                                    @if ($errors->has('sort_order'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('sort_order') }}</strong>
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
                </div>
            </div>
            <!-- /.col -->
        </div>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection