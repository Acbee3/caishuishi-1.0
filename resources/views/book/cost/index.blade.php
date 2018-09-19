@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/receipts.css?v=20180830">
@endsection

@section('content')

    <div class="receipts" v-cloak>
        <div class="formWrapper" style="z-index: 99">
            <form id="query" class="layui-form receiptsMenu" action="" method="get">
                <div class="receiptsMenuLeft">
                    <div>
                        <select name="q_key" lay-filter="q_key" v-model="q_key">
                            <option value="">全部</option>
                            <option value="qdzt">清单状态</option>
                            <option value="pzzt">凭证状态</option>
                        </select>
                    </div>
                    <div>
                        <select name="q_val" lay-filter="q_val" v-model="q_val">
                        </select>
                    </div>
                    <div class="receipts-invoice">
                        <a href="javascript:;" @click="query_select">查询</a>
                    </div>
                    {{--<div>--}}
                    {{--<p class="tipText">温馨提示：若需要自定义凭证摘要，请填写备注栏!</p>--}}
                    {{--</div>--}}
                </div>
                <div class="receiptsMenuRight">
                    <div class="receipts-money" style="display: none">
                        <span>余额:</span>
                        <i>2,537.86</i>
                        <span>元</span>
                    </div>
                    <div class="receipts-invoice">
                        <a class="add" href="javascript:;" @click="addExpense">新增</a>
                    </div>
                    <div class="receipts-invoice">
                        <a href="javascript:;" @click="importFiles">导入</a>
                    </div>
                    <div class="receipts-del">
                        <a href="javascript:;" @click="deleteSelected">删除</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="receiptsTable">
            <div class="receipts-thead tableBorderHead fixTableHeader">
                <table border="0">
                    <thead>
                    <tr>
                        <th class="width3"><input type="checkbox" @click="allSelect" v-model="checked"></th>
                        <th class="width10">日期</th>
                        <th class="width21">费用类型</th>
                        <th class="width10">金额</th>
                        <th class="width14">现金结算金额</th>
                        <th class="width14">单位名称</th>
                        <th class="width14">备注</th>
                        <th class="width14">操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div>
                <div class="receiptsEach" v-for="(expenseList,index) in allTables" :key="expenseList" ref="receiptsEach">
                    <div class="receipts-table tableBorder">
                        <table border="0">
                            <tbody>
                            <tr class="title-expense">
                                <td class="width3">
                                    <input v-model="selected" type="checkbox"  :disabled="expenseList.disableCkeck"
                                                          :value="expenseList.id">
                                </td>
                                <td colspan="7">
                                    <div class="receipts-tableTotal">
                                        <div class="receipts-tableInvoice">
                                            <div class="receipts-expense">
                                                <span>费用金额:</span>
                                                {{--<i>@{{expenseList.expenseMoney}}</i>--}}
                                                <i>@{{ total_fee(index) }}</i>
                                            </div>
                                            <div class="receipts-invoiceText">
                                                <i>凭证号:</i>
                                                <span class="text" @click="addInvoice(expenseList)">@{{expenseList.voucher_num}}</span>
                                            </div>
                                        </div>
                                        <div class="receipts-tableInvoiceRight receipts-editor editorShow" >
                                            <i class="icon iconfont tableMarked" v-show="expenseList.editor" @click="makeVoucher(expenseList,index)">&#xe602;</i>
                                            <i class="icon iconfont tableEditor" @click="expenseEditor(index,expenseList)" v-show="expenseList.editor">&#xe606;</i>
                                            <i class="icon iconfont tableEditor" @click="expenseKeep(index,expenseList)" v-show="expenseList.keep">&#xe60b;</i>
                                            <i class="icon iconfont tableDel" @click="delTotal(index,expenseList)">&#xe605;</i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="receipts-tableCenter expense-tableCenter tableBorder">
                        <table border="0">
                            <tbody>
                            <tr v-for="(item,index) in expenseList.expenseTable" :key="item">
                                <td class="width3"></td>
                                <td class="width10 dateCur">
                                    <span v-show="item.top" class="curData">@{{ item.fyrq }}</span>
                                    <div class="expense-date" v-show="item.select">
                                        <input type="text" class="date dataCur" :data-id="item.id" v-model="item.fyrq" readonly @blur="getDateCur(item)" onclick="WdatePicker({ onpicking:onpick })">
                                        <span class="icon iconfont dataEle">&#xe61f;</span>
                                    </div>
                                </td>
                                <td class="width21">
                                    <span v-show="item.top">@{{item.expenseVal}}</span>
                                    <div class="expense-menu curList" v-show="item.select">
                                        <div class="expense-select">
                                            <div @click="item.expense =! item.expense">
                                                <input type="text" class="expenseSelectText" v-model="item.expenseVal">
                                                <span class="textIcon">
                                                <i class="icon iconfont">&#xe620;</i>
                                            </span>
                                            </div>
                                            <ul class="expenseSelect_ul" v-show="item.expense">
                                                <li v-for="expense in expenseOption" @click="getExpenseList(expense,item)">
                                                    <em>@{{expense.label}}</em>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="width10">
                                    <span v-show="item.top">@{{item.money}}</span>
                                    <input type="text" :value="item.money" v-show="item.select" v-model="item.money">
                                </td>
                                <td class="width14">
                                    <span v-show="item.top">@{{item.price}}</span>
                                    <input type="text" :value="item.price" v-show="item.select" v-model="item.price">
                                </td>
                                <td class="width14">
                                    <span v-show="item.top">@{{item.unit}}</span>
                                    <div class="expense-menu curList" v-show="item.select">
                                        <div class="expense-select">
                                            <div @click="item.expenseUnit =! item.expenseUnit">
                                                <input type="text" class="expenseSelectText" v-model="item.unit">
                                                <span class="textIcon">
                                                <i class="icon iconfont">&#xe620;</i>
                                            </span>
                                            </div>
                                            <ul class="expenseSelect_ul" v-show="item.expenseUnit">
                                                <li v-for="unit in expenseUnitOption" @click="getExpenseUnit(unit,item)">
                                                    <em>@{{unit.value}}</em>
                                                    <span>@{{unit.label}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="width14">
                                    <span v-show="item.top">@{{item.remarks}}</span>
                                    <input type="text" v-model="item.remarks" v-show="item.select">
                                </td>
                                <td class="width14">
                                    <div class="fs0">
                                        <i class="icon iconfont addEditor" @click="editorAdd(index,item,expenseList)">&#xe60c;</i>
                                        <i class="icon iconfont addEditor" @click="editorLine(item,expenseList)">&#xe606;</i>
                                        <i class="icon iconfont del" @click="editorDel(index,item,expenseList)">&#xe605;</i>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if( !empty($list) )
            {{ $list->appends(request()->toArray())->links() }}
        @endif
        <div id="importAlert" style="display: none">
            <form action="{{ url('book/cost/importExcel')  }}" id="uploadForm">
                <div class="filesUp">
                    <input type="text" readonly v-model="fileName">
                    <input type="hidden" id="csrf_token" value="{{csrf_token()}}" readonly>
                    <input type="file" name="file" id="excel_file" style="display:none;" @change="showFileName">
                    <a href="javascript:;" class="filesBtn" @click="upfile">文件上传</a>
                </div>
                <div class="expense-load">
                    <span>费用通用模板</span>
                    <a href="http://p9j4qv818.bkt.clouddn.com/费用模板_jFnrEnWKCKQK3leDb2eJiqWZ1R718dm5ZRSS6xpT.xls">下载</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script>
        layui.use(['form'], function () {
            var form = layui.form;

            initSelect();
            form.render();

            form.on("select(q_key)", function (data) {

                a.q_key = data.value || '{{ request('q_key') }}';
                a.q_val = '';

                var opt = {
                    qdzt: [{val: '', label: '全部'}, {val: 'unfinished', label: '数据不全'}, {val: 'finished', label: '数据完整'}],
                    pzzt: [{val: '', label: '全部'}, {val: 'todo', label: '未生成'}, {val: 'done', label: '已生成'}],
                };

                $('select[name=q_val]').empty();

                for (var i in opt[data.value]) {
                    $('select[name=q_val]').append("<option value='" + opt[data.value][i]['val'] + "'>" + opt[data.value][i]['label'] + "</option>");
                }

                form.render();
            });

            form.on("select(q_val)", function (data) {
                a.q_val = data.value;
                form.render();
            });

            //console.log(a.q_val);
            //console.log(a.q_key);

        });
        var curData;

        function initSelect() {
            var q_key = '{{ request('q_key') }}';
            var q_val = '{{ request('q_val') }}';
            var opt = {
                qdzt: [{val: '', label: '全部'}, {val: 'unfinished', label: '数据不全'}, {val: 'finished', label: '数据完整'}],
                pzzt: [{val: '', label: '全部'}, {val: 'todo', label: '未生成'}, {val: 'done', label: '已生成'}],
            };

            $('select[name=q_val]').empty();

            for (var i in opt[q_key]) {
                var select = opt[q_key][i]['val'] == q_val ? 'selected' : '';
                $('select[name=q_val]').append("<option " + select + " value='" + opt[q_key][i]['val'] + "'>" + opt[q_key][i]['label'] + "</option>");
            }
        }

        function onpick(dp) {
            /*console.log(dp.cal.getNewDateStr())*/
            curData = dp.cal.getNewDateStr();
            $(this).parents('.dateCur').find(".curData").html(curData);
            $(this).val(curData);
            //$('input[data-id=]')
        }

        var a = new Vue({
            "el": ".receipts",
            data: {
                getDate: '更多',
                receiptsList: false,
                options: [
                    {
                        value: '1',
                        label: '更多'
                    },
                    {
                        value: '2',
                        label: '导入(F12)'
                    },
                    {
                        value: '3',
                        label: '采集'
                    },
                    {
                        value: '4',
                        label: '本地提取'
                    },
                    {
                        value: '5',
                        label: '批量补充'
                    },
                    {
                        value: '6',
                        label: '批量结算'
                    },
                    {
                        value: '7',
                        label: '外币补充'
                    },
                    {
                        value: '8',
                        label: '排序'
                    },
                    {
                        value: '9',
                        label: '进项图片管理'
                    },
                    {
                        value: '10',
                        label: '习惯设置'
                    },
                    {
                        value: '11',
                        label: '导出Excel'
                    }
                ],
                status: 0,
                expenseOption: [
                    {
                        label: '4001生产成本',
                        value: 0,
                    },
                    {
                        label: '4002劳务成本',
                        value: 1,
                    },
                    {
                        label: '4001制造费用',
                        value: 2,
                    },
                    {
                        label: '560201管理费用_办公费',
                        value: 3,
                    },
                    {
                        label: '560202管理费用_业务招待费',
                        value: 4,
                    },
                ],
                expenseUnitOption: JSON.parse(JSON.stringify( {!! json_encode(\App\Entity\Invoice::dwList()) !!})),
                checked: false,
                selected:[],
                allTables: [],
                q_key: '{{request('q_key')}}',
                q_val: '{{request('q_val')}}',

                q_key_options: [{val: '', label: '全部'}, {val: 'qdzt', label: '清单状态'}, {val: 'pzzt', label: '凭证状态'}],
                //q_val_options: [{val: '', label: '全部2222'}, {val: 'unfinished', label: '数据不全'}, {val: 'finished', label: '数据完整'}],
                q_val_options: [],
                fileName: '',
            },
            created: function () {

                //console.log('{{request('q_key')}}');
                //console.log(this.q_key == '{{request('q_key')}}');

                this.mapClass = ['deductible', 'noDeductible'];
                this.dateMap = ['可抵用', '不可抵用'];
                var tmp_data = JSON.parse(JSON.stringify({!! json_encode($list->toArray()['data']) !!}));
                //console.log(tmp_data);return false;
                for (var i in tmp_data) {
                    var tmp = tmp_data[i];
                    var marks = '';
                    if (tmp['voucher'] != null && tmp['voucher']['voucher_num'] != null && tmp['voucher']['voucher_num'] != undefined) {
                        marks = tmp['voucher']['voucher_num'];
                    }
                    tmp_data[i] = {
                        expenseData: tmp['created_at'],
                        expenseMoney: tmp['total_money'],
                        fiscal_period: tmp['fiscal_period'],
                        voucher_num: tmp['voucher_num'],
                        voucher_id: tmp['voucher_id'],
                        marks: marks,
                        id: tmp['id'],
                        editor: true,
                        keep: false,
                        check: false,
                        disableCkeck: false,
                    };
                   if(tmp_data[i].voucher_num){
                       tmp_data[i].disableCkeck = true;
                   }
                    for (j in tmp['cost_item']) {
                        var tmp_item = tmp['cost_item'][j];
                        tmp['cost_item'][j] = {
                            id: tmp_item['id'],
                            id: tmp_item['id'],

                            data: '',
                            fyrq: tmp_item['fyrq'],
                            expenseVal: tmp_item['fylx'],
                            money: tmp_item['money'],
                            price: tmp_item['cash'],
                            account_id: tmp_item['account_id'],
                            account_number: tmp_item['account_number'],
                            account_name: tmp_item['account_name'],
                            unit: tmp_item['dw_name'],
                            unit_id: tmp_item['dw_id'],
                            remarks: tmp_item['remark'],
                            expense: false,
                            expenseUnit: false,
                            top: true,
                            select: false,
                            editor: true,
                            keep: false
                        };
                    }

                    tmp_data[i]['expenseTable'] = tmp['cost_item'];

                }
                this.allTables = tmp_data;

                this.expenseOption = [];
                var tmp_cost_item = JSON.parse('{!! json_encode((new \App\Entity\BusinessDataConfig\BusinessConfig(3))->getData()) !!}');
                for (var i in tmp_cost_item) {
                    this.expenseOption.push({label: tmp_cost_item[i]['name'], value: tmp_cost_item[i]['number']});
                }
            },
            mounted() {
                this.clickBlank()
            },
            methods: {
                //点击空白处相应div隐藏
                clickBlank:function(){
                    var _this = this;
                    $(document).click(function(event){
                        var _con = $('.curList');  // 设置目标区域
                        if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
                            $.each(_this.allTables,function(i,arr){
                                arr.expenseTable[0].expense = false;
                                arr.expenseTable[0].expenseUnit = false;
                            });
                        }
                    });
                },
                getNewAdds: function (value) {
                    this.receiptsList = false;
                    this.getDate = value
                },
                getExpenseList: function (expense, item) {
                    item.expenseVal = expense.label;
                    item.expense = false;
                    item.account_name = expense.label;
                    item.account_number = expense.value;
                },
                getExpenseUnit: function (unit, item) {
                    item.unit_id = unit.id;
                    item.unit = unit.value;
                    item.expenseUnit = false;
                },
                /*---编辑按钮---*/
                expenseEditor: function (index, expenseList) {
                    if (this.checkUnsave()) {
                        layer.msg('您有未保存的编辑！', {icon: 2, time: 1000});
                        return false;
                    }
                    expenseList.editor = false;
                    expenseList.keep = true;
                    for (var i in expenseList.expenseTable) {
                        expenseList.expenseTable[i].top = false;
                        expenseList.expenseTable[i].select = true;
                    }
                },
                /*--保存按钮---*/
                expenseKeep: function (index, expenseList) {
                    var _this = this;

                    for (var i in expenseList.expenseTable) {
                        if(expenseList.expenseTable[i].expenseVal == ''){
                            layer.msg('费用类型必填', {icon: 2, time: 700, shade: 0.2});
                            return;
                        }
                        if(expenseList.expenseTable[i].money == '' || expenseList.expenseTable[i].money == 0){
                            layer.msg('金额大于现金结算金额', {icon: 2, time: 700, shade: 0.2});
                            return;
                        }
                        if(expenseList.expenseTable[i].unit == ''){
                            layer.msg('单位名称必填', {icon: 2, time: 700, shade: 0.2});
                            return;
                        }
                        expenseList.expenseTable[i].top = true;
                        expenseList.expenseTable[i].select = false;
                        /*expenseList.expenseTable[i].data = curData*/
                    }
                    expenseList.editor = true;
                    expenseList.keep = false;
                    expenseList['_token'] = "{{csrf_token()}}";
                    expenseList['company_id'] = "{{ \App\Entity\Company::sessionCompany()->id }}";
                    expenseList['fiscal_period'] = "{{ \App\Entity\Period::currentPeriod() }}";

                    if (expenseList['id'] == undefined) {

                        //新增操作
                        _this.$http.post('{{ url('book/cost/add') }}', expenseList).then(function (response) {
                            if (response.body.status == 1) {
                                layer.msg(response.body.info, {icon: 1, time: 700, shade: 0.2});
                                setTimeout(function () {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                layer.msg(response.body.info, {icon: 2, time: 3000, shade: 0.2});
                                setTimeout(function () {
                                    //window.location.reload();
                                }, 1800);
                            }
                        });
                    } else {
                        //更新操作
                        _this.$http.post('{{ url('book/cost/update') }}', expenseList).then(function (response) {
                            if (response.body.status == 1) {
                                layer.msg(response.body.info, {icon: 1, time: 700, shade: 0.2});
                                setTimeout(function () {
                                    //window.location.reload();
                                }, 1000);
                            } else {
                                layer.msg(response.body.info, {icon: 2, time: 3000, shade: 0.2});
                                setTimeout(function () {
                                    //window.location.reload();
                                }, 1800);
                            }
                        });
                    }

                },
                /*---删除当前的整个table---*/
                delTotal: function (index, expenseList) {
                    /*console.log(index)
                    console.log(expenseList)*/
                    var _this = this;
                    var voucher_id = _this.allTables[index]['marks'];
                    var msg = voucher_id == 0 ? '确定删除吗？' : '当前发票已生成凭证，是否确定删除？';

                    layer.confirm(
                        msg, {icon: 3, title: '提示'},
                        function () {
                            _this.$http.post('{{ url('book/cost/delete') }}', {_token: "{{csrf_token()}}", id: expenseList['id']}).then(function (response) {
                                if (response.body.status == 1) {
                                    layer.msg(response.body.info, {icon: 1, time: 700, shade: 0.2});
                                    this.allTables.splice(index, 1);
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    layer.msg(response.body.info, {icon: 2, time: 3000, shade: 0.2});
                                    setTimeout(function () {
                                        //window.location.reload();
                                    }, 1800);
                                }
                            });
                        }
                    );

                },
                /*---行内的编辑----*/
                editorLine: function (item, expenseList) {
                    expenseList.editor = false;
                    expenseList.keep = true;
                    item.top = false;
                    item.select = true
                    /*-------------------------------------------最后补充----------------------*/
                    /*for(var i in this.allTables){
                        console.log(this.allTables[i].keep)
                    }*/
                },
                /*---行内删除---*/
                editorDel: function (index, item, expenseList) {
                    var _this = this;
                    var voucher_id = _this.allTables[index]['marks'];
                    var msg = '确定删除吗';

                    //console.log(item);return false;

                    if (voucher_id != '') {
                        layer.msg('当前发票已生成凭证，不允许删除明细内容，请先删除对应的记账凭证', {icon: 2, time: 3000, shade: 0.2});
                        return false;
                    }

                    layer.confirm(
                        msg, {icon: 3, title: '提示'},
                        function () {
                            _this.$http.post('{{ url('book/cost/deleteItem') }}', {_token: "{{csrf_token()}}", id: item['id']}).then(function (response) {
                                if (response.body.status == 1) {
                                    layer.msg(response.body.info, {icon: 1, time: 700, shade: 0.2});
                                    expenseList.expenseTable.splice(index, 1);
                                    setTimeout(function () {
                                        window.location.reload();
                                    }, 1000);
                                } else {
                                    layer.msg(response.body.info, {icon: 2, time: 3000, shade: 0.2});
                                    setTimeout(function () {
                                        //window.location.reload();
                                    }, 1800);
                                }
                            });
                        }
                    );
                },
                /*---行内新增----*/
                editorAdd: function (index, item, expenseList) {
                    expenseList.editor = false;
                    expenseList.keep = true;
                    expenseList.expenseTable.splice((index + 1), 0, {
                        data: '',
                        expenseData: '2018-6-26',
                        expenseVal: '',
                        money: '123.00',
                        price: '22',
                        unit: '财税',
                        remarks: '备注',
                        expense: false,
                        expenseUnit: false,
                        top: true,
                        select: false,
                        editor: true,
                        keep: false,
                    });
                    expenseList.expenseTable[(index + 1)].top = false;
                    expenseList.expenseTable[(index + 1)].select = true;
                },
                /*----获取当前时间-----*/
                getCurrentDate: function () {
                    var now_date = new Date();
                    return now_date.getFullYear() + '-' + (now_date.getMonth() + 1) + '-' + now_date.getDate();
                },
                /*----新增-----*/
                addExpense: function () {
                    //此处有点问题更改了
                    /*if (this.checkUnsave()) {
                        layer.msg('您有未保存的编辑！', {icon: 2, time: 2000});
                        return false;
                    }*/
                    for(var i in this.allTables){
                        if(this.allTables[i].keep){
                            layer.msg('您有未保存的编辑！', {icon: 2, time: 2000});
                            return false;
                        }
                    }
                    var cur = this.allTables.length;
                    this.allTables.splice(this.allTables.length, 0, {
                            expenseData: '',
                            expenseMoney: '',
                            marks: '',
                            editor: true,
                            keep: false,
                            check: false,
                            disableCkeck: false,
                            expenseTable: [
                                {
                                    data: '',
                                    fyrq: this.getCurrentDate(),
                                    expenseVal: '',
                                    money: '',
                                    price: '',
                                    unit: '',
                                    remarks: '',
                                    expense: false,
                                    expenseUnit: false,
                                    top: true,
                                    select: false,
                                    editor: true,
                                    keep: false
                                }
                            ]
                        }
                    );
                    /*console.log(this.allTables[this.allTables.length-1])*/
                    /*----新增对象的所有数据-----*/
                    this.allTables[this.allTables.length - 1].editor = false;
                    this.allTables[this.allTables.length - 1].keep = true;
                    this.allTables[this.allTables.length - 1].expenseTable[0].top = false;
                    this.allTables[this.allTables.length - 1].expenseTable[0].select = true;
                    //console.log(this.allTables);
                },
                /*----checkbox全选与反选-----*/
                /*allSelect: function (event) {
                    //console.log(event.target.checked);
                    this.checked = event.target.checked;
                    for (var i in this.allTables) {
                        if (!this.allTables[i]['disableCkeck']) {
                            this.allTables[i]['check'] = this.checked;
                        }
                    }
                },*/
                allSelect:function(){
                    layer.msg('已经生成凭证，无法进行批量生成，只能选择没生成凭证的', {icon: 3, time: 2000});
                    //expenseList.id
                    var arr = [];
                    for(var i in this.allTables){
                        if(this.allTables[i].voucher_num != ''){
                            arr.push(this.allTables[i].voucher_num)
                        }
                    }
                    if (this.selected.length != (this.allTables.length - arr.length)) {
                        this.selected = [];
                        for (var i in this.allTables) {
                            if(this.allTables[i].voucher_num == ''){
                                this.selected.push(this.allTables[i].id)
                            }
                        }
                    } else {
                        this.selected = [];
                    }
                },
                /*----点击记账-----*/
                /*=-------点击生成的凭证---记—12-----expenseList.marks-*/
                addInvoice:function(expenseList) {
                    var items = expenseList.voucher_id;
                    //localStorage.setItem('invoiceId',items);
                    localStorage.setItem('voucher_id',items);
                    layer.open({
                        type: 2,
                        title: '凭证信息',
                        shadeClose: true,
                        shade: 0.2,
                        skin:'pzAlert',
                        maxmin: true, //开启最大化最小化按钮
                        area: ['1200px', '96%'],
                        //content: ['{{ url('book/voucher/add') }}', 'yes']
                        content: ['{{ url('book/voucher/edit') }}', 'yes']
                    });
                },
                /*-------点击记图标------------*/
                makeVoucher: function (expenseList,index) {
                    if (expenseList.voucher_num !== '') {
                        layer.msg('已生成凭证！', {icon: 2, time: 2000});
                        return;
                    }
                    var id = expenseList.id;
                    var items = {"type":'4',"id": id};
                    items = JSON.stringify(items);
                    localStorage.setItem('invoiceId',items);
                    layer.open({
                       type: 2,
                       title: '发票凭证预览页面',
                       shadeClose: true,
                       shade: 0.2,
                        skin:'pzAlert',
                       maxmin: true, //开启最大化最小化按钮
                       area: ['1200px', '96%'],
                       content: ['{{ url('book/voucher/addKeep') }}', 'yes']
                    });
                },

                /*----单条费用总额-----*/
                total_fee: function (index) {
                    var total_money = 0;
                    for (var i in this.allTables[index]['expenseTable']) {
                        total_money += Number(this.allTables[index]['expenseTable'][i]['money']);
                    }
                    return total_money.toFixed(2);
                },

                /*----检查是否有未保存的费用项-----*/
                checkUnsave: function () {

                    for (var i in this.allTables) {
                        if (this.allTables[i]['id'] == undefined || this.allTables[i]['editor'] == false) {
                            return true;
                        }
                    }
                    return false;
                },

                /*------获取当前日期---------*/
                getDateCur: function (item) {
                    item.fyrq = curData
                },

                /*------最上面的删除按钮删除选中项---------*/
                deleteSelected: function () {
                    var _this = this;
                    var selectCheck = [];
                    var params = {'_token': '{{ csrf_token()  }}', 'ids': _this.selected};
                    if (_this.selected.length <= 0) {
                        layer.open({
                            type: 1,
                            title: '信息',
                            skin: 'bank',
                            shadeClose: true,
                            shade: false,
                            maxmin: false, //开启最大化最小化按钮
                            area: ['310px', '140px'],
                            content: '<div class="tips">请选择需要删除的选项</div>',
                            btn: ['确定'],
                            yes: function (index, layero) {
                                layer.close(index)
                            }
                        });
                        return
                    }
                    layer.confirm('确定删除选项吗？', {icon: 3, title: '提示'}, function () {
                        _this.$http.post('{{ url('book/cost/deleteAll') }}', params).then(function (response) {
                            response = response.body;
                            if (response.status == '1') {
                                for (var j = 0; j < _this.selected.length; j++) {
                                    for (var i = 0; i < _this.allTables.length; i++) {
                                        if (_this.selected[j] == _this.allTables[i].id) {
                                            _this.allTables.splice(i, 1)
                                        }
                                    }
                                }
                                layer.msg(response.info, {icon: 1, time: 1500});
                            } else {
                                layer.msg(response.info, {icon: 2, time: 2000});
                            }
                        })
                    })

                },

                /*------删除选中项---------*/
                query_select: function () {

                    if (this.q_key == '' || this.q_val == '') {
                        //layer.msg('请选择搜索项', {icon: 2, time: 1500});
                        //return false;
                    }

                    //console.log(this.q_key);
                    //console.log(this.q_val);

                    $('#query').submit();

                },

                /*------导入excel弹出框---------*/
                import_excel: function () {
                    layer.open({
                        type: 2,
                        title: '导入费用明细',
                        shadeClose: true,
                        shade: false,
                        maxmin: true, //开启最大化最小化按钮
                        area: ['893px', '600px'],
                        content: '{{  url('book/cost/import_excel')  }}',
                    });
                },

                /*---------导入弹框-------*/
                importFiles: function () {

                    this.fileName = '';

                    var _this = this;
                    layer.open({
                        type: 1,
                        title: '导入费用',
                        skin: 'components', //样式类名
                        /*closeBtn: 0, //不显示关闭按钮*/
                        shadeClose: true, //开启遮罩关闭
                        content: $('#importAlert'),
                        area: ['360px', '180px'],
                        btn: ['确定', '取消'],
                        yes: function () {
                            _this.upload();
                        },
                    });
                },

                /*---------触发文件域点击-------*/
                upfile: function () {

                    $('input[name=file]').click();
                },

                showFileName: function () {
                    var files = document.querySelector("#excel_file");
                    var file = files.files[0];
                    this.fileName = file['name'];
                },

                /*--------- 上传文件  -------*/
                upload: function (event) {

                    var form = new FormData();
                    var files = document.querySelector("#excel_file");
                    var file = files.files[0];

                    form.append("file", file);//此处的name在后端会用到
                    form.append("_token", '{{csrf_token()}}');//此处的name在后端会用到

                    var _this = this;

                    _this.$http.post('{{url('book/cost/importExcel')}}', form).then(function (response) {
                        //console.log(response);
                        if (response.body.status == 1) {
                            layer.msg('操作成功', {icon: 1, time: 1000});
                            //location.reload();
                        } else {
                            layer.msg(response.body.info, {icon: 2, time: 2000});
                        }
                    });
                }
            },
            watch: {
                "selected": function () {
                    var arr = [];
                    for(var i in this.allTables){
                        if(this.allTables[i].voucher_num != ''){
                            arr.push(this.allTables[i].voucher_num)
                        }
                    }
                    if (this.selected.length == (this.allTables.length - arr.length)) {
                        this.checked = true
                    } else {
                        this.checked = false
                    }
                }
            }
        })
    </script>
@endsection