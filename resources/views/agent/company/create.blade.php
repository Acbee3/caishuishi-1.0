@extends('agent.company.app_company')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/companies")}}" >客户信息管理</a><i>&gt</i><a href="javascript:void(0);" class="cur">新增客户信息</a>
        </div>
        <div class="customerEditor">
            <ul class="editorList">
                <li class="activeEditor">基本信息</li>
                <li>账套信息</li>
            </ul>
            <div class="editorContainter">
                <div class="bascInfo">
                    <form method="post" action="?">
                        {{ csrf_field() }}
                        <div class="editorLine">
                            <div class="editorLineLeft {{ $errors->has('company_name') ? ' has-error' : '' }}">
                                <label class="required">企业名称：</label>
                                <input type="text" name="company_name"  placeholder="企业名称" value="{{ old('company_name')}}">
                                @if ($errors->has('company_name'))
                                    {{ $errors->first('company_name') }}
                                @endif
                            </div>
                            <div class="editorLineRight {{ $errors->has('taxpayer_number') ? ' has-error' : '' }}">
                                <label class="required">纳税人识别号：</label>
                                <input type="text" name="taxpayer_number"  placeholder="纳税人识别号" value="{{ old('taxpayer_number')}}">
                                @if ($errors->has('taxpayer_number'))
                                    {{ $errors->first('taxpayer_number') }}
                                @endif
                            </div>
                        </div>


                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>登记注册类型：</label>
                                <input type="text" name="reg_sort"  placeholder="登记注册类型" value="{{ old('reg_sort')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>注册日期：</label>
                                <span class="editorDate">
                            <input type="text" value="" class="date layui-input" name="reg_date" id="date">
                            <span class="layui-icon" id="testdateIcon">&#xe637;</span>
                        </span>
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>企业类型：</label>
                                <input type="text" name="company_sort"  placeholder="企业类型" value="{{ old('company_sort')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>社会统一信用代码：</label>
                                <input type="text" name="credit_code"  placeholder="社会统一信用代码(18位)" value="{{ old('credit_code')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">地区：</label>
                                <select id="loc_province" style="width:120px;" ></select>
                                <select id="loc_city" style="width:120px; margin-left: 10px"></select>
                                <select id="loc_town" style="width:120px; margin-left: 10px"></select>
                                <input type="hidden" name="area_id" value="1,2,7">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft {{ $errors->has('company_address') ? ' has-error' : '' }}">
                                <label class="required">营业地址：</label>
                                <input type="text" name="company_address"  placeholder="营业地址" value="{{ old('company_address')}}">
                                @if ($errors->has('company_address'))
                                    {{ $errors->first('company_address') }}
                                @endif
                            </div>
                        </div>
                        <div class="editorLine" style="display: none;">
                            <div class="editorLineLeft {{ $errors->has('scope_business') ? ' has-error' : '' }}">
                                <label class="operate">经营范围：</label>
                                <textarea name="scope_business" id="textA" cols="30" rows="3">{{ old('scope_business')}}</textarea>
                                @if ($errors->has('scope_business'))
                                    {{ $errors->first('scope_business') }}
                                @endif
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>法定代表人：</label>
                                <input type="text" name="legal_person"  placeholder="法定代表人" value="{{ old('legal_person')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>联系方式：</label>
                                <input type="text" name="legal_personphone"  placeholder="法定代表人联系方式" value="{{ old('legal_personphone')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft {{ $errors->has('finance_person') ? ' has-error' : '' }}">
                                <label class="required">财务联系人：</label>
                                <input type="text" name="finance_person"  placeholder="财务联系人" value="{{ old('finance_person')}}">
                                @if ($errors->has('finance_person'))
                                    {{ $errors->first('finance_person') }}
                                @endif
                            </div>
                            <div class="editorLineRight {{ $errors->has('finance_personphone') ? ' has-error' : '' }}">
                                <label class="required">联系方式：</label>
                                <input type="text" name="finance_personphone"  placeholder="财务联系人联系方式" value="{{ old('finance_personphone')}}">
                                @if ($errors->has('finance_personphone'))
                                    {{ $errors->first('finance_personphone') }}
                                @endif
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>企业联系人：</label>
                                <input type="text" name="company_person"  placeholder="企业联系人" value="{{ old('company_person')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>联系方式：</label>
                                <input type="text" name="company_personphone"  placeholder="企业联系人联系方式" value="{{ old('company_personphone')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft {{ $errors->has('taxpayer_rights') ? ' has-error' : '' }}">
                                <label class="required">纳税人资格：</label>
                                <div class="accountSearch">
                                    <div class="selectWrapper">
                                        <select name="taxpayer_rights" class="form-control">
                                            <option value="" selected="">请选择</option>
                                            <option value="0" >一般纳税人</option>
                                            <option value="1" >小规模纳税人</option>
                                        </select>
                                    </div>
                                </div>
                                @if ($errors->has('taxpayer_rights'))
                                    {{ $errors->first('taxpayer_rights') }}
                                @endif
                            </div>
                            <div class="editorLineRight">
                                <label>纳税人信用等级：</label>
                                <input type="text" name="taxpayer_rank"  placeholder="纳税人信用等级" value="{{ old('taxpayer_rank')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>注册资本：</label>
                                <input type="text" name="registered_capital"  placeholder="注册资本" value="{{ old('registered_capital')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>实收资本：</label>
                                <input type="text" name="paidup_capital"  placeholder="实收资本" value="{{ old('paidup_capital')}}">
                            </div>
                        </div>

                        <div class="bascBtn">
                            <button type="submit" class="editorKeep">提交</button>
                            <a href="javascript:history.back(-1);" class="editorBlack">返回</a>
                        </div>
                    </form>
                </div>
                <div class="accountInfo"></div>
            </div>
        </div>
        <script>
            layui.use('laydate', function(){
                var laydate = layui.laydate;
                laydate.render({
                    elem: '#date'
                    ,eventElem: '#testdateIcon'
                    ,trigger: 'click'
                });

            })
        </script>
        <link rel="stylesheet" href="{{url("agent/common/area/select2.css")}}">
        <script src="{{url("agent/common/area/area.js")}}"></script>
        <script src="{{url("agent/common/area/location.js")}}"></script>
        <script src="{{url("agent/common/area/select2.js")}}"></script>
        <script src="{{url("agent/common/area/select2_locale_zh-CN.js")}}"></script>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection