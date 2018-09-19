@extends('admin.layouts.admin')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                代账公司管理
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
                <li class="active">代账公司管理</li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <form method="get" action="?">
                                <div class="col-sm-2">
                                    <label>公司名称</label>
                                    <input class="form-control" name="name" value="{{isset($request->name)?$request->name:''}}">
                                </div>

                                <div class="col-sm-1">
                                    <label>联系人</label>
                                    <input class="form-control" name="contacts" value="{{isset($request->contacts)?$request->contacts:''}}">
                                </div>

                                <div class="col-sm-2">
                                    <label>联系电话</label>
                                    <input class="form-control" name="phone" value="{{isset($request->phone)?$request->phone:''}}">
                                </div>

                                <div class="col-sm-1">
                                    <label>状态</label>
                                    <select name="status" class="form-control">
                                        <option value="">请选择</option>
                                        @foreach(\App\Models\Agent::$statusLables as $key=>$v)
                                            <option value="{{$key}}" @if(isset($request->status) && $request->status == $key)@endif>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-1">
                                    <button type="submit" class="btn btn-info search-btn">搜索</button>
                                </div>
                                <div class="col-sm-1">
                                    <a href="/admin/agent/create" class="btn btn-google search-btn" style="float: right;">添加</a>
                                </div>
                            </form>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="margin-top: 20px;">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>代账公司名称</th>
                                    <th>联系人</th>
                                    <th>联系电话</th>
                                    <th>状态</th>
                                    <th>创建时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $v)
                                    <tr>
                                        <td>{{$v->id}}</td>
                                        <td>{{$v->name}}</td>
                                        <td>{{$v->contacts}}</td>
                                        <td>{{$v->phone}}</td>
                                        <th>{{key_exists($v->status,\App\Models\Agent::$statusLables)?\App\Models\Agent::$statusLables[$v->status]:''}}</th>
                                        <td>{{$v->created_at}}</td>
                                        <td>
                                            <a href="/admin/agent/edit?id={{$v->id}}" class="btn btn-info">修改</a>
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