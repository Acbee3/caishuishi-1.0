@extends('book.layout.base')

@section('title')临时工资薪金@endsection

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
            </div>
            <div class="payrollEditorMenu-right">
                <a href="javascript:void(0);" @click="addPersorner">新增</a>
                <a href="javascript:void(0);" @click="copySalaryBill">复制</a>
                <a href="javascript:void(0);" @click="keepPayroll">保存</a>
                <a href="javascript:void(0);" @click="doPrinting">打印</a>
            </div>
        </div>
        <div class="payrollEditorTable">
            <div class="editorTable-header">
                <table>
                    <tbody>
                    <tr>
                        <td rowspan="2" class="width2"></td>
                        <td rowspan="2" class="width4">姓名</td>
                        <td rowspan="2">费用类型</td>
                        <td rowspan="2">应发工资</td>
                        <td rowspan="1" colspan="6">代扣社保</td>
                        <td rowspan="2">代扣公积金</td>
                        <td rowspan="2">实发工资</td>
                        <td rowspan="2">操作</td>
                    </tr>
                    <tr>
                        <td rowspan="1">养老保险</td>
                        <td rowspan="1">医疗保险</td>
                        <td rowspan="1">失业保险</td>
                        <td rowspan="1">大病医疗</td>
                        <td rowspan="1">其他</td>
                        <td rowspan="1">合计</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="editorTable-Center">
                <table>
                    <tr v-for="(item,index) in lsTables" :key="item.id" :cur_row="item.select" :cur_num="index" class="cur_row">
                        <td class="width2">@{{index+1}}</td>
                        <td class="width4">@{{item.name}}</td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.moneyType}}</span>
                            <div class="payroll-menu" v-show="item.select">
                                <div class="payroll-select curList">
                                    <div @click="item.payroll =! item.payroll">
                                        <input type="text" class="payrollSelectText"  :value="item.moneyType">
                                        <span class="textIcon">
                                            <i class="icon iconfont">&#xe616;</i>
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
                            <input type="text" v-show="item.select" :value="item.money" v-model="item.money" @blur="yfgzComputed(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.ylbx}}</span>
                            <input type="text" v-show="item.select" :value="item.ylbx" v-model="item.ylbx" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.doctor}}</span>
                            <input type="text" v-show="item.select" :value="item.doctor" v-model="item.doctor" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.sybx}}</span>
                            <input type="text" v-show="item.select" :value="item.sybx" v-model="item.sybx" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.dbbx}}</span>
                            <input type="text" v-show="item.select" :value="item.dbbx" v-model="item.dbbx" @blur="dksb(item,index)">
                        </td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.other}}</span>
                            <input type="text" v-show="item.select" :value="item.other" v-model="item.other" @blur="dksb(item,index)">
                        </td>
                        <td>@{{item.total}}</td>
                        <td @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.dkgj}}</span>
                            <input type="text" v-show="item.select" :value="item.dkgj" v-model="item.dkgj" @blur="yfgzComputed(item,index)">
                        </td>
                        <td>@{{item.sfgz}}</td>
                        <td>
                            <i class="iconfont" @click="doEditor(index,item)">&#xe600;</i>
                            <i class="iconfont del" @click="doDel(index,item)">&#xe620;</i>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="editorFooter">
                <table>
                    <tbody>
                    <tr>
                        <td class="width2"></td>
                        <td class="width4">合计</td>
                        <td></td>
                        <td>@{{yfgz}}</td>
                        <td>@{{ylbxfy}}</td>
                        <td>@{{ylbxfee}}</td>
                        <td>@{{sybxfy}}</td>
                        <td>@{{dbylfy}}</td>
                        <td>@{{otherfy}}</td>
                        <td>@{{totalfy}}</td>
                        <td>@{{dkgjjfy}}</td>
                        <td>@{{sfgzfy}}</td>
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
                    <i class="iconfont" id="copy_monthData-1">&#xe616;</i>
                </div>
                <span>复制至</span>
                <div class="copy" id="to_monthShow">
                    <input type="text" disabled :value="copy_to" id="copy_to">
                    <i class="iconfont">&#xe616;</i>
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
            yfgz: '0.00',
            ylbxfy: '0.00',
            ylbxfee:'0.00',
            sybxfy:'0.00',
            dbylfy:'0.00',
            otherfy:'0.00',
            totalfy: '0.00',
            dkgjjfy: '0.00',
            sfgzfy: '0.00',
            payrollOption:[
                '管理费用','公共费用'
            ],
            lsTables:[]
        },
        created:function(){
            // 获取列表数据 ok
            this.getEmployeeSalaryList();
        },
        mounted:function(){
            this.clickBlank()
        },
        methods:{
            //点击空白处相应div隐藏
            clickBlank:function(){
                /*-------费用类型--------*/
                var _this = this;
                $(document).click(function(event){
                    var _con = $('.curList');  // 设置目标区域
                    if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
                        for(var i in _this.lsTables){
                            _this.lsTables[i].payroll = false;
                        }
                    }
                });
            },
            /*---当行获取费用类型--*/
            getpayrollList: function (payrolls, item) {
                item.moneyType = payrolls;
                item.payroll = false;
            },
            /*---- 行内编辑 ok -----*/
            doEditor: function (index,item) {
                let _this = this;
                let cur = $(".editorTable-Center table").find('tr[cur_row="true"]').attr('cur_num');
                if( cur >= '0'){
                    let curIndex = parseInt(cur)+parseInt('1');
                    if(curIndex >= 1  && cur != index)
                    {
                        if(_this.lsTables[cur].money > '0.00')
                        {
                            // 提交保存
                            let data = {
                                _token: "{{ csrf_token() }}",
                                salary:_this.lsTables[cur].money,
                                employee_id:_this.lsTables[cur].id,
                                salary_id:'{{$id}}',
                                employee_name:_this.lsTables[cur].name,
                                do:_this.lsTables[cur].do,
                                se_id:_this.lsTables[cur].se_id,
                                yanglaobx:_this.lsTables[cur].ylbx,
                                yiliaobx:_this.lsTables[cur].doctor,
                                sybx:_this.lsTables[cur].sybx,
                                dbyl:_this.lsTables[cur].dbbx,
                                other_fee:_this.lsTables[cur].other,
                                dkgjj:_this.lsTables[cur].dkgj,
                                fylx:_this.lsTables[cur].moneyType
                            };
                            _this.$http.post('{{ route('salary.api_save_salary') }}', data).then(function (response) {
                                if (response.body.status == 'success') {
                                    // 更新相关 否则会重复新增
                                    _this.lsTables[cur].do = 'update';
                                    _this.lsTables[cur].se_id = response.body.id;
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
                        return false;
                    } else {
                        layer.msg("第"+curIndex+"行数据不全，请补全数据！", {icon: 2, time: 2000});
                        return false;
                    }

                    // 关闭其他编辑
                    for (let i in this.lsTables) {
                        this.lsTables[i].top = true;
                        this.lsTables[i].select = false;
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

                    if(_this.lsTables[cur].money > '0.00')
                    {
                        // 提交保存
                        let data = {
                            _token: "{{ csrf_token() }}",
                            salary:_this.lsTables[cur].money,
                            employee_id:_this.lsTables[cur].id,
                            salary_id:'{{$id}}',
                            employee_name:_this.lsTables[cur].name,
                            do:_this.lsTables[cur].do,
                            se_id:_this.lsTables[cur].se_id,
                            yanglaobx:_this.lsTables[cur].ylbx,
                            yiliaobx:_this.lsTables[cur].doctor,
                            sybx:_this.lsTables[cur].sybx,
                            dbyl:_this.lsTables[cur].dbbx,
                            other_fee:_this.lsTables[cur].other,
                            dkgjj:_this.lsTables[cur].dkgj,
                            fylx:_this.lsTables[cur].moneyType
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
                    for (let i in this.lsTables) {
                        this.lsTables[i].top = true;
                        this.lsTables[i].select = false;
                    }
                    layer.msg("当前没有更改任何数据哦！", {icon: 1, time: 1000});
                }
            },
            /*-----新增人员按钮 ok --------*/
            addPersorner: function () {
                let _this = this;
                layer.open({
                    type: 1,
                    title: '新增人员',
                    skin: 'addAleart',
                    shadeClose: true,
                    shade: false,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['510px', '360px'],
                    content:$('#addPersorner'),
                    btn:['确定','取消'],
                    yes: function(index){
                        if(addPersonal.selected.length >= 1){
                            for(let j = 0; j< addPersonal.selected.length;j++){
                                //console.log(addPersonal.selected[j])
                                let cur = _this.lsTables.length
                                _this.lsTables.splice(_this.lsTables.length, 0, {
                                        id: addPersonal.selected[j][0],
                                        name: addPersonal.selected[j][1],
                                        moneyType: '管理费用',
                                        money: '0.00',
                                        ylbx: '0.00',
                                        doctor:'0.00',
                                        sybx: '0.00',
                                        dbbx: '0.00',
                                        other: '0.00',
                                        total: '0.00',
                                        dkgj: '0.00',
                                        sfgz: '0.00',
                                        top:true,
                                        select:false,
                                        payroll:false,
                                        do: 'insert',
                                        se_id: ''
                                    }
                                )
                            }
                            /!*---- 新增首行设置成编辑状态 -----*!/
                            _this.lsTables[_this.lsTables.length - addPersonal.selected.length].top = false;
                            _this.lsTables[_this.lsTables.length - addPersonal.selected.length].select = true;
                        }
                        layer.close(index)
                    },
                    success: function () {
                        let data = {
                            _token: "{{ csrf_token() }}",
                            salary_id:'{{$id}}'
                        };
                        _this.$http.post('{{ route('salary.api_get_employee') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                addPersonal.addTables = response.body.data;
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 2000});
                            }
                        });
                    }
                })
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
                    skin: 'copy_salary',
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
            /*----养老保险,医疗保险,失业保险,大病医疗,其他,input失去焦点计算实发工资合计---实发工资--*/
            dksb: function (item, index) {
                this.$options.methods.allComputed.bind(this)();
            },
            /*----应发工资与代扣公积金失去焦点后计算实发工资-=----*/
            yfgzComputed:function(item,index){
                this.$options.methods.allComputed.bind(this)();
            },
            //临时工资计算、实发工资money、养老保险ylbx、医疗保险doctor、失业保险sybx、大病医疗dbbx、其他other、合计total、代扣公积金dkgj、实发工资sfgz
            //yfgz、ylbxfy、ylbxfee、sybxfy、dbylfy、otherfy、totalfy、dkgjjfy、sfgzfy
            allComputed:function(item,index){
                let gzfee = 0;
                let ylbxfee = 0;
                let ylbxfy = 0;
                let sybxfee = 0;
                let dbylfee = 0;
                let totalfee = 0;
                let otherFy = 0;
                let dkgsfy = 0;
                let sfgzfy = 0;
                for (let i in this.lsTables) {
                    this.lsTables[i].money = Number(this.lsTables[i].money).toFixed(2);
                    this.lsTables[i].ylbx = Number(this.lsTables[i].ylbx).toFixed(2);
                    this.lsTables[i].doctor = Number(this.lsTables[i].doctor).toFixed(2);
                    this.lsTables[i].sybx = Number(this.lsTables[i].sybx).toFixed(2);
                    this.lsTables[i].dbbx = Number(this.lsTables[i].dbbx).toFixed(2);
                    this.lsTables[i].other = Number(this.lsTables[i].other).toFixed(2);
                    let sbhj = Number(this.lsTables[i].ylbx) + Number(this.lsTables[i].doctor) + Number(this.lsTables[i].sybx) + Number(this.lsTables[i].dbbx)+ Number(this.lsTables[i].other);
                    this.lsTables[i].total = sbhj.toFixed(2);
                    this.lsTables[i].dkgj = Number(this.lsTables[i].dkgj).toFixed(2);
                    let sfMoney = Number(this.lsTables[i].money) - Number(this.lsTables[i].total) - Number(this.lsTables[i].dkgj);
                    this.lsTables[i].sfgz = sfMoney.toFixed(2);
                    if (this.lsTables[i].sfgz < 0) {
                        this.lsTables[i].sfgz = '0.00'
                    }
                    gzfee += Number(this.lsTables[i].money);
                    ylbxfee += Number(this.lsTables[i].ylbx);
                    ylbxfy += Number(this.lsTables[i].doctor);
                    sybxfee += Number(this.lsTables[i].sybx);
                    dbylfee += Number(this.lsTables[i].dbbx);
                    otherFy += Number(this.lsTables[i].other);
                    totalfee += Number(this.lsTables[i].total);
                    dkgsfy += Number(this.lsTables[i].dkgj);
                    sfgzfy += Number(this.lsTables[i].sfgz);
                }
                this.yfgz = gzfee.toFixed(2);
                this.ylbxfy = ylbxfee.toFixed(2);
                this.ylbxfee = ylbxfy.toFixed(2);
                this.sybxfy = sybxfee.toFixed(2);
                this.dbylfy = dbylfee.toFixed(2);
                this.otherfy = otherFy.toFixed(2);
                this.totalfy = totalfee.toFixed(2);
                this.dkgjjfy = dkgsfy.toFixed(2);
                this.sfgzfy = sfgzfy.toFixed(2);
            },
            /*---删除行薪酬 ok --*/
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
                            _this.lsTables.splice(index,1);
                            _this.$options.methods.allComputed.bind(this)();
                            layer.closeAll();
                        }
                    );
                }
            },
            /*------ 获取员工薪酬列表 ok ---------*/
            getEmployeeSalaryList: function(){
                let _this = this;
                let data = {_token: "{{ csrf_token() }}",id:'{{$id}}'};
                _this.$http.post('{{ route('salary.api_salary_b') }}', data).then(function (response) {
                    response = response.body;
                    if(response.status == 'success'){
                        if(response.data.items.length >= 1){
                            _this.lsTables = response.data.items;
                            _this.yfgz = response.data.total.total_salary;
                            _this.ylbxfy = response.data.total.total_yanglaobx;
                            _this.ylbxfee = response.data.total.total_yiliaobx;
                            _this.sybxfy = response.data.total.total_sybx;
                            _this.dbylfy = response.data.total.total_dbyl;
                            _this.otherfy = response.data.total.total_other_fee;
                            _this.totalf = (Number(_this.ylbxfy)+Number(_this.ylbxfee)+Number(_this.sybxfy)+Number(_this.dbylfy)+Number(_this.otherfy)).toFixed(2);
                            _this.dkgjjfy = response.data.total.total_dkgjj;
                            _this.sfgzfy = response.data.total.total_real_salary;

                            //_this.payrollOption = response.data.cost_list;
                            //console.log(response.data.cost_list);
                        }
                    }
                    _this.payrollOption = response.data.cost_list;
                    layer.load(2, {shade: false, time: 500});
                })
            }
        }
    })
</script>
<script>
    var addPersonal =  new Vue({
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
                    for(var i in this.addTables){
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