@extends('admin.layouts.admin')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            创建代账公司
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li class="active">创建代账公司</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form class="form-horizontal" method="post" action="?">
                        {{ csrf_field() }}
                        <div class="box-body col-xs-6">
                            <div class="form-group {{ $errors? ' has-error' : '' }}" >
                                <span class="help-block">
                                    <strong>{{ $errors->first() }}</strong>
                                </span>
                            </div>

                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}" >
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 代账公司名称
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name"  placeholder="代账公司名称" value="{{ old('name')}}">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('contacts') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 联系人
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="contacts"  placeholder="联系人" value="{{ old('contacts')}}">
                                    @if ($errors->has('contacts'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('contacts') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 联系电话
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

                            <div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 联系地址
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address"  placeholder="联系地址" value="{{ old('address')}}">
                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('pic') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 营业执照
                                </label>

                                <div class="col-sm-10">
                                    <!-- image表图集 -->
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div style="margin-bottom:5px;">
                                            <span class="btn red btn-outline btn-file">
                                                <span class="btn"> 上传图片 </span>
                                                <input type="hidden" id="" name="pic" value="{{ old('pic')}}">
                                                <input type="file" name="..." class="file_buttom1">
                                            </span>
                                        </div>
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                             style="width: 200px; height: 150px;">
                                            <img class="file_img" style="height: 140px;" src="{{ old('pic') ? old('pic') : "/admin/static/images/no.png"}}">
                                        </div>
                                    </div>
                                    @if ($errors->has('pic'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pic') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 状态
                                </label>

                                <div class="col-sm-10">
                                    @foreach(\App\Models\Agent::$statusLables as $key=>$v)
                                        <label><input type="radio" name="status" value="{{$key}}" @if($key==1) checked @endif > {{$v}}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    @endforeach
                                    @if ($errors->has('status'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('status') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">
                                    <span style="color: #ff0000;">*</span> 用户名
                                </label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username"  placeholder="账号" value="{{ old('username')}}">
                                    @if ($errors->has('username'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
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

<script type="text/javascript">
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
    });

    $(function() {
        /* ===================上传单图======================= */
        $(".file_buttom1").on("change", function(){
            var file_ipt = $(this).siblings('input');
            var file_img = $(this).parents('.fileinput').find('.file_img');

            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return;
            //判断是否是图片类型
            if(!/image\/\w+/.test(files[0].type)){
                alert("只能选择图片");
                return false;
            }
            if(files[0].size > 300000){
                alert('文件超过300K');
                return false;
            }
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onloadend = function(){
                $.ajax({
                    type: 'post',
                    url: '/admin/upload/img',
                    data: {imgbase64:this.result},
                    dataType: 'json',
                    beforeSend: function(){

                    },
                    success: function(json){
                        if(json.result){
                            file_img.attr('src',json.url);
                            file_ipt.val(json.url);
                        } else {
                            alert(json.message);
                        }
                    },
                    error: function(xhr, type){
                        alert('服务器错误，刷新页面')
                    }
                });
            }
        });
    });


</script>
@endsection
