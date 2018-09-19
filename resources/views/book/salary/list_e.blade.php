@extends('book.layout.base')

@section('title')劳务报酬@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--薪酬表-->
    <link rel="stylesheet" href="{{asset("css/book/paid/payrollEditor.css")}}">
@endsection

@section('content')
    <div class="labourService" v-cloak>
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
                    <a href="javascript:void(0);" @click="addPersorner">新增</a>
                    <a href="javascript:void(0);" @click="keepPayroll">保存</a>
                    <a href="javascript:void(0);" @click="doPrinting">打印</a>
                </div>
            </div>
            <div class="payrollYearTable">
                <div class="yearTable-header">
                    <table>
                        <thead>
                        <tr>
                            <th class="width2"></th>
                            <th class="width18">姓名</th>
                            <th class="width16">费用类型</th>
                            <th class="width16">劳务报酬</th>
                            <th class="width16">代扣个税</th>
                            <th class="width16">实发劳务报酬</th>
                            <th class="width16">操作</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="yearTable-Center">
                    <table>
                        <tr v-for="(item,index) in labourTables" :key="item" :cur_row="item.select" :cur_num="index" class="cur_row">
                            <td class="width2">@{{index+1}}</td>
                            <td class="width18">@{{item.name}}</td>
                            <td class="width16" @click="doEditor(index,item)">
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
                            <td class="width16" @click="doEditor(index,item)">
                                <span v-show="item.top">@{{item.money}}</span>
                                <input type="text" v-show="item.select" :value="item.dbbx" v-model="item.money" @blur="gzComputed(item,index)">
                            </td>
                            <td class="width16">@{{item.dkgs}}</td>
                            <td class="width16">@{{item.sflwbc}}</td>
                            <td class="width16">
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
                            <td class="width18">合计</td>
                            <td class="width16"></td>
                            <td class="width16">@{{lwbc}}</td>
                            <td class="width16">@{{dkgs}}</td>
                            <td class="width16">@{{sflwbc}}</td>
                            <td class="width16"></td>
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
            "el": '.labourService',
            data: {
                lwbc: '0.00',
                dkgs: '0.00',
                sflwbc: '0.00',
                payrollOption:'',
                labourTables:[],
                sv: ''
            },
            created:function(){
                // 获取列表数据
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
                            for(var i in _this.labourTables){
                                _this.labourTables[i].payroll = false;
                            }
                        }
                    });
                },
                /*----行内获取费用类型-----*/
                getpayrollList:function(payrolls,item){
                    item.type = payrolls;
                    item.payroll = false;
                },
                /*----行内删除--------*/
                labourDel:function(index){
                    this.labourTables.splice(index,1);
                    this.$options.methods.gzbcComputed.bind(this)();
                },
                /*----行内编辑 ok -------*/
                doEditor: function (index, item) {
                    let _this = this;
                    let cur = $(".yearTable-Center table").find('tr[cur_row="true"]').attr('cur_num');
                    if( cur >= '0'){
                        let curIndex = parseInt(cur)+parseInt('1');
                        if(curIndex >= 1  && cur != index)
                        {
                            if(_this.labourTables[cur].money > '0.00')
                            {
                                // 提交保存
                                let data = {
                                    _token: "{{ csrf_token() }}",
                                    employee_id:_this.labourTables[cur].id,
                                    salary_id:'{{$id}}',
                                    employee_name:_this.labourTables[cur].name,
                                    do:_this.labourTables[cur].do,
                                    salary:'1.00',
                                    se_id:_this.labourTables[cur].se_id,
                                    fylx:_this.labourTables[cur].type,
                                    lwbc:_this.labourTables[cur].money
                                };
                                _this.$http.post('{{ route('salary.api_save_salary') }}', data).then(function (response) {
                                    if (response.body.status == 'success') {
                                        // 更新相关 否则会重复新增
                                        _this.labourTables[cur].do = 'update';
                                        _this.labourTables[cur].se_id = response.body.id;
                                        _this.labourTables[cur].dkgs = response.body.data.personal_tax;
                                        _this.labourTables[cur].sflwbc = response.body.data.sflwbc;
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
                        for (let i in this.labourTables) {
                            this.labourTables[i].top = true;
                            this.labourTables[i].select = false;
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

                        if(_this.labourTables[cur].money > '0.00')
                        {
                            // 提交保存
                            let data = {
                                _token: "{{ csrf_token() }}",
                                employee_id:_this.labourTables[cur].id,
                                salary_id:'{{$id}}',
                                employee_name:_this.labourTables[cur].name,
                                do:_this.labourTables[cur].do,
                                salary:'1.00',
                                se_id:_this.labourTables[cur].se_id,
                                fylx:_this.labourTables[cur].type,
                                lwbc:_this.labourTables[cur].money
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
                        for (let i in this.labourTables) {
                            this.labourTables[i].top = true;
                            this.labourTables[i].select = false;
                        }
                        layer.msg("当前没有更改任何数据哦！", {icon: 1, time: 1000});
                    }
                },
                /*-----劳务报酬失去焦点---获取实发劳务报酬---*/
                gzComputed:function(item,index){
                    this.$options.methods.gzbcComputed.bind(this)();
                },
                gzbcComputed:function(){
                    //计算总的劳务报酬与实发劳务报酬
                    let lwbcfy = 0;
                    let sflwbcfy = 0;
                    for(let i in this.labourTables){
                        this.labourTables[i].money = Number(this.labourTables[i].money).toFixed(2)
                        let totalMoney = Number(this.labourTables[i].money) - Number(this.labourTables[i].dkgs)
                        this.labourTables[i].sflwbc = Number(totalMoney).toFixed(2)
                        lwbcfy += Number(this.labourTables[i].money)
                        sflwbcfy += Number(this.labourTables[i].sflwbc)
                    }
                    this.lwbc = lwbcfy.toFixed(2)
                    this.sflwbc = sflwbcfy.toFixed(2)
                },
                /*--------------新增劳务报酬 ok -----------*/
                addPersorner:function(){
                    let _this = this;
                    layer.open({
                        type: 1,
                        title: '请选择人员',
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
                                    _this.labourTables.splice(_this.labourTables.length, 0, {
                                            id: addPersonal.selected[j][0],
                                            name: addPersonal.selected[j][1],
                                            type:'管理费用',
                                            money: '0.00',
                                            dkgs: '0.00',
                                            sflwbc: '0.00',
                                            top:true,
                                            select:false,
                                            payroll:false,
                                            do: 'insert',
                                            se_id: ''
                                        }
                                    )
                                }
                                _this.labourTables[_this.labourTables.length - addPersonal.selected.length].top = false;
                                _this.labourTables[_this.labourTables.length - addPersonal.selected.length].select = true;
                            }
                            layer.close(index)
                        },
                        success: function () {
                            let data = {
                                _token: "{{csrf_token()}}",
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
                                _this.labourTables.splice(index, 1);
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
                /*------ 获取搜索结果 ok ---------*/
                doSearch:function() {
                    let _this = this;
                    let sv = this.sv;
                    if(sv.length >= 1){
                        let data = {_token: "{{ csrf_token() }}",id:'{{$id}}',sv:sv};
                        _this.$http.post('{{ route('salary.api_salary_e') }}', data).then(function (response) {
                            response = response.body;
                            if(response.status == 'success'){
                                _this.labourTables = response.data.items;
                                _this.lwbc = response.data.total.total_lwbc;
                                _this.dkgs = response.data.total.total_personal_tax;
                                _this.sflwbc = response.data.total.total_sflwbc;

                                _this.payrollOption = response.data.cost_list;
                            }else{
                                _this.labourTables = [];
                                _this.lwbc = '0.00';
                                _this.dkgs = '0.00';
                                _this.sflwbc = '0.00';

                                _this.payrollOption = [];
                            }
                            layer.load(2, {shade: false, time: 500});
                        })
                    }else{
                        layer.msg("请输入姓名进行搜索！", {icon: 2, time: 2000});
                    }
                },
                /*------ 获取员工薪酬列表 ok ---------*/
                getEmployeeSalaryList: function(){
                    let _this = this;
                    let data = {_token: "{{ csrf_token() }}",id:'{{$id}}'};
                    _this.$http.post('{{ route('salary.api_salary_e') }}', data).then(function (response) {
                        response = response.body;
                        if(response.status == 'success'){
                            if(response.data.items.length >= 1){
                                _this.labourTables = response.data.items;
                                _this.lwbc = response.data.total.total_lwbc;
                                _this.dkgs = response.data.total.total_personal_tax;
                                _this.sflwbc = response.data.total.total_sflwbc;

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