@extends('agent.layouts.agent')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/cfs/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/cfs/users")}}" class="cur">用户管理</a>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{$model->id}}">
                        <div class="box-body">
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 用户名
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"  placeholder="用户名" value="{{ $model->name}}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('role_id') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 角色
                                </label>

                                <div class="col-sm-10">
                                    <select name="role_id" class="form-control" style="width:auto;">
                                        @foreach($roles as $key=>$v)
                                            <option value="{{$v->id}}"  @if(isset($model->role_id) && $model->role_id == $v->id) selected @endif>{{$v->role_name}}</option>
                                        @endforeach
                                    </select>
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