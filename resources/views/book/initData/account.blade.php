@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/account.css?v=20180817011">
@endsection

@section('content')
    <div class="accountWrapper" id="accountWrapper" v-cloak>
        <div class="tabTitle">
            <ul class="menu">
                <li class="active">科目余额表</li>
            </ul>
            {{----------暂时不需要-------------}}
            {{--<div class="btnRight">
                <a href="javascript:;" class="initDate">重新初始化</a>
                <a href="javascript:;" class="hiddenDate">隐藏空数据</a>
            </div>--}}
            <div class="daoru" v-if="allow" @click="showDaoru()">导入</div>
        </div>
        <div class="accountTable">
            <div class="accountHead fixTableHeader">
                <table>
                    <tbody>
                    <tr>
                        <td rowspan="2" class="width15">科目编码</td>
                        <td rowspan="2" class="width26">科目名称</td>
                        <td colspan="2" class="width24">期初余额</td>
                        <td colspan="2" class="width24">本年累计发生额</td>
                        <td rowspan="2" class="width11">操作</td>
                    </tr>
                    <tr>
                        <td class="width12">借方(本位币)</td>
                        <td class="width12">贷方(本位币)</td>
                        <td class="width12">借方(本位币)</td>
                        <td class="width12">贷方(本位币)</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="accountBody tableScroll">
                <table>
                    <tr v-for="(item,index) in accountTables">
                        <td class="width15">@{{item.account_subject_number}}</td>
                        <td class="width26">@{{item.account_subject_name}}</td>
                        <td class="width12">
                            <span v-show="item.qcye_jTop">@{{item.qcye_j}}</span>
                            <input type="text" v-show="item.qcye_jSelect" :value="item.qcye_j" v-model="item.qcye_j">
                        </td>
                        <td class="width12">
                            <span v-show="item.qcye_dTop">@{{item.qcye_d}}</span>
                            <input type="text" v-show="item.qcye_dSelect" :value="item.qcye_d" v-model="item.qcye_d">
                        </td>
                        <td class="width12">
                            <span v-show="item.bnlj_Top">@{{item.bnljfse_j}}</span>
                            <input type="text" v-show="item.bnlj_select" :value="item.bnljfse_j" v-model="item.bnljfse_j">
                        </td>
                        <td class="width12">
                            <span v-show="item.bnlj_Top">@{{item.bnljfse_d}}</span>
                            <input type="text" v-show="item.bnlj_select" :value="item.bnljfse_d" v-model="item.bnljfse_d">
                        </td>
                        <td class="width11">
                            <i class="iconfont initEditor" v-show="item.editorIcon" @click="editor(item,index)">&#xe606;</i>
                            <i class="iconfont initKeep" v-show="item.keep" @click="keep(item,index)">&#xe60b;</i>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="accountFooter">
                <table>
                    <tr>
                        <td class="width15"></td>
                        <td class="width26">
                            <div class="totalPrice">
                                <span>总计(</span>
                                <a href="javascript:;" @click="searchPrice" :class="mapClass[status]" v-text="bankStatus[status]">@{{testMenu}}</a>
                                <i class="iconfont">&#xe625;</i>
                                <span>)</span>
                            </div>
                        </td>
                        <td class="width12">@{{qcye_jTotal}}</td>
                        <td class="width12">@{{qcye_dTotal}}</td>
                        <td class="width12">@{{bnljfse_j_total}}</td>
                        <td class="width12">@{{bnljfse_d_total}}</td>
                        <td class="width11"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="checkNum" style="display:none;">
            <div class="checkHead">
                <table border="0">
                    <thead>
                    <tr>
                        <th class="width20">项目</th>
                        <th class="width30">借方金额</th>
                        <th class="width30">贷方金额</th>
                        <th class="width20">差额</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div>
                <table border="0">
                    <tbody>
                    <tr>
                        <td class="width20">期初余额</td>
                        <td class="width30">@{{qcye_jTotal}}</td>
                        <td class="width30">@{{qcye_dTotal}}</td>
                        <td class="width20">@{{balance}}</td>
                    </tr>
                    <tr>
                        <td class="width20">本年累计发生额</td>
                        <td class="width30">@{{bnljfse_j_total}}</td>
                        <td class="width30">@{{bnljfse_d_total}}</td>
                        <td class="width20">@{{bnlj}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="components daoruBox" style="display: none;">
            <div class="content">
                <form action="#" method="POST" id="fileUploadForm" enctype="multipart/form-data">
                    <input type="hidden" name="company_id" value='{{ \App\Entity\Company::sessionCompany()->id }}'>
                    <input type="hidden" name="fiscal_period" value='{{ \App\Entity\Period::currentPeriod() }}'>
                    <input readonly type="text" id="fileName">
                    <a href="javascript:;" id="fileUp" @click="$('#file').click()">文件上传</a>
                    <input type="file" name="data" id="file" style="display: none" @change="$('#fileName').val($('#file')[0].value.substring($('#file')[0].value.lastIndexOf('\\')+1), file = $('#file')[0].files)" accept="application/vnd.ms-excel">
                </form>
                <div class="download">
                    <p>费用通用模板下载<a href="http://p9j4qv818.bkt.clouddn.com/common/科目余额表_模板.xls" style="color: red;">下载</a></p>
                </div>
                <p class="attention" style="color: red;">注意：上传模板会覆盖掉当前的数据</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="/js/book/table.js"></script>
    <script>
        new Vue({
            'el':'#accountWrapper',
            data:{
                qcye_jTotal:'',
                qcye_dTotal:'',
                bnljfse_j_total:'',
                bnljfse_d_total:'',
                balance: '',
                bnlj:'',
                testMenu: '试算平衡',
                status: 0,
                //每行编辑editor，每行保存keep，
                accountTables:[],
                file: '',
                allow: 0
            },
            created:function(){
                var _this = this;
                this.getKmye();
                layui.use(['form', 'jquery', 'layer'], function () {
                    var form = layui.form;
                    var layer = layui.layer;
                });
                this.mapClass = ['accountBody','errorColor'];
                this.bankStatus = ['试算平衡','试算不平'];
                if(this.qcye_jTotal != this.qcye_dTotal){
                    this.status = 1
                };
                this.$http.post('/book/subjectBalance/checkInit', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}'
                }).then(function (res) {
                    if (res.body.status == 1) {
                        _this.allow = res.body.data.allow;
                    }
                    // console.log(res)
                })
            },
            methods:{

                /*-----------获取数据------*/
                getKmye:function(){
                    this.$http.get('{{ route('subjectBalance.subjectBalanceFirst') }}').then(function(response){
                        if(response.body.status == 1){
                            var arrList = response.body.data.list;
                            this.qcye_jTotal = response.body.data.qcye_j_total;
                            this.qcye_dTotal = response.body.data.qcye_d_total;
                            this.bnljfse_j_total = response.body.data.bnljfse_j_total;
                            this.bnljfse_d_total = response.body.data.bnljfse_d_total;
                            for(var i in arrList){
                                if(arrList[i].qcye_j == 0){
                                    arrList[i].qcye_j = ''
                                }
                                arrList[i].qcye_d == 0 ? arrList[i].qcye_d = '': arrList[i].qcye_d = arrList[i].qcye_d;
                                if(arrList[i].bnljfse_j == 0){
                                    arrList[i].bnljfse_j = ''
                                }
                                if(arrList[i].bnljfse_d == 0){
                                    arrList[i].bnljfse_d = ''
                                }
                                arrList[i].bnlj_Top = true;
                                arrList[i].bnlj_select = false;
                                /*----当每行的qcye_canedit为true且account_closed=0时(未结账)时可以编辑 && arrList[i].account_closed == 0*/
                                arrList[i].editorIcon = '';
                                if(arrList[i].qcye_canedit && arrList[i].account_closed == 0 && !arrList[i].isHasVoucher){
                                    arrList[i].editorIcon = true
                                }
                            }
                            this.accountTables = arrList;
                        }
                    }).then(function(){
                        initHeight();
                        this.balance = (this.qcye_jTotal - this.qcye_dTotal).toFixed(2);
                         this.bnlj = (this.bnljfse_j_total - this.bnljfse_d_total).toFixed(2);
                    });
                },
                //--------每行编辑-----
                editor:function(item,index){
                    //判断其他行是否有编辑状态
                    for(var i in this.accountTables){
                        if(this.accountTables[i].keep){
                            layer.msg('请先保存其它行编辑', {icon: 3, time: 1500});
                            return
                        }
                    }
                    item.bnlj_Top = false;
                    item.bnlj_select = true;
                    if(item.balance_direction == '借'){
                        item.qcye_jTop = false;
                        item.qcye_jSelect = true;
                    }else{
                        item.qcye_dTop = false;
                        item.qcye_dSelect = true;
                    }
                    //保存按钮show,编辑按钮hide
                    item.editor = false;
                    item.keep = true;
                },
                //--------每行保存---------、
                keep:function(item,index){
                    //inputshow
                    item.qcye_jTop = true;
                    item.qcye_dTop = true;
                    item.qcye_jSelect = false;
                    item.qcye_dSelect = false;
                    /*---本年累计发生额不可编辑-------*/
                    item.bnlj_Top = true;
                    item.bnlj_select = false;
                    //保存按钮show,编辑按钮hide
                    item.editor = true;
                    item.keep = false;
                    //计算借方总额与贷方总额
                    var qcye_jTotal = 0;
                    var qcye_dTotal = 0;
                    for(var i in this.accountTables){
                        qcye_jTotal += Number(this.accountTables[i].qcye_j);
                        qcye_dTotal += Number(this.accountTables[i].qcye_d);
                    }
                    this.qcye_jTotal = qcye_jTotal.toFixed(2);
                    this.qcye_dTotal = qcye_dTotal.toFixed(2);
                    this.balance = (this.qcye_jTotal - this.qcye_dTotal).toFixed(2);
                    if(item.qcye_j == ''){
                        item.qcye_j = 0
                    }
                    if(item.qcye_d == ''){
                        item.qcye_d = 0
                    }
                    if(item.bnljfse_j == ''){
                        item.bnljfse_j = 0
                    }
                    if(item.bnljfse_d == ''){
                        item.bnljfse_d = 0
                    }
                    data = {
                        _token:"{{csrf_token()}}",
                        "id":item.id,
                        "qcye_j": item.qcye_j,
                        "qcye_d": item.qcye_d,
                        "bnljfse_j":item.bnljfse_j,
                        "bnljfse_d":item.bnljfse_d
                    };
                    this.$http.post('{{ route('subjectBalance.subjectBalanceEdit') }}', data).then(function(response){
                        if (response.body.status == 1){
                            layer.msg(response.body.info, {icon:1,time:1000});
                            window.location.reload();
                        }else{
                            layer.msg(response.body.info, {icon:2,time:1000});
                            window.location.reload();
                        }
                    });
                    if(this.qcye_jTotal != this.qcye_dTotal){
                        //总计1为试算平衡，0为试算不平衡
                        this.status = 1
                    }else{
                        this.status = 0
                    }
                },
                //查看试算是否平衡
                searchPrice:function(){
                    var _this = this;
                    layer.open({
                        type: 1,
                        title: '试算平衡',
                        skin: 'testComputed',
                        shadeClose: true,
                        shade: false,
                        maxmin: false, //开启最大化最小化按钮
                        area: ['510px', 'auto'],
                        content: $('#checkNum'),
                        btn:['确定','取消'],
                        yes: function(index,layero){
                            layer.close(index)
                        }
                        ,btn2: function(index, layero){
                            //return false 开启该代码可禁止点击该按钮关闭
                        }
                    })
                },
                // 导入弹出框
                showDaoru: function () {
                    var _this = this;
                    layer.open({
                        type: 1,
                        title: '导入科目余额表初始数据',
                        skin: 'components',
                        shadeClose: true,
                        shade: false,
                        maxmin: false, //开启最大化最小化按钮
                        area: ['420px', '240px'],
                        content: $('.daoruBox'),
                        btn: ['上传', '取消'],
                        yes: function (index, layero) {

                            _this.file = $('#file')[0].files[0];
                            var form = $("#fileUploadForm");
                            var formData = new FormData();
                            // console.log(_this.file);
                            if (_this.file == undefined) {
                                layer.msg('请选择要上传的文件', {icon: 2, time: 2000});
                                return false;
                            } else {
                                formData.append('company_id', '{{ \App\Entity\Company::sessionCompany()->id }}');
                                formData.append('fiscal_period', '{{ \App\Entity\Period::currentPeriod() }}');
                                formData.append('file', _this.file);
                                var config = {
                                    headers: {
                                        'Content-Type': 'multipart/form-data'
                                    }
                                };

                                var index1 = layer.load(1, {shade: [0.6, '#000']});
                                _this.$http.post('/book/subjectBalance/import', formData, config).then(function (res) {
                                    if (res.status != 200 && res.ok != true) {
                                        layer.close(index1);
                                        layer.msg('操作失败', {icon: 2, time: 2000});
                                    }
                                    if (res.body.status == 1) {
                                        layer.close(index1);
                                        // 刷新列表数据
                                        layer.msg('操作成功', {icon: 1, time: 2000});
                                        this.getKmye();
                                    } else {
                                        layer.close(index1);
                                        layer.msg(res.body.info, {icon: 2, time: 2000});
                                    }
                                    _this.file = '';
                                    $('#file')[0].value = '';
                                    $('#fileName').val('');
                                });
                                layer.close(index)
                            }

                        },
                        btn2: function (index, layero) {
                            _this.file = '';
                            $('#file')[0].value = '';
                            $('#fileName').val('');
                        },
                        cancel: function () {
                            _this.file = '';
                            $('#file')[0].value = '';
                            $('#fileName').val('');
                        }
                    })
                },
            }
        })
    </script>
@endsection