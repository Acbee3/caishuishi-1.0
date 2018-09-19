@extends('admin.layouts.admin')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            用户中心
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">用户中心</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <form method="get" action="?">
                            <div class="col-sm-1">
                                <label>姓名</label>
                                <input class="form-control" name="name" value="{{isset($request->name)?$request->name:''}}">
                            </div>

                            <div class="col-sm-1">
                              <label>工号</label>
                              <input class="form-control" name="job_number" value="{{isset($request->job_number)?$request->job_number:''}}">
                            </div>

                            <div class="col-sm-1">
                                <label>手机号</label>
                                <input class="form-control" name="phone" value="{{isset($request->phone)?$request->phone:''}}">
                            </div>

                            <div class="col-sm-1">
                                <label>状态</label>
                                <select name="status" class="form-control">
                                    <option value="">请选择</option>
                                    @foreach(\App\User::$statusLables as $key=>$v)
                                        <option value="{{$key}}" @if(isset($request->status) && $request->status == $key)@endif>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <label>最后登录时间</label>
                                <div class="date-input">
                                    <input class="datepicker" name="login_at_start" placeholder="开始时间" value="{{isset($request->login_at_start)?$request->login_at_start:''}}">
                                    <input class="datepicker" name="login_at_end" placeholder="结束时间" value="{{isset($request->login_at_end)?$request->login_at_end:''}}">
                                </div>

                            </div>

                            <div class="col-sm-2">
                                <label>创建时间</label>
                                <div class="date-input">
                                    <input class="datepicker" name="created_at_start" placeholder="开始时间" value="{{isset($request->created_at_start)?$request->created_at_start:''}}">
                                    <input class="datepicker" name="created_at_end" placeholder="结束时间" value="{{isset($request->created_at_end)?$request->created_at_end:''}}">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" class="btn btn-info search-btn">搜索</button>
                            </div>
                            <div class="col-sm-1">
                                <a href="/admin/user/create" class="btn btn-google search-btn" style="float: right;">添加</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" style="margin-top: 20px;">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>用户名</th>
                                <th>工号</th>
                                <th>手机号</th>
                                <th>状态</th>
                                <th>登录时间</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                            <tr>
                                <td>{{$v->id}}</td>
                                <td>{{$v->name}}</td>
                                <td>{{$v->job_number}}</td>
                                <td>{{$v->phone}}</td>
                                <th>{{key_exists($v->status,\App\User::$statusLables)?\App\User::$statusLables[$v->status]:''}}</th>
                                <td>{{$v->login_at}}</td>
                                <td>{{$v->created_at}}</td>
                                <td>
                                    <a href="/admin/user/edit?id={{$v->id}}" class="btn btn-info">修改</a>
                                    @if($v->status == \App\User::STATUS_1)
                                        <a href="/admin/user/freeze?id={{$v->id}}&status={{\App\User::STATUS_5}}" class="btn btn-danger">冻结</a>
                                    @else
                                        <a href="/admin/user/freeze?id={{$v->id}}&status={{\App\User::STATUS_1}}" class="btn btn-success">解禁</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <!-- 分页 -->
                        <nav>
                            @if (!empty($data))
                                {!! $data->appends($request->toArray())->render() !!}
                            @endif
                        </nav>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

</div>



@endsection