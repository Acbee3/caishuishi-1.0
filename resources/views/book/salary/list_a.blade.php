@extends('book.layout.base')

@section('title')正常工资薪酬@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--薪酬表-->
    <link rel="stylesheet" href="{{asset("css/book/paid/payrollEditor.css")}}">
    <link rel="stylesheet" href="{{asset("css/book/paid/fzxc.css")}}">
@endsection

@section('content')
<div class="payrollEditor" v-cloak>
    <div class="payrollEditorWrapper">
        <div class="payrollEditorMenu">
            <div class="payrollEditorMenu-left">
                <div class="payrollMenuBlack">
                    <a href="javascript:history.back(-1);" class="payrollBlack">返回</a>
                </div>
                <div class="payrollMenuSearch">
                    <span>姓名:</span>
                    <div class="payrollEditorSearch">
                        <input type="text" placeholder="请输入姓名进行搜索" v-model="sv">
                        <i class="iconfont search" @click="doSearch">&#xe61e;</i>
                    </div>
                </div>
            </div>
            <div class="payrollEditorMenu-right">
                <a href="javascript:void(0);" @click="addPersorner" class="add">新增</a>
                <a href="javascript:void(0);" @click="keepPayroll" class="save">保存</a>
                <a href="javascript:void(0);" @click="doExport" class="excel">导出Excel</a>
                <a href="javascript:void(0);" @click="doPrinting" class="print">打印</a>
                <a href="javascript:void(0);" @click="copySalaryBill" class="copySalary">复制工资表</a>
                <a href="javascript:history.back(-1);" style="background-color: #f66; display: none;">返回</a>
                <div class="payrollEditorMenu-more">
                    <div class="payrollEditorHead" class="more">
                        <span class="payrollEditorTop">更多</span>
                        <i class="icon iconfont icon-xialazhishijiantou"></i>
                    </div>
                    <ul class="payrollMore-select">
                        <li @click="delAll" v-model="checked">删除</li>
                        <li>导入</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="payrollEditorTable">
            <div class="editorTable-header">
                <table>
                    <tbody>
                    <tr>
                        <td rowspan="2" class="width2"><input type="checkbox" @click="allSelect" v-model="checked"></td>
                        <td rowspan="2" class="width4">序号</td>
                        <td rowspan="2" class="width4">姓名</td>
                        <td rowspan="2">费用类型</td>
                        <!--<td rowspan="1" colspan="2">应发工资</td>-->
                        <td rowspan="1">应发工资</td>
                        <td rowspan="1" colspan="6">代扣社保</td>
                        <td rowspan="2">代扣公积金</td>
                        <td rowspan="2">其他费用</td>
                        <td rowspan="2">代扣个税</td>
                        <td rowspan="2">实发工资</td>
                        <td rowspan="2">备注</td>
                        <td rowspan="2">操作</td>
                    </tr>
                    <tr>
                        <td rowspan="1">工资</td>
                        <td rowspan="1">养老保险</td>
                        <td rowspan="1">医疗保险</td>
                        <td rowspan="1">失业保险</td>
                        <td rowspan="1">大病医疗</td>
                        <td rowspan="1">其中通讯费</td>
                        <td rowspan="1">合计</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="editorTable-Center">
                <table>
                    <tr v-for="(item,index) in editorTables" :key="item" :cur_row="item.select" :cur_num="index" class="cur_row">
                        <td class="width2"><input type="checkbox" v-model="selected" :value="item.se_id"></td>
                        <td class="width4">@{{index+1}}</td>
                        <td class="width4">@{{item.name}}</td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.type}}</span>
                            <div class="payroll-menu" v-show="item.select">
                                <div class="payroll-select curList">
                                    <div @click="item.payroll =! item.payroll">
                                        <input type="text" class="payrollSelectText"  :value="item.type">
                                        <span class="textIcon">
                                            <i class="icon iconfont font12">&#xe620;</i>
                                        </span>
                                    </div>
                                    <ul class="payrollSelect_ul" v-show="item.payroll">
                                        <li v-for="payrolls in payrollOption" @click="getpayrollList(payrolls,item)">
                                            <em>@{{payrolls}}</em>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.money}}</span>
                            <input class="salary_money" type="text" v-show="item.select" :value="item.money" v-model="item.money" @blur="dkfee(item,index,$event)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.ylbx}}</span>
                            <input type="text" v-show="item.select" :value="item.ylbx" v-model="item.ylbx" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.Medical}}</span>
                            <input type="text" v-show="item.select" :value="item.Medical" v-model="item.Medical" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.sybx}}</span>
                            <input type="text" v-show="item.select" :value="item.sybx" v-model="item.sybx" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.dbyl}}</span>
                            <input type="text" v-show="item.select" :value="item.dbyl" v-model="item.dbyl" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.communication}}</span>
                            <input type="text" v-show="item.select" :value="item.communication" v-model="item.communication" @blur="dkfee(item,index,$event)">
                        </td>
                        <td>
                        @{{item.total}}
                        <!--<input type="text" readonly :value="item.total" v-model="item.total" style="border:none">-->
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.accumulation}}</span>
                            <input type="text" v-show="item.select" :value="item.accumulation" v-model="item.accumulation" @blur="dkfee(item,index,$event)">
                        </td>
                        <td>@{{item.otherMoney}}</td>
                        <td>@{{item.dkgs}}</td>
                        <td>@{{item.realwages}}</td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.bz}}</span>
                            <input type="text" v-show="item.select" :value="item.bz" v-model="item.bz">
                        </td>
                        <td>
                            <i class="iconfont" @click="doEditor(index,item)">&#xe606;</i>
                            <i class="iconfont del" @click="doDel(index,item)">&#xe605;</i>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="editorFooter">
                <table>
                    <tbody>
                    <tr>
                        <td class="width2"></td>
                        <td class="width4"></td>
                        <td class="width4">合计</td>
                        <td></td>
                        <td>@{{gz}}</td>
                        <td>@{{ylbxf}}</td>
                        <td>@{{ylbxfy}}</td>
                        <td>@{{sybxf}}</td>
                        <td>@{{dbylfy}}</td>
                        <td>@{{txf}}</td>
                        <td>@{{totalf}}</td>
                        <td>@{{dkgjj}}</td>
                        <td>@{{otherF}}</td>
                        <td>@{{dkgsf}}</td>
                        <td>@{{sfgzf}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="addPersorner" id="addPersorner" style="display:none">
    <div class="addPersorner-top" >
        <table>
            <thead>
            <tr>
                <th class="width10"><input type="checkbox" v-model="checked" @click="allSelect"></th>
                <th class="width26">姓名</th>
                <th class="width26">证件类型</th>
                <th class="width38">证件号码</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="addPersorner-body">
        <table>
            <tr v-for="item in addTables" :key="item.id">
                <td class="width10"><input type="checkbox" :value="[item.id,item.name]" v-model="selected"></td>
                <td class="width26">@{{item.name}}</td>
                <td class="width26">@{{item.IDcard}}</td>
                <td class="width38">@{{item.IDnumber}}</td>
            </tr>
        </table>
    </div>
</div>
<div class="copy_salary_employee" id="copy_salary_employee_box" style="display: none;">
    <div class="fzxc-content">
        <form class="fzxcWrapper layui-form">
            <div class="fzxc-itemA">
                <div class="copy" id="copy_monthShow">
                    <input type="text" id="copy_monthData" value="">
                    <i class="iconfont" id="copy_monthData-1">&#xe61f;</i>
                </div>
                <span>复制至</span>
                <div class="copy" id="to_monthShow">
                    <input type="text" disabled :value="copy_to" id="copy_to">
                    <i class="iconfont">&#xe61f;</i>
                </div>
            </div>
        </form>
    </div>
</div>
<style type="text/css">
    .fzxcWrapper { margin-left: 30px;}
    .fzxc-itemA { width: 300px;}
    .copy { margin-right: 10px;}
    .copy input { padding-left: 10px; height: 28px;}
    .fzxc-item .layui-form-select dl dd.layui-this { background: #5FB878;}
</style>
@endsection

@section('script')
<script>
    new Vue({
        "el": '.payrollEditor',
        data: {
            gz: '0.00',
            txf:'0.00',
            ylbxf: '0.00',
            ylbxfy: '0.00',
            sybxf: '0.00',
            dbylfy: '0.00',
            totalf: '0.00',
            dkgjj:'0.00',
            otherF:'0.00',
            dkgsf: '0.00',
            sfgzf: '0.00',
            username: '',
            checked:false,
            selected: [],
            /*payrollOption:[
                '管理费用','公共费用'
            ],*/
            payrollOption:'',
            addTables:[],
            editorTables:[],
            sv: ''
        },
        created:function(){
            // 获取列表数据
            this.getEmployeeSalaryList();
        },
        mounted:function(){
            this.clickBlank()
        },
        methods: {
            //点击空白处相应div隐藏
            clickBlank:function(){
                /*-------费用类型--------*/
                var _this = this;
                $(document).click(function(event){
                    var _con = $('.curList');  // 设置目标区域
                    if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
                        for(var i in _this.editorTables){
                            _this.editorTables[i].payroll = false;
                        }
                    }
                });
            },
            /*---- 行内编辑 ok -----*/
            doEditor: function (index, item) {
                let _this = this;
                let cur = $(".editorTable-Center table").find('tr[cur_row="true"]').attr('cur_num');
                if( cur >= '0'){
                    let curIndex = parseInt(cur)+parseInt('1');
                    //console.log(cur+"_"+curIndex+"_"+index);
                    if(curIndex >= 1  && cur != index)
                    {
                        if(_this.editorTables[cur].money > '0.00')
                        {
                            // 提交保存
                            let data = {
                                _token: "{{ csrf_token() }}",
                                salary:_this.editorTables[cur].money,
                                employee_id:_this.editorTables[cur].id,
                                salary_id:'{{$id}}',
                                employee_name:_this.editorTables[cur].name,
                                do:_this.editorTables[cur].do,
                                se_id:_this.editorTables[cur].se_id,
                                yanglaobx:_this.editorTables[cur].ylbx,
                                yiliaobx:_this.editorTables[cur].Medical,
                                sybx:_this.editorTables[cur].sybx,
                                dbyl:_this.editorTables[cur].dbyl,
                                txf:_this.editorTables[cur].communication,
                                dkgjj:_this.editorTables[cur].accumulation,
                                remark:_this.editorTables[cur].bz,
                                fylx:_this.editorTables[cur].type
                            };
                            _this.$http.post('{{ route('salary.api_save_salary') }}', data).then(function (response) {
                                if (response.body.status == 'success') {
                                    // 更新相关 否则会重复新增
                                    _this.editorTables[cur].do = 'update';
                                    _this.editorTables[cur].se_id = response.body.id;
                                    _this.editorTables[cur].dkgs = response.body.data.personal_tax;
                                    _this.editorTables[cur].realwages = response.body.data.real_salary;
                                    // 提示消息
                                    //layer.msg(response.body.msg, {icon: 1, time: 1000});
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 1000});
                                }
                            });

                        } else {
                            // 提示数据不全（工资未填）
                            layer.msg("第"+curIndex+"行工资未填写，请补全数据！", {icon: 2, time: 2000});
                            return false;
                        }
                    } else if(curIndex >= 1 && cur == index) {
                        // 编辑当前行数据 不保存
                        //layer.msg("编辑当前行数据！", {icon: 2, time: 2000});
                        return false;
                    } else {
                        layer.msg("第"+curIndex+"行数据不全，请补全数据！", {icon: 2, time: 2000});
                        return false;
                    }

                    // 关闭其他编辑
                    for (let i in this.editorTables) {
                        this.editorTables[i].top = true;
                        this.editorTables[i].select = false;
                    }
                    // 开启本行编辑
                    item.top = false;
                    item.select = true;
                } else {
                    // 开启本行编辑
                    item.top = false;
                    item.select = true;
                }
            },
            /*----保存按钮 ok ----*/
            keepPayroll: function () {
                let _this = this;
                let cur = $(".editorTable-Center table").find('tr[cur_row="true"]').attr('cur_num');
                if( cur >= '0') {
                    // 有编辑行
                    let curIndex = parseInt(cur)+parseInt('1');

                    if(_this.editorTables[cur].money > '0.00')
                    {
                        // 提交保存
                        let data = {
                            _token: "{{ csrf_token() }}",
                            salary:_this.editorTables[cur].money,
                            employee_id:_this.editorTables[cur].id,
                            salary_id:'{{$id}}',
                            employee_name:_this.editorTables[cur].name,
                            do:_this.editorTables[cur].do,
                            se_id:_this.editorTables[cur].se_id,
                            yanglaobx:_this.editorTables[cur].ylbx,
                            yiliaobx:_this.editorTables[cur].Medical,
                            sybx:_this.editorTables[cur].sybx,
                            dbyl:_this.editorTables[cur].dbyl,
                            txf:_this.editorTables[cur].communication,
                            dkgjj:_this.editorTables[cur].accumulation,
                            remark:_this.editorTables[cur].bz,
                            fylx:_this.editorTables[cur].type
                        };
                        _this.$http.post('{{ route('salary.api_save_salary') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.msg(response.body.msg, {icon: 1, time: 1000});

                                // 更新列表行
                                this.getEmployeeSalaryList();
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 1000});
                            }
                        });

                    } else {
                        // 提示数据不全（工资未填）
                        layer.msg("第"+curIndex+"行工资未填写，请补全数据！", {icon: 2, time: 2000});
                        return false;
                    }
                }else{
                    // 没有编辑行
                    for (let i in this.editorTables) {
                        this.editorTables[i].top = true;
                        this.editorTables[i].select = false;
                    }
                    layer.msg("当前没有更改任何数据哦！", {icon: 1, time: 1000});
                }
            },
            /*-----每行内的费用类型----*/
            getpayrollList: function (payrolls, item) {
                item.type = payrolls;
                item.payroll = false;
            },
            allComputed: function(item,index){
                let gzfee = 0;
                let ylbxfee = 0;
                let ylbxfy = 0;
                let sybxfee = 0;
                let dbylfee = 0;
                let totalfee = 0;
                let dkgjjf = 0;
                let otherFy = 0;
                let dkgsfy = 0;
                let sfgzfy = 0;
                let sbOther = 0;
                for (let i in this.editorTables) {
                    //代扣社保合计
                    this.editorTables[i].ylbx = Number(this.editorTables[i].ylbx).toFixed(2);
                    this.editorTables[i].Medical = Number(this.editorTables[i].Medical).toFixed(2);
                    this.editorTables[i].sybx = Number(this.editorTables[i].sybx).toFixed(2);
                    this.editorTables[i].dbyl = Number(this.editorTables[i].dbyl).toFixed(2);
                    let sbhj = Number(this.editorTables[i].ylbx) + Number(this.editorTables[i].Medical) + Number(this.editorTables[i].sybx) + Number(this.editorTables[i].dbyl)+ Number(this.editorTables[i].communication);
                    this.editorTables[i].total = sbhj.toFixed(2);
                    //实发工资合计
                    this.editorTables[i].money = Number(this.editorTables[i].money).toFixed(2);
                    this.editorTables[i].otherMoney = Number(this.editorTables[i].otherMoney).toFixed(2);
                    this.editorTables[i].accumulation = Number(this.editorTables[i].accumulation).toFixed(2);
                    let sfMoney = Number(this.editorTables[i].money) - Number(this.editorTables[i].total) - Number(this.editorTables[i].accumulation) - Number(this.editorTables[i].otherMoney) - Number(this.editorTables[i].dkgs);
                    this.editorTables[i].realwages = sfMoney.toFixed(2);
                    if (this.editorTables[i].realwages < 0) {
                        this.editorTables[i].realwages = '0.00';
                    }
                    gzfee += Number(this.editorTables[i].money);
                    ylbxfee += Number(this.editorTables[i].ylbx);
                    ylbxfy += Number(this.editorTables[i].Medical);
                    sybxfee += Number(this.editorTables[i].sybx);
                    dbylfee += Number(this.editorTables[i].dbyl);
                    totalfee += Number(this.editorTables[i].total);
                    dkgjjf += Number(this.editorTables[i].accumulation);
                    otherFy += Number(this.editorTables[i].otherMoney);
                    dkgsfy += Number(this.editorTables[i].dkgs);
                    sfgzfy += Number(this.editorTables[i].realwages);
                    sbOther += Number(this.editorTables[i].communication);
                }
                this.gz = gzfee.toFixed(2);
                this.ylbxf = ylbxfee.toFixed(2);
                this.ylbxfy = ylbxfy.toFixed(2);
                this.sybxf = sybxfee.toFixed(2);
                this.dbylfy = dbylfee.toFixed(2);
                this.totalf = totalfee.toFixed(2);
                this.dkgjj = dkgjjf.toFixed(2);
                this.otherF = otherFy.toFixed(2);
                this.dkgsf = dkgsfy.toFixed(2);
                this.sfgzf = sfgzfy.toFixed(2);
                this.txf = sbOther.toFixed(2);
            },
            /*----代扣社保input失去焦点计算代扣社保的总计---实发工资--*/
            dksb: function (item, index) {
                this.$options.methods.allComputed.bind(this)();
            },
            /*----代扣公积金与其他费用----*/
            dkfee: function (item, index, e) {
                //实发工资合计
                this.$options.methods.allComputed.bind(this)();
            },
            /*-----添加人员按钮 ok --------*/
            addPersorner: function () {
                let _this = this;
                layer.open({
                    type: 1,
                    title: '新增人员',
                    skin: 'components',
                    shadeClose: true,
                    shade: false,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['510px', '360px'],
                    content: $('#addPersorner'),
                    btn:['确定','取消'],
                    yes: function(index){
                        if(addPersonal.selected.length >= 1){
                            for(let j = 0; j< addPersonal.selected.length;j++){
                                //var cur = _this.editorTables.length;
                                _this.editorTables.splice(_this.editorTables.length, 0, {
                                        id: addPersonal.selected[j][0],
                                        name: addPersonal.selected[j][1],
                                        type: '管理费用',
                                        money: '0.00',
                                        communication: '0.00',
                                        ylbx: '0.00',
                                        Medical: '0.00',
                                        sybx: '0.00',
                                        dbyl: '0.00',
                                        total: '0.00',
                                        accumulation: '0.00',
                                        otherMoney: '0.00',
                                        dkgs: '0.00',
                                        realwages: '0.00',
                                        bz: '',
                                        top: true,
                                        select: false,
                                        payroll: false,
                                        do: 'insert',
                                        se_id: ''
                                    }
                                )
                            }
                            /*---- 新增首行设置成编辑状态 -----*/
                            //_this.editorTables[0].top = false;
                            //_this.editorTables[0].select = true;
                            _this.editorTables[_this.editorTables.length - addPersonal.selected.length].top = false;
                            _this.editorTables[_this.editorTables.length - addPersonal.selected.length].select = true;

                            /*$(".editorTable-Center").find('.cur_row td .salary_money').val('').focus();*/
                        }
                        layer.close(index);
                    },
                    success: function () {
                        let data = {
                            _token: "{{ csrf_token() }}",
                            salary_id:'{{$id}}'
                        };
                        _this.$http.post('{{ route('salary.api_get_employee') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                //获取该公司人员
                                addPersonal.addTables = response.body.data;
                                //console.log(addPersonal.addTables);
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 3000});
                                //layer.closeAll();
                            }
                        });
                    }
                })
            },
            /*---- 行内删除 ok -----*/
            //工资money、通讯费communication、养老保险ylbx、医疗保险Medical、失业保险sybx、大病医疗dbyl、合计total、代扣公积金accumulation、其他费用otherMoney、代扣个税dkgs、实发工资realwages
            doDel:function(index, item){
                let _this = this;
                if(item.se_id > 0){
                    layer.confirm('确定要删除 ' + item.name + ' 的薪酬吗？', {icon: 3, title: '提示'},
                        function () {
                            _this.$http.post('{{ route('salary.api_del_salary') }}', {
                                _token: "{{csrf_token()}}",
                                id: item.se_id
                            }).then(function (response) {
                                if (response.body.status == 'success') {
                                    layer.msg(response.body.msg, {icon: 1, time: 1000});
                                    //_this.getEmployeeSalaryList();
                                    window.location.reload();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 1000});
                                }
                            })
                        }
                    );
                }else{
                    layer.confirm('确定要删除 ' + item.name + ' 的薪酬吗？', {icon: 3, title: '提示'},
                        function () {
                            _this.editorTables.splice(index, 1);
                            _this.$options.methods.allComputed.bind(this)();
                            layer.closeAll();
                        }
                    );
                }
            },
            /*-----批量删除----------*/
            allSelect:function(){
                if(this.selected.length != this.editorTables.length){
                    this.selected = [];
                    for(var i in this.editorTables){
                        //this.selected.push(this.editorTables[i].name)
                        this.selected.push(this.editorTables[i].id,this.editorTables[i].name);
                    }
                }else{
                    this.selected = [];
                }
            },
            delAll:function(){
                var _this = this;
                layer.open({
                    type: 1,
                    title: '信息',
                    skin: 'components',
                    shadeClose: true,
                    shade: false,
                    maxmin: false,//开启最大化最小化按钮
                    area: ['310px', '160px'],
                    content:'确定删除吗?',
                    btn:['确定','取消'],
                    yes:function(index, layero){
                        for(var j = 0; j< _this.selected.length;j++){
                            for(var i = 0; i< _this.editorTables.length; i++){
                                if(_this.selected[j] == _this.editorTables[i].name){
                                    _this.editorTables.splice(i,1);
                                }
                            }
                        }
                        layer.close(index)
                    }
                })

            },
            /*------ 导出excel ok ----------*/
            doExport: function () {
                let _this = this;
                let salary_id = '{{$id}}';
                if (_this.selected.length <= 0) {
                    layer.confirm('您没有选择要导出的员工薪酬，确定要导出全部员工薪酬信息吗？', {icon: 3, title: '提示'},
                        function () {
                            layer.closeAll();
                            //window.location.href = "{{ route('salary.export_a', ['ids'=>'all','salary_id'=>$id]) }}";
                            window.location.href = "/book/salary/export_a?ids=all&salary_id="+salary_id;
                        }
                    );
                } else {
                    layer.confirm('确定导出当前已选择的员工薪酬信息？', {icon: 3, title: '提示'},
                        function () {
                            layer.closeAll();
                            window.location.href = "/book/salary/export_a?ids=" + _this.selected +"&salary_id="+salary_id;
                        }
                    );
                }
            },
            /*------ 打印 delay ---------*/
            doPrinting:function(){
                layer.msg("打印功能暂缓开发!", {icon: 2, time: 2000});
            },
            /*------ 复制工资条 ok ---------*/
            copySalaryBill:function(){
                let _this = this;
                layer.open({
                    type: 1,
                    title: '复制往期工资',
                    skin: 'components',
                    shadeClose: true,
                    shade: false,
                    area: ['420px', '220px'],
                    content:$('#copy_salary_employee_box'),
                    btn:['确定','取消'],
                    yes: function () {
                        let copy_from = $("#copy_monthData").val();
                        let copy_to = $("#copy_to").val();
                        let data = {
                            _token: "{{ csrf_token() }}",
                            copy_from:copy_from,
                            copy_to:copy_to,
                            salary_id:'{{$id}}'
                        };
                        _this.$http.post('{{ route('salary.api_copy_salary_bill') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.closeAll();
                                layer.msg(response.body.msg, {icon: 1, time: 2000});
                                _this.getEmployeeSalaryList();
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 2000});
                            }
                        });
                    },
                    success: function () {
                        // 打开窗口处理逻辑
                        let belongtime = '{{\App\Entity\Salary::Get_Belong_Time()}}';
                        $("#copy_to").val(belongtime);
                    }
                });
            },
            /*------ 获取搜索结果 ok ---------*/
            doSearch:function() {
                let _this = this;
                let sv = this.sv;
                if(sv.length >= 1){
                    let data = {_token: "{{ csrf_token() }}",id:'{{$id}}',sv:sv};
                    _this.$http.post('{{ route('salary.api_salary_a') }}', data).then(function (response) {
                        response = response.body;
                        if(response.status == 'success'){
                            _this.editorTables = response.data.items;
                            _this.gz = response.data.total.total_salary;
                            _this.ylbxf = response.data.total.total_yanglaobx;
                            _this.ylbxfy = response.data.total.total_yiliaobx;
                            _this.sybxf = response.data.total.total_sybx;
                            _this.dbylfy = response.data.total.total_dbyl;
                            _this.txf = response.data.total.total_txf;
                            _this.totalf = (Number(_this.ylbxf)+Number(_this.ylbxfy)+Number(_this.sybxf)+Number(_this.dbylfy)+Number(_this.txf)).toFixed(2);
                            _this.otherF = response.data.total.total_other_fee;
                            _this.dkgsf = response.data.total.total_personal_tax;
                            _this.dkgjj = response.data.total.total_dkgjj;
                            _this.sfgzf = response.data.total.total_real_salary;

                            _this.payrollOption = response.data.cost_list;
                        }else{
                            _this.editorTables = [];
                            _this.gz = '0.00';
                            _this.ylbxf = '0.00';
                            _this.ylbxfy = '0.00';
                            _this.sybxf = '0.00';
                            _this.dbylfy = '0.00';
                            _this.txf = '0.00';
                            _this.totalf = '0.00';
                            _this.otherF = '0.00';
                            _this.dkgsf = '0.00';
                            _this.dkgjj = '0.00';
                            _this.sfgzf = '0.00';

                            _this.payrollOption = [];
                        }
                        layer.load(2, {shade: false, time: 500});
                    })
                }else{
                    layer.msg("请输入姓名进行搜索!", {icon: 2, time: 2000});
                }
            },
            /*------ 获取员工薪酬列表 ok ---------*/
            getEmployeeSalaryList: function(){
                let _this = this;
                let data = {_token: "{{ csrf_token() }}",id:'{{$id}}'};
                _this.$http.post('{{ route('salary.api_salary_a') }}', data).then(function (response) {
                    response = response.body;
                    if(response.status == 'success'){
                        if(response.data.items.length >= 1){
                            _this.editorTables = response.data.items;
                            _this.gz = response.data.total.total_salary;
                            _this.ylbxf = response.data.total.total_yanglaobx;
                            _this.ylbxfy = response.data.total.total_yiliaobx;
                            _this.sybxf = response.data.total.total_sybx;
                            _this.dbylfy = response.data.total.total_dbyl;
                            _this.txf = response.data.total.total_txf;
                            _this.totalf = (Number(_this.ylbxf)+Number(_this.ylbxfy)+Number(_this.sybxf)+Number(_this.dbylfy)+Number(_this.txf)).toFixed(2);
                            _this.otherF = response.data.total.total_other_fee;
                            _this.dkgsf = response.data.total.total_personal_tax;
                            _this.dkgjj = response.data.total.total_dkgjj;
                            _this.sfgzf = response.data.total.total_real_salary;

                            //_this.payrollOption = response.data.cost_list;
                        }
                    }
                    _this.payrollOption = response.data.cost_list;
                    layer.load(2, {shade: false, time: 500});
                })
            }
        },
        watch: {
            "selected":function(){
                if(this.selected.length == this.editorTables.length){
                    this.checked = true;
                }else{
                    this.checked = false;
                }
            }
        }
    })
</script>
<script>
    let addPersonal =  new Vue({
        "el": "#addPersorner",
        data: {
            checked: false,
            selected: [],
            addTables: ''
        },
        methods:{
            allSelect:function(){
                if(this.selected.length != this.addTables.length){
                    this.selected = [];
                    for(let i in this.addTables){
                        //this.selected.push(this.addTables[i].name);
                        this.selected.push([this.addTables[i].id,this.addTables[i].name]);
                    }
                }else{
                    this.selected = [];
                }
            }
        },
        watch: {
            "selected" :function(){
                if(this.selected.length == this.addTables.length){
                    this.checked = true;
                }else{
                    this.checked = false;
                }
            }
        }
    })
</script>
<!-- 复制薪酬弹窗调用的VUE -->
<script>
    layui.use(['layer','laydate'],function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#copy_monthData'
            ,eventElem: '#copy_monthData-1'
            ,trigger: 'click'
            ,type:'month'
        })
    });
    new Vue({
        'el': '.copy_salary_employee',
        data: {
            copy_monthShow: true,
            copy_to:'2017-06'
        },
        created:function(){}
    })
</script>
@endsection
