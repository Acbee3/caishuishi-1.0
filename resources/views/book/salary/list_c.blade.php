@extends('book.layout.base')

@section('title')全年一次性奖金@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--薪酬表-->
    <link rel="stylesheet" href="{{asset("css/book/paid/payrollEditor.css")}}">
@endsection

@section('content')
<div class="payrollYearEditor" v-cloak>
    <div class="payrollEditorWrapper">
        <div class="payrollEditorMenu">
            <div class="payrollEditorMenu-left">
                <div class="payrollMenuBlack">
                    <a href="javascript:history.back(-1);" class="payrollBlack">返回</a>
                </div>
            </div>
            <div class="payrollEditorMenu-right">
                <a href="javascript:void(0);" @click="addPersorner">新增</a>
                <a href="javascript:void(0);" @click="keepPayroll">保存</a>
                <a href="javascript:void(0);" @click="yearEditor">编辑</a>
                <a href="javascript:void(0);" @click="doPrinting">打印</a>
            </div>
        </div>
        <div class="payrollYearTable">
            <div class="yearTable-header">
                <table>
                    <thead>
                    <tr>
                        <th  class="width2"></th>
                        <th  class="width14">姓名</th>
                        <th class="width14">费用类型</th>
                        <th class="width14">全年一次性奖金</th>
                        <th class="width14">
                            减除费用(补差)
                            <i class="iconfont icon-tixing"></i>
                        </th>
                        <th class="width14">代扣个税</th>
                        <th class="width14">实发奖金</th>
                        <th class="width14">操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="yearTable-Center">
                <table>
                    <tr v-for="(item,index) in yearTable" :key="item" :cur_row="item.select" :cur_num="index" class="cur_row">
                        <td class="width2">@{{index+1}}</td>
                        <td class="width14">@{{item.name}}</td>
                        <td class="width14" @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.type}}</span>
                            <div class="payroll-menu" v-show="item.select">
                                <div class="payroll-select curList">
                                    <div @click="item.payroll =! item.payroll">
                                        <input type="text" class="payrollSelectText"  :value="item.type">
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
                        <td class="width14" @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.totalMoney}}</span>
                            <input type="text" v-show="item.select" :value="item.totalMoney" v-model="item.totalMoney" @blur="moneyComputed(item,index)">
                        </td>
                        <td class="width14" @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.daff}}</span>
                            <input type="text" v-show="item.select" :value="item.daff" v-model="item.daff" @blur="moneyComputed(item,index)">
                        </td>
                        <td class="width14" @click="doEditor(index,item)">
                            <span v-show="item.top">@{{item.dkgs}}</span>
                            <input type="text" v-show="item.select" :value="item.dkgs" v-model="item.dkgs" @blur="moneyComputed(item,index)">
                        </td>
                        <td class="width14">@{{item.bonus}}</td>
                        <td class="width14">
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
                        <td class="width14">合计</td>
                        <td class="width14"></td>
                        <td class="width14">@{{allYearMoney}}</td>
                        <td class="width14">@{{costReduction}}</td>
                        <td class="width14">@{{dkgsfy}}</td>
                        <td class="width14">@{{sfMoney}}</td>
                        <td class="width14"></td>
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
@endsection

@section('script')
<script>
    new Vue({
        "el": '.payrollYearEditor',
        data: {
            allYearMoney: '0.00',
            costReduction: '0.00',
            dkgsfy: '0.00',
            sfMoney: '0.00',
            payrollOption:'',
            yearTable:[]
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
                        for(var i in _this.yearTable){
                            _this.yearTable[i].payroll = false;
                        }
                    }
                });
            },
            /*---生成凭证后顶部的编辑 -------*/
            yearEditor:function(){
                layer.open({
                        type: 1,
                        title: '信息',
                        skin: 'yearMoneyEditor',
                        content: '该工资已经生成凭证，是否删除该凭证?',
                        area: ['280px', '150px'],
                        btn:['确定','取消']
                    }
                )
            },
            /*----每行费用类型-----*/
            getpayrollList:function(payrolls,item){
                item.type = payrolls;
                item.payroll = false;
            },
            /*-----每行的编辑 ok ------*/
            doEditor: function (index, item) {
                let _this = this;
                let cur = $(".yearTable-Center table").find('tr[cur_row="true"]').attr('cur_num');
                if( cur >= '0'){
                    let curIndex = parseInt(cur)+parseInt('1');
                    if(curIndex >= 1  && cur != index)
                    {
                        if(_this.yearTable[cur].totalMoney > '0.00')
                        {
                            // 提交保存
                            let data = {
                                _token: "{{ csrf_token() }}",
                                employee_id:_this.yearTable[cur].id,
                                salary_id:'{{$id}}',
                                employee_name:_this.yearTable[cur].name,
                                do:_this.yearTable[cur].do,
                                salary:'1.00',
                                se_id:_this.yearTable[cur].se_id,
                                fylx:_this.yearTable[cur].type,
                                year_bonus:_this.yearTable[cur].totalMoney,
                                jcfy:_this.yearTable[cur].daff,
                                sfjj:_this.yearTable[cur].bonus,
                                personal_tax:_this.yearTable[cur].dkgs
                            };
                            _this.$http.post('{{ route('salary.api_save_salary') }}', data).then(function (response) {
                                if (response.body.status == 'success') {
                                    // 更新相关 否则会重复新增
                                    _this.yearTable[cur].do = 'update';
                                    _this.yearTable[cur].se_id = response.body.id;
                                    _this.yearTable[cur].dkgs = response.body.data.personal_tax;
                                    _this.yearTable[cur].bonus = response.body.data.year_bonus;
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
                    for (let i in this.yearTable) {
                        this.yearTable[i].top = true;
                        this.yearTable[i].select = false;
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
                let cur = $(".yearTable-Center table").find('tr[cur_row="true"]').attr('cur_num');
                if( cur >= '0') {
                    // 有编辑行
                    let curIndex = parseInt(cur)+parseInt('1');

                    if(_this.yearTable[cur].totalMoney > '0.00')
                    {
                        // 提交保存
                        let data = {
                            _token: "{{ csrf_token() }}",
                            employee_id:_this.yearTable[cur].id,
                            salary_id:'{{$id}}',
                            employee_name:_this.yearTable[cur].name,
                            do:_this.yearTable[cur].do,
                            salary:'1.00',
                            se_id:_this.yearTable[cur].se_id,
                            fylx:_this.yearTable[cur].type,
                            year_bonus:_this.yearTable[cur].totalMoney,
                            jcfy:_this.yearTable[cur].daff,
                            sfjj:_this.yearTable[cur].bonus,
                            personal_tax:_this.yearTable[cur].dkgs
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
                    for (let i in this.yearTable) {
                        this.yearTable[i].top = true;
                        this.yearTable[i].select = false;
                    }
                    layer.msg("当前没有更改任何数据哦！", {icon: 1, time: 1000});
                }
            },
            /*-----全年一次奖金与减除费用失去焦点  计算的实发奖金---*/
            //全年一次性奖金totalMoney、减除费用daff、代扣个税dkgs、实发奖金bonus、
            //全年奖金的和totalYearMoney，减除费用和totalDaff、代扣个税totalDkgs、实发奖金和totalBonus
            moneyComputed:function(item,index){
                this.$options.methods.moneysComputed.bind(this)();
            },
            moneysComputed:function(){
                var totalYearMoney = 0;
                var totalDaff = 0;
                var totalDkgs = 0;
                var totalBonus = 0;
                for(var i in this.yearTable){
                    this.yearTable[i].totalMoney = Number(this.yearTable[i].totalMoney).toFixed(2);
                    this.yearTable[i].daff = Number(this.yearTable[i].daff).toFixed(2);
                    this.yearTable[i].bonus = (Number(this.yearTable[i].totalMoney) - Number(this.yearTable[i].daff) - Number(this.yearTable[i].dkgs)).toFixed(2);
                    totalYearMoney += Number(this.yearTable[i].totalMoney);
                    totalDaff += Number(this.yearTable[i].daff);
                    totalDkgs += Number(this.yearTable[i].dkgs);
                    totalBonus += Number(this.yearTable[i].bonus);
                }
                this.allYearMoney = Number(totalYearMoney).toFixed(2);
                this.costReduction = Number(totalDaff).toFixed(2);
                this.dkgsfy = Number(totalDkgs).toFixed(2);
                this.sfMoney = Number(totalBonus).toFixed(2);
            },
            /*-----新增人员按钮 ok --------*/
            addPersorner: function () {
                var _this = this
                layer.open({
                    type: 1,
                    title: '新增人员',
                    skin: 'addAleart',
                    shadeClose: true,
                    shade: false,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['510px', '360px'],
                    content: $('#addPersorner'),
                    btn:['确定','取消'],
                    yes: function(index){
                        if(addPersonal.selected.length >= 1){
                            for(var j = 0; j< addPersonal.selected.length;j++){
                                _this.yearTable.splice(_this.yearTable.length, 0, {
                                        id: addPersonal.selected[j][0],
                                        name: addPersonal.selected[j][1],
                                        type: '管理费用',
                                        totalMoney: '0.00',
                                        daff: '0.00',
                                        dkgs: '0.00',
                                        bonus: '0.00',
                                        top:true,
                                        select:false,
                                        payroll:false,
                                        do: 'insert',
                                        se_id: ''
                                    }
                                )
                            }
                            /*----新增对象的所有数据-----*/
                            _this.yearTable[_this.yearTable.length - addPersonal.selected.length].top = false;
                            _this.yearTable[_this.yearTable.length - addPersonal.selected.length].select = true;
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
                                layer.msg(response.body.msg, {icon: 2, time: 5000});
                            }
                        });
                    }
                })
            },
            /*----行内删除 ok -----*/
            doDel:function(index,item){
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
                                    _this.getEmployeeSalaryList();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 1000});
                                }
                            })
                        }
                    );
                }else{
                    layer.confirm('确定要删除 ' + item.name + ' 的薪酬吗？', {icon: 3, title: '提示'},
                        function () {
                            _this.yearTable.splice(index, 1);
                            _this.$options.methods.allComputed.bind(this)();
                            layer.closeAll();
                        }
                    );
                }
            },
            /*------ 打印 delay ---------*/
            doPrinting:function(){
                layer.msg("打印功能暂缓开发!", {icon: 2, time: 2000});
            },
            /*------ 获取员工薪酬列表 ok ---------*/
            getEmployeeSalaryList: function(){
                let _this = this;
                let data = {_token: "{{ csrf_token() }}",id:'{{$id}}'};
                _this.$http.post('{{ route('salary.api_salary_c') }}', data).then(function (response) {
                    response = response.body;
                    if(response.status == 'success'){
                        if(response.data.items.length >= 1){
                            _this.yearTable = response.data.items;
                            _this.allYearMoney = response.data.total.total_year_bonus;
                            _this.costReduction = response.data.total.total_jcfy;
                            _this.dkgsfy = response.data.total.total_personal_tax;
                            _this.sfMoney = response.data.total.total_sfjj;

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
@endsection