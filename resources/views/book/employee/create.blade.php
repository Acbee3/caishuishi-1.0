@extends('book.layout.base')

@section('title')新增人员@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("css/agent/zTreeStyle.css")}}" type="text/css">
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--员工模块-->
    <link rel="stylesheet" href="{{asset("css/book/paid/newStaff.css?v=20180821")}}">
@endsection

@section('content')
<div class="addProfess container_agerant" v-cloak>
    <div class="addProfessWrapper">
        <p class="lineText">基本信息</p>
        <form method="post">
            {{ csrf_field() }}
            <div class="addLine">
                <div class="LineLeft">
                    <label class="required">姓名：</label>
                    <input type="text" name="employee_name" v-model="employee_name"  placeholder="姓名" value="{{ old('employee_name')}}">
                </div>
                <div class="lineCenter">
                    <label class="required">证照类型：</label>
                    <div class="staff" ref="zType">
                        <div class="staffTotal" @click="documentTypeShow = !documentTypeShow">
                            <span class="staffTop">@{{getDocumentTypes}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="documentTypeShow">
                            <li v-for="item in documentTypeOption" :key="item.index" @click="getDocumentType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lineRight">
                    <label class="required">证照号码：</label>
                    <input type="text" name="zjhm" v-model="zjhm" placeholder="证件号码" value="{{ old('zjhm')}}">
                </div>
            </div>
            <div class="addLine">
                <div class="LineLeft">
                    <label class="required">境内、境外：</label>
                    <div class="staff" ref="overseas">
                        <div class="staffTotal" @click="overseasShow = !overseasShow">
                            <span class="staffTop">@{{getOverseas}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="overseasShow">
                            <li v-for="item in getOverseasOption" :key="item.index" @click="getOverseasType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lineCenter">
                    <label class="required">是否残疾烈属孤老：</label>
                    <div class="staff" ref="oldChinese">
                        <div class="staffTotal" @click="oldChineseShow = !oldChineseShow">
                            <span class="staffTop">@{{getOldChinese}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="oldChineseShow">
                            <li v-for="item in oldChineseOption" :key="item.index" @click="getOldChineseType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lineRight">
                    <label class="required">是否雇员：</label>
                    <div class="staff" ref="employees">
                        <div class="staffTotal" @click="employeesShow = !employeesShow">
                            <span class="staffTop">@{{getEmployees}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="employeesShow">
                            <li v-for="item in oldChineseOption" :key="item.index" @click="getEmployeesType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="addLine">
                <div class="LineLeft">
                    <label class="required">联系电话：</label>
                    <input type="text" name="lxdh" v-model="lxdh" placeholder="联系电话" value="{{ old('lxdh')}}">
                </div>
                <div class="lineCenter">
                    <label class="required">电子邮箱：</label>
                    <input type="text" name="email" v-model="email" placeholder="电子邮箱" value="{{ old('email')}}">
                </div>
                <div class="lineRight">
                    <label class="required">工作单位：</label>
                    <input type="text" name="company_name" v-model="company_name" placeholder="工作单位" value="{{ old('company_name')}}">
                </div>
            </div>
            <div class="addLine">
                <div class="LineLeft">
                    <label class="required">工号：</label>
                    <input type="text" name="employee_num" v-model="employee_num" placeholder="工号" value="{{ old('employee_num')}}">
                </div>
                <div class="lineCenter">
                    <label >性别：</label>
                    <div class="staff" ref="sexType">
                        <div class="staffTotal" @click="sexShow = !sexShow">
                            <span class="staffTop">@{{getSex}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="sexShow">
                            <li v-for="item in sexOption" :key="item.index" @click="getSexType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lineRight">
                    <label class="required">出生年月：</label>
                    <div class="staffDate">
                        <input type="text"  readonly  onclick="WdatePicker({onpicking:onpick})" :value="birthday" v-model="birthday" >
                        <i class="iconfont">&#xe61f;</i>
                    </div>
                </div>
            </div>
            <div class="addLine">
                <div class="LineLeft">
                    <label class="required">人员状态：</label>
                    <div class="staff" ref="Pstatus">
                        <div class="staffTotal" @click="personnelShow = !personnelShow">
                            <span class="staffTop">@{{getPersonnel}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="personnelShow">
                            <li v-for="item in personnelOption" :key="item.index" @click="getPersonnelType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lineCenter" ref="shareholder">
                    <label class="required">是否股东、投资者：</label>
                    <div class="staff">
                        <div class="staffTotal" @click="shareholdersShow = !shareholdersShow">
                            <span class="staffTop">@{{getShareholders}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="shareholdersShow">
                            <li v-for="item in oldChineseOption" :key="item.index" @click="getShareholdersType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="lineRight">
                    <label class="required">是否特定行业：</label>
                    <div class="staff" ref="industry">
                        <div class="staffTotal" @click="industryShow = !industryShow">
                            <span class="staffTop">@{{getIndustry}}</span>
                            <i class="icon iconfont icon-xialazhishijiantou"></i>
                        </div>
                        <ul class="documentType" v-show="industryShow">
                            <li v-for="item in oldChineseOption" :key="item.index" @click="getIndustryType(item)">
                                @{{item}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="addLine">
                <div class="LineLeft">
                    <label>备注：</label>
                    <input type="text" name="remark" v-model="remark" placeholder="备注" value="{{ old('remark')}}">
                </div>
            </div>
            <div class="newStaff-btn">
                <a href="javascript:void(0);" class="keepBtn" @click="createPost">保存并新增</a>
                <a href="javascript:void(0);" class="keepBtn" style="display: none;">保存</a>
                <a href="javascript:history.back(-1);" class="cancleBtn">返回</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!--公用-->
<script src="{{asset("js/My97DatePicker/WdatePicker.js")}}"></script>
<!--<script src="{{asset("agent/common/js/layer.js")}}"></script>-->
<script>
    var curData = '1990-01-01';
    function onpick(dp){
        curData = dp.cal.getNewDateStr();
    }

    new Vue({
        "el": '.addProfess',
        data: {
            documentTypeShow:false,
            overseasShow:false,
            oldChineseShow:false,
            employeesShow:false,
            sexShow: false,
            personnelShow: false,
            shareholdersShow: false,
            industryShow: false,
            getDocumentTypes:'居民身份证',
            getOverseas:'境内',
            getOldChinese: '否',
            getEmployees: '是',
            getSex: '男',
            getPersonnel: '正常',
            getShareholders: '否',
            getIndustry: '否',
            documentTypeOption:[
                '居民身份证','军官证','士兵证','武警警官证','中国护照','外国护照','香港身份证','香港永久性居民身份证','澳门身份证','澳门永久性居民身份证','港澳居民来往内地通行证','台湾居民来往大陆通行证','台湾身份证','外国人永久居留身份证（外国人永久居留证）','外国人永久居留证','外交官证'
            ],
            getOverseasOption:[
                '境内','境外'
            ],
            oldChineseOption:[
                '是','否'
            ],
            sexOption:[
                '男','女'
            ],
            personnelOption:[
                '正常','非正常'
            ],
            employee_name:'',
            zjhm:'',
            lxdh:'',
            email:'',
            company_name:'{{$company_name}}',
            employee_num:'',
            remark:'',
            birthday:'1990-01-01'
        },
        mounted:function(){
            this.clickBlank()
        },
        methods:{
            /*-------点击空白处隐藏-----*/
            clickBlank:function(){
                //----------二期可优化----没时间-----
                /*--证照类型zType，境内外overseas,烈属孤老oldChinese,雇员employees,性别sex,人员状态Pstatus,特定行业industry,股东shareholder*/
                var zType = this.$refs.zType;
                var overseas = this.$refs.overseas;
                var oldChinese = this.$refs.oldChinese;
                var employees = this.$refs.employees;
                var sexType = this.$refs.sexType;
                var shareholder = this.$refs.shareholder;
                var Pstatus = this.$refs.Pstatus;
                var industry = this.$refs.industry;
                var _this = this;
                document.addEventListener('click',function(e){
                    if(!zType.contains(e.target)){
                        _this.documentTypeShow = false;
                    }
                    if(!overseas.contains(e.target)){
                        _this.overseasShow = false;
                    }
                    if(!oldChinese.contains(e.target)){
                        _this.oldChineseShow = false;
                    }
                    if(!employees.contains(e.target)){
                        _this.employeesShow = false;
                    }
                    if(!sexType.contains(e.target)){
                        _this.sexShow = false;
                    }
                    if(!Pstatus.contains(e.target)){
                        _this.personnelShow = false;
                    }
                    if(!shareholder.contains(e.target)){
                        _this.shareholdersShow = false;
                    }
                    if(!industry.contains(e.target)){
                        _this.industryShow = false;
                    }
                });
            },
            /*----form内的所有下拉框-----*/
            getDocumentType:function(item){
                this.getDocumentTypes = item;
                this.documentTypeShow = false
            },
            getOverseasType:function(item){
                this.getOverseas = item;
                this.overseasShow = false
            },
            getOldChineseType:function(item){
                this.getOldChinese = item;
                this.oldChineseShow = false
            },
            getEmployeesType:function(item){
                this.getEmployees = item;
                this.employeesShow = false
            },
            getSexType:function(item){
                this.sexShow = false;
                this.getSex = item
            },
            getPersonnelType:function(item){
                this.personnelShow = false
                this.getPersonnel = item
            },
            getShareholdersType:function(item){
                this.shareholdersShow = false
                this.getShareholders = item
            },
            getIndustryType:function(item){
                this.industryShow = false
                this.getIndustry = item
            },
            createPost:function(){
                var _this = this;
                var data = {
                    _token:"{{ csrf_token() }}",
                    employee_name:_this.employee_name,
                    zjlx:_this.getDocumentTypes,
                    zjhm:_this.zjhm,
                    country:_this.getOverseas,
                    sf_cjlsgl:_this.getOldChinese,
                    sf_employee:_this.getEmployees,
                    lxdh:_this.lxdh,
                    email:_this.email,
                    company_name:_this.company_name,
                    employee_num:_this.employee_num,
                    gender:_this.getSex,
                    birthday:curData,
                    status:_this.getPersonnel,
                    sf_shareholder:_this.getShareholders,
                    sf_tdhy:_this.getIndustry,
                    remark:_this.remark,
                    do:'insert'
                };
                //console.log(data);
                _this.$http.post('{{ route('employee.api_save_add') }}', data).then(function (response) {
                    if (response.body.status == 'success'){
                        layer.msg(response.body.msg, {icon:1,time:1000});
                        //window.location.href = "{{ route('department.index') }}";
                        setTimeout(function(){window.location.href = "{{ route('department.index') }}";}, 1200);
                    }else{
                        layer.msg(response.body.msg, {icon:2,time:1000});
                    }
                })
            }
        }
    })
</script>
@endsection