@extends('agent.company.app_company')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/companies")}}" >客户信息管理</a><i>&gt</i><a href="javascript:void(0);" class="cur">查看客户信息</a>
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
                                <input type="text" name="company_name"  value="{{ $model->company_name}}">
                            </div>
                            <div class="editorLineRight {{ $errors->has('taxpayer_number') ? ' has-error' : '' }}">
                                <label class="required">纳税人识别号：</label>
                                <input type="text" name="taxpayer_number"  value="{{ $model->taxpayer_number}}">
                            </div>
                        </div>

                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label>登记注册类型：</label>
                            <input type="text" name="reg_sort"  value="{{ $model->reg_sort}}">
                        </div>
                        <div class="editorLineRight">
                            <label>注册日期：</label>
                            <span class="editorDate">
                            <input type="text" value="{{ $model->reg_date}}" class="date layui-input" name="reg_date" id="date">
                            <span class="layui-icon" id="testdateIcon">&#xe637;</span>
                        </span>
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label>企业类型：</label>
                            <input type="text" name="company_sort"  value="{{ $model->company_sort}}">
                        </div>
                        <div class="editorLineRight">
                            <label>社会统一信用代码：</label>
                            <input type="text" name="credit_code"  value="{{ $model->credit_code}}">
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label class="required">地区：</label>
                            <select id="loc_province" style="width:120px;" ></select>
                            <select id="loc_city" style="width:120px; margin-left: 10px"></select>
                            <select id="loc_town" style="width:120px; margin-left: 10px"></select>
                            <input type="hidden" name="area_id" value="{{$model->area_id}}">
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label class="required">营业地址：</label>
                            <input type="text" name="company_address"  value="{{ $model->company_address}}">
                        </div>
                    </div>
                    <div class="editorLine" style="display: none;">
                        <div class="editorLineLeft">
                            <label class="operate">经营范围：</label>
                            <textarea name="scope_business" id="textA" cols="30" rows="3">{{ $model->scope_business}}</textarea>
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label>法定代表人：</label>
                            <input type="text" name="legal_person"  value="{{ $model->legal_person}}">
                        </div>
                        <div class="editorLineRight">
                            <label>联系方式：</label>
                            <input type="text" name="legal_personphone"  value="{{ $model->legal_personphone}}">
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label class="required">财务联系人：</label>
                            <input type="text" name="finance_person"  value="{{ $model->finance_person}}">
                        </div>
                        <div class="editorLineRight">
                            <label class="required">联系方式：</label>
                            <input type="text" name="finance_personphone"  value="{{ $model->finance_personphone}}">
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label>企业联系人：</label>
                            <input type="text" name="company_person"  value="{{ $model->company_person}}">
                        </div>
                        <div class="editorLineRight">
                            <label>联系方式：</label>
                            <input type="text" name="company_personphone"  value="{{ $model->company_personphone}}">
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label class="required">纳税人资格：</label>
                            <div class="accountSearch">
                                <div class="selectWrapper">
                                    <select name="taxpayer_rights" class="form-control">
                                        <option value="0" @if(isset($model->taxpayer_rights) && $model->taxpayer_rights == '0') selected @endif>增值税一般纳税人</option>
                                        <option value="1" @if(isset($model->taxpayer_rights) && $model->taxpayer_rights == '1') selected @endif>增值税小规模纳税人</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="editorLineRight">
                            <label>纳税人信用等级：</label>
                            <input type="text" name="taxpayer_rank"  value="{{ $model->taxpayer_rank}}">
                        </div>
                    </div>
                    <div class="editorLine">
                        <div class="editorLineLeft">
                            <label>注册资本：</label>
                            <input type="text" name="registered_capital"  value="{{ $model->registered_capital}}">
                        </div>
                        <div class="editorLineRight">
                            <label>实收资本：</label>
                            <input type="text" name="paidup_capital"  value="{{ $model->paidup_capital}}">
                        </div>
                    </div>
                </div>
                <div class="accountInfo" v-show="nowIndex===1">
                    <div>
                        <p class="basicNum">基本参数</p>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">账套名称：</label>
                                <input type="text" value="{{ $model->company_name}}">
                            </div>
                            <div class="editorLineRight">
                                <label class="required">启用期间：</label>
                                <div class="editorMenu">
                                    <div class="yearSearch" style="float:left">
                                        <div class="selectWrapper">
                                            <select name="used_year" class="form-control">
                                                <option value="" @if(isset($model->used_year) && $model->used_year == '') selected @endif>请选择</option>
                                                <option value="2018" @if(isset($model->used_year) && $model->used_year == '2018') selected @endif>2018</option>
                                                <option value="2017" @if(isset($model->used_year) && $model->used_year == '2017') selected @endif>2017</option>
                                                <option value="2016" @if(isset($model->used_year) && $model->used_year == '2016') selected @endif>2016</option>
                                                <option value="2015" @if(isset($model->used_year) && $model->used_year == '2015') selected @endif>2015</option>
                                                <option value="2014" @if(isset($model->used_year) && $model->used_year == '2014') selected @endif>2014</option>
                                                <option value="2013" @if(isset($model->used_year) && $model->used_year == '2013') selected @endif>2013</option>
                                                <option value="2012" @if(isset($model->used_year) && $model->used_year == '2012') selected @endif>2012</option>
                                                <option value="2011" @if(isset($model->used_year) && $model->used_year == '2011') selected @endif>2011</option>
                                                <option value="2010" @if(isset($model->used_year) && $model->used_year == '2010') selected @endif>2010</option>
                                                <option value="2009" @if(isset($model->used_year) && $model->used_year == '2009') selected @endif>2009</option>
                                                <option value="2008" @if(isset($model->used_year) && $model->used_year == '2008') selected @endif>2008</option>
                                                <option value="2007" @if(isset($model->used_year) && $model->used_year == '2007') selected @endif>2007</option>
                                                <option value="2006" @if(isset($model->used_year) && $model->used_year == '2006') selected @endif>2006</option>
                                                <option value="2005" @if(isset($model->used_year) && $model->used_year == '2005') selected @endif>2005</option>
                                                <option value="2004" @if(isset($model->used_year) && $model->used_year == '2004') selected @endif>2004</option>
                                                <option value="2003" @if(isset($model->used_year) && $model->used_year == '2003') selected @endif>2003</option>
                                                <option value="2002" @if(isset($model->used_year) && $model->used_year == '2002') selected @endif>2002</option>
                                                <option value="2001" @if(isset($model->used_year) && $model->used_year == '2001') selected @endif>2001</option>
                                                <option value="2000" @if(isset($model->used_year) && $model->used_year == '2000') selected @endif>2000</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="yearSearch" style="float:left">
                                        <div class="selectWrapper">
                                            <select name="used_month" class="form-control">
                                                <option value="" @if(isset($model->used_month) && $model->used_month == '') selected @endif>请选择</option>
                                                <option value="1" @if(isset($model->used_month) && $model->used_month == '1') selected @endif>1</option>
                                                <option value="2" @if(isset($model->used_month) && $model->used_month == '2') selected @endif>2</option>
                                                <option value="3" @if(isset($model->used_month) && $model->used_month == '3') selected @endif>3</option>
                                                <option value="4" @if(isset($model->used_month) && $model->used_month == '4') selected @endif>4</option>
                                                <option value="5" @if(isset($model->used_month) && $model->used_month == '5') selected @endif>5</option>
                                                <option value="6" @if(isset($model->used_month) && $model->used_month == '6') selected @endif>6</option>
                                                <option value="7" @if(isset($model->used_month) && $model->used_month == '7') selected @endif>7</option>
                                                <option value="8" @if(isset($model->used_month) && $model->used_month == '8') selected @endif>8</option>
                                                <option value="9" @if(isset($model->used_month) && $model->used_month == '9') selected @endif>9</option>
                                                <option value="10" @if(isset($model->used_month) && $model->used_month == '10') selected @endif>10</option>
                                                <option value="11" @if(isset($model->used_month) && $model->used_month == '11') selected @endif>11</option>
                                                <option value="12" @if(isset($model->used_month) && $model->used_month == '12') selected @endif>12</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">会计制度：</label>
                                <div class="accountSearch accountTip">
                                    <div class="selectWrapper">
                                        <select name="accounting_system" class="form-control">
                                            <option value="0" @if(isset($model->accounting_system) && $model->accounting_system == '0') selected @endif>企业会计准则</option>
                                            <option value="1" @if(isset($model->accounting_system) && $model->accounting_system == '1') selected @endif>小企业会计准则</option>

                                        </select>
                                    </div>
                                    <p class="accountTipConenter">会计制度一旦选择,不得随意变更!</p>
                                </div>
                            </div>
                            <div class="editorLineRight">
                                <label class="required">本位币：</label>
                                <div class="accountSearch">
                                    <div class="selectWrapper">
                                        <select name="standard_money" class="form-control">
                                            <option value="RMB" @if(isset($model->standard_money) && $model->standard_money == 'RMB') selected @endif>人民币</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label class="required">会计行业：</label>
                                <div class="accountSearch">
                                    <div class="selectWrapper">
                                        <select name="accounting_trade" class="form-control">
                                            <option value="0" @if(isset($model->accounting_trade) && $model->accounting_trade == '0') selected @endif>通用行业</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="basicNum">科目参数</p>
                        <div class="editorLine">
                            <div class="editorLineLeft">
                                <label>科目层级:</label>
                                <select class="topClass" v-model="selected" @change="chooseOption">
                                    <option v-for="option in selectOption" :key="selectOption" :value="option.value">@{{option.text}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="editorLine">
                            <div class="editorOther">
                                <label>科目长度:</label>
                                <div class="editorOtherAdd">
                                    <select class="subjectLength">
                                        <option value="4">4</option>
                                    </select>
                                    <select class="subjectLength" v-for="item in boxs">
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="{{url("agent/common/area/select2.css")}}">
        <script src="{{url("agent/common/area/area.js")}}"></script>
        <script src="{{url("agent/common/area/location.js")}}"></script>
        <script src="{{url("agent/common/area/select2.js")}}"></script>
        <script src="{{url("agent/common/area/select2_locale_zh-CN.js")}}"></script>
        <script>
            $(function(){showLocation({{$model->area_id}})})
        </script>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection