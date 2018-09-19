@extends('agent.company.app_company')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/companies")}}" >客户信息管理</a><i>&gt</i><a href="javascript:void(0);" class="cur">新增客户信息</a>
        </div>
        <div class="customerEditor">
            <ul class="editorList">
                <li v-for="(item,index) in list" :key="item" :class="{activeEditor:index===nowIndex}" @click="showTable(index)">@{{item}}</li>
            </ul>
            <div class="editorContainter">
                <div class="bascInfo" v-show="nowIndex===0">
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">企业名称：</label>
                                <input type="text" id="company_name" name="company_name"  placeholder="企业名称" value="{{ old('company_name')}}">
                            </div>
                            <div class="editorLineRight">
                                <label class="required">社会统一信用代码：</label>
                                <input type="text" id="credit_code" name="credit_code"  placeholder="社会统一信用代码(18位)" value="{{ old('credit_code')}}">
                            </div>
                        </div>


                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>登记注册类型：</label>
                                <input type="text" id="reg_sort" name="reg_sort"  placeholder="登记注册类型" value="{{ old('reg_sort')}}">
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
                            <div class="editorLineLeft" style="display: none;">
                                <label>企业类型：</label>
                                <input type="text" id="company_sort" name="company_sort"  placeholder="企业类型" value="{{ old('company_sort')}}">
                            </div>
                            <div class="editorLineLeft">
                                <label>纳税人识别号：</label>
                                <input type="text" id="taxpayer_number" name="taxpayer_number"  placeholder="纳税人识别号" value="{{ old('taxpayer_number')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">地区：</label>
                                <select id="loc_province" style="width:120px;" ></select>
                                <select id="loc_city" style="width:120px; margin-left: 10px"></select>
                                <select id="loc_town" style="width:120px; margin-left: 10px"></select>
                                <input type="hidden" id="area_id" name="area_id" value="1226,1359,4846">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">营业地址：</label>
                                <input type="text" id="company_address" name="company_address"  placeholder="营业地址" value="{{ old('company_address')}}">
                            </div>
                        </div>
                        <div class="editorLine" style="display: none;">
                            <div class="editorLineLeft">
                                <label class="operate">经营范围：</label>
                                <textarea name="scope_business" id="textA" cols="30" rows="3">{{ old('scope_business')}}</textarea>
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>法定代表人：</label>
                                <input type="text" id="legal_person" name="legal_person"  placeholder="法定代表人" value="{{ old('legal_person')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>联系方式：</label>
                                <input type="text" id="legal_personphone" name="legal_personphone"  placeholder="法定代表人联系方式" value="{{ old('legal_personphone')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">财务联系人：</label>
                                <input type="text" id="finance_person" name="finance_person"  placeholder="财务联系人" value="{{ old('finance_person')}}">
                            </div>
                            <div class="editorLineRight">
                                <label class="required">联系方式：</label>
                                <input type="text" id="finance_personphone" name="finance_personphone"  placeholder="财务联系人联系方式" value="{{ old('finance_personphone')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>企业联系人：</label>
                                <input type="text" id="company_person" name="company_person"  placeholder="企业联系人" value="{{ old('company_person')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>联系方式：</label>
                                <input type="text" id="company_personphone" name="company_personphone"  placeholder="企业联系人联系方式" value="{{ old('company_personphone')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">纳税人资格：</label>
                                <div class="accountSearch">
                                    <div class="selectWrapper">
                                        <select id="taxpayer_rights" name="taxpayer_rights" class="form-control">
                                            <option value="" selected="">请选择</option>
                                            <option value="0" >一般纳税人</option>
                                            <option value="1" >小规模纳税人</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="editorLineRight">
                                <label>纳税人信用等级：</label>
                                <input type="text" id="taxpayer_rank" name="taxpayer_rank"  placeholder="纳税人信用等级" value="{{ old('taxpayer_rank')}}">
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>注册资本：</label>
                                <input type="text" id="registered_capital" name="registered_capital"  placeholder="注册资本" value="{{ old('registered_capital')}}">
                            </div>
                            <div class="editorLineRight">
                                <label>实收资本：</label>
                                <input type="text" id="paidup_capital" name="paidup_capital"  placeholder="实收资本" value="{{ old('paidup_capital')}}">
                            </div>
                        </div>

                        <input type="hidden" id="company_id" name="company_id" value="">
                        <div class="bascBtn">
                            <button type="submit" class="editorKeep" id="keep_info">提交</button>
                            <a href="javascript:history.back(-1);" class="editorBlack">返回</a>
                        </div>
                </div>
                <div class="accountInfo" v-show="nowIndex===1">
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            请先填写企业基本信息，再更新账套信息！
                        </div>
                    </div>
                </div>
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
        <script>
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
            $(function(){
                /*------ 新增公司基本信息 -------*/
                $("#keep_info").click(function () {
                    let company_name = $("#company_name").val();
                    let taxpayer_number = $("#taxpayer_number").val();
                    let reg_sort = $("#reg_sort").val();
                    let reg_date = $("#date").val();
                    let company_sort = $("#company_sort").val();
                    let credit_code = $("#credit_code").val();
                    let area_id = $("#area_id").val();
                    let company_address = $("#company_address").val();
                    //let scope_business = $("#textA").val();
                    let legal_person = $("#legal_person").val();
                    let legal_personphone = $("#legal_personphone").val();
                    let finance_person = $("#finance_person").val();
                    let finance_personphone = $("#finance_personphone").val();
                    let company_person = $("#company_person").val();
                    let company_personphone = $("#company_personphone").val();
                    let taxpayer_rights = $("#taxpayer_rights").val();
                    let taxpayer_rank = $("#taxpayer_rank").val();
                    let registered_capital = $("#registered_capital").val();
                    let paidup_capital = $("#paidup_capital").val();
                    let company_id = $("#company_id").val();
                    $.ajax({
                        type: 'post',
                        url: '/agent/companies/api_edit',
                        data: {
                            'company_name': company_name,
                            'taxpayer_number':taxpayer_number,
                            'reg_sort':reg_sort,
                            'reg_date':reg_date,
                            'company_sort':company_sort,
                            'credit_code':credit_code,
                            'area_id':area_id,
                            'company_address':company_address,
                            'legal_person':legal_person,
                            'legal_personphone':legal_personphone,
                            'finance_person':finance_person,
                            'finance_personphone':finance_personphone,
                            'company_person':company_person,
                            'company_personphone':company_personphone,
                            'taxpayer_rights':taxpayer_rights,
                            'taxpayer_rank':taxpayer_rank,
                            'registered_capital':registered_capital,
                            'paidup_capital':paidup_capital,
                            'id': company_id
                        },
                        success: function (res) {
                            if (res.status == 'success') {
                                layer.msg(res.msg, {icon: 1, time: 1000});
                                let cid = res.id;
                                setTimeout(function(){window.location.href = "/agent/companies/edit?id=" + cid;}, 1200);
                                return true;
                            } else {
                                layer.msg(res.msg, {icon: 2, time: 2000});
                                return false;
                            }
                        },
                        error: function () {
                            layer.msg('新增失败', {icon: 2, time: 2000});
                            return false;
                        }
                    });
                });

            });
        </script>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection