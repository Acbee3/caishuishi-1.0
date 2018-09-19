@extends('admin.layouts.admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            创建用户
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">创建用户</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <div class="box-body col-xs-6">
                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 姓名
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"  placeholder="姓名" value="{{ old('name')}}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('job_number') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 工号
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="job_number"  placeholder="工号" value="{{ old('job_number')}}">
                                    @if ($errors->has('job_number'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('job_number') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 手机
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="phone"  placeholder="手机" value="{{ old('phone')}}">
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 密码
                                </label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password"  placeholder="密码" value="{{ old('password')}}">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 确认密码
                                </label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password_confirmation"  placeholder="确认密码" value="{{ old('password_confirmation')}}">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">
                                    勾选模块管理员
                                </label>

                                <div class="col-sm-10">
                                    <label><input type="checkbox" name="qa" value="1"> 知识库系统&nbsp;&nbsp;</label>
                                    <label><input type="checkbox" name="business" value="1"> 业务系统&nbsp;&nbsp;</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-instagram form-control blue">添  加</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.col -->
        </div>
    </section>

</div>



@endsection