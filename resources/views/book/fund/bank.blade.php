@extends('book.layout.base')
<!--公用-->
@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/receipts.css?v=20180090401">
@endsection

@section('content')
    <div class="receipts" v-cloak>
        <div class="formWrapper" style="z-index: 99">
            <form class="layui-form receiptsMenu" id="query_form">
                <input type="hidden" name="channel_type" v-model="channel_type">
                <input type="text" style="display: none" name="bank_name" v-model="getBlank">
                <input type="text" style="display: none" name="bank_id" v-model="getBlank_id">
                <div class="receiptsMenuLeft">
                    <div>
                        <select name="voucher_status" v-model="voucher_status" lay-filter="voucher_status">
                            <option value="">凭证状态</option>
                            <option value="1">已生成凭证</option>
                            <option value="2">未生成凭证</option>
                        </select>
                    </div>
                    <div>
                        <select name="fund_type" v-model="fund_type" lay-filter="fund_type">
                            <option value="">收支类型</option>
                            <option value="1">收入</option>
                            <option value="2">支出</option>
                        </select>
                    </div>
                    {{--<div>--}}
                    {{--<select name="city">--}}
                    {{--<option value="" v-for='q_key_item in q_key' :value='q_key_item.value'>@{{q_key_item.label}}</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}
                    {{--<div>--}}
                    {{--<select name="date">--}}
                    {{--<option value="" v-for='q_val_item in q_val' :value='q_val_item.value'>@{{q_val_item.label}}</option>--}}
                    {{--</select>--}}
                    {{--</div>--}}
                    <div>
                        <div class="selectBlank">
                            <div class="blankHead curList" @click="showBankList">
                            <span class="bankTitleTop">@{{getBlank}}</span>
                            <i class="icon iconfont icon--xialajiantou"></i>
                        </div>
                        <ul class="showBankTitle" v-show="blankList">
                            <li v-for="item in blankOption" :key="item.index" @click="getBlanks(item)">
                            @{{item.name}}
                            </li>
                        </ul>
                    </div>
                </div>
                <div>
                    <p class="tipText">温馨提示：若需要自定义凭证摘要，请填写备注栏!</p>
                </div>
        </div>
        <div class="receiptsMenuRight">
            <div class="receipts-invoice">
                <a class="add" href="javascript:;" @click="addExpense">新增</a>
            </div>
            <div class="receipts-invoice" style="display: none">
                <a href="javascript:;">导入</a>
            </div>
            <div class="receipts-del">
                <a href="javascript:;" @click="delBtn">删除</a>
            </div>
        </div>
        </form>
        <div class="blankTopMoney">
            <div>
                <span>本期收入金额合计：</span>
                <i>@{{ periodIncome }}</i>
                <span class="price">元</span>
            </div>
            <div>
                <span>本期支出金额合计：</span>
                <i>@{{ periodOut }}</i>
                <span class="price">元</span>
            </div>
            <div>
                <span>余额：</span>
                <i>@{{ totalAccount }}</i>
                <span class="price">元</span>
            </div>
        </div>
    </div>
    <div class="receiptsTable">
        <div class="receipts-thead tableBorderHead">
            <table border="0">
                <thead>
                <tr>
                    <th class="width3 w39"><input type="checkbox" v-model="checked" @click="allSelect"></th>
                    <th class="width10">日期</th>
                    <th class="width35">业务类型</th>
                    <th class="width35">往来单位</th>
                    <th class="width10">金额</th>
                    <th class="width28">备注</th>
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
                                <input type="checkbox" :value="expenseList.id" v-model="selected" :disabled="expenseList.disableCkeck">
                            </td>
                            <td colspan="7">
                                <div class="receipts-tableTotal">
                                    <div class="receipts-tableInvoice">
                                        <div>
                                            <i class="iconfont manual" v-if="expenseList.source_type==1">&#xe604;</i>
                                            <i class="iconfont manual" v-if="expenseList.source_type==0">&#xe608;</i>
                                            <span :class="mapClass[expenseList.status]" class="iconfont manual"></span>
                                        </div>
                                        <div>
                                            @{{expenseList.bank_name}}
                                        </div>
                                        <div>
                                            <span>对方账户名称:</span>
                                            <i>@{{expenseList.name}}</i>
                                        </div>
                                        <div class="receipts-expense">
                                            <span>@{{ getFundType(expenseList.fund_type) }}金额（本位币）:</span>
                                            <i>@{{expenseList.money}}</i>
                                        </div>
                                        <div class="receipts-invoiceText">
                                            <i>凭证号:</i>
                                            <span class="text" @click="addInvoice(expenseList)">@{{expenseList.voucher_num}}</span>
                                        </div>
                                    </div>
                                    <div class="receipts-tableInvoiceRight receipts-editor editorShow">
                                        <i class="icon iconfont tableEditor" v-if="expenseList.source_type==1" @click="show_add_voucher(expenseList)">&#xe602;</i>
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
                        <tr v-for="(item,index) in expenseList.fund_items" :key="item">
                            <td class="width3"></td>
                            <td class="width10 dateCur">
                                <span v-show="item.top" class="curData">@{{item.funditem_date}}</span>
                                <div class="expense-date" v-show="item.select">
                                    <input :value="item.funditem_date" type="text" class="date dataCur" readonly @blur="getDateCur(item)" onclick="WdatePicker({onpicking:onpick})">
                                    <span class="icon iconfont dataEle">&#xe61f;</span>
                                </div>
                            </td>
                            <td class="width35"> <!-- 业务/科目类型 -->
                                <span v-show="item.top">@{{item.ywlx}}</span>
                                <div class="expense-menu curList" v-show="item.select">
                                    <div class="expense-select">
                                        <div @click="showYwList(item)">
                                        <input type="text" class="expenseSelectText" v-model="item.ywlx">
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
                <td class="width35"> <!-- 往来单位 -->
                    <span v-show="item.top">@{{item.dw_name}}</span>
                    <div class="expense-menu curList" v-show="item.select_dw">
                        <div class="expense-select">
                            <div @click="item.expenseUnit = !item.expenseUnit">
                            <input type="text" :disabled="item.dwDisabled" class="expenseSelectText" v-model="item.dw_name">
                            <span class="textIcon">
                                <i class="icon iconfont">&#xe620;</i>
                            </span>
                        </div>
                        <ul class="expenseSelect_ul" v-show="item.expenseUnit">
                            <li v-for="dw in dwList" @click="getDwList(dw,item)">
                            <em>@{{dw.value}}</em>
                            </li>
                        </ul>
                    </div>
            </div>
            </td>
            <td class="width10">
                <span v-show="item.top">@{{item.money}}</span>
                <input type="text" :value="item.money" v-show="item.select" v-model="item.money">
            </td>
            <td class="width28">
                <span v-show="item.top">@{{item.remark}}</span>
                <input type="text" :value="item.remarks" v-model="item.remark" v-show="item.select">
            </td>
            <td class="width14">
                <i class="icon iconfont addEditor" @click="editorAdd(index,item,expenseList)">&#xe60c;</i>
                {{--<i class="icon iconfont addEditor" @click="editorLine(item,expenseList)">&#xe600;</i>--}}
                <i class="icon iconfont del" @click="editorDel(index,item,expenseList)">&#xe605;</i>
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
    </div>

@endsection
<!--公用-->
@section('script')
    @parent
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script>
        var upDataVal = '';

        var curData = '2018-6-25';

        function onpick(dp) {
            curData = dp.cal.getNewDateStr();
        }

        new Vue({
                    "el": ".receipts",
                    data: {
                        disabled: false,
                        selected: [],
                        checked: false,
                        dwList: [],
                        editStatus: false,
                        getDate: '更多',
                        receiptsList: false,
                        blankList: false,
                        getBlank: '{{ request('bank_name')  }}',
                        getBlank_id: '{{ request('bank_id')  }}',
                        blankOption: [],
                        q_key: [{label: '全部', value: ''}, {label: '凭证状态', value: 'pzzt'}, {label: '收支状态', value: 'szzt'}],
                        q_val: [{label: '全部', value: ''}],
                        options: [
                            {
                                value: '1',
                                label: '更多'
                            },
                            // {
                            //     value: '2',
                            //     label: '导入(F12)'
                            // },
                            // {
                            //     value: '3',
                            //     label: '采集'
                            // },
                            // {
                            //     value: '4',
                            //     label: '本地提取'
                            // },
                            // {
                            //     value: '5',
                            //     label: '批量补充'
                            // },
                            // {
                            //     value: '6',
                            //     label: '批量结算'
                            // },
                            // {
                            //     value: '7',
                            //     label: '外币补充'
                            // },
                            // {
                            //     value: '8',
                            //     label: '排序'
                            // },
                            // {
                            //     value: '9',
                            //     label: '进项图片管理'
                            // },
                            // {
                            //     value: '10',
                            //     label: '习惯设置'
                            // },
                            {
                                value: '11',
                                label: '导出Excel'
                            }
                        ],
                        status: 0,
                        expenseOption: [],
                        expenseUnitOption: [
                            {
                                value: '苏州财税狮网络科技有限公司',
                                label: '客户'
                            },
                            {
                                value: '苏州财税狮网络科技有限公司',
                                label: '客户'
                            },
                            {
                                value: '安徽省监狱',
                                label: '供应商'
                            },
                        ],
                        allTables: [],
                        upData: [
                            {
                                value: '0',
                                label: '全部'
                            },
                            {
                                value: '1',
                                label: '数据不全'
                            },
                            {
                                value: '2',
                                label: '数据完整'
                            },
                        ],
                        fundTypeMap: {'0': '收入', '1': '支出'},
                        fund_type: '{{ request('fund_type')  }}',
                        voucher_status: '{{ request('voucher_status')  }}',
                        channel_type: '{{ request('channel_type') }}',
                        periodIncome: 0,
                        periodOut: 0,
                        totalAccount: 0,
                    },
                    created: function () {
                        this.mapClass = ['icon-icon8', 'icon-icon9'];
                        this.getblankList();
                        this.bankFundCount();
                        this.getAllTables();
                        this.getBlank = '{{request('bank_name')}}';

                        this.expenseOption = JSON.parse('{!! json_encode(\App\Entity\Fund::ywOptions()) !!}');
                        this.dwList = JSON.parse('{!! json_encode(\App\Entity\Invoice::dwList()) !!}');

                        layui.use(['form'], function () {
                            var form = layui.form;
                            form.on('select(upData)', function (data) {
                                //console.log(data.value);
                                upDataVal = data.value
                            });
                        });

                    },
                    mounted() {
                        this.clickBlank()
                    },
                    methods: {
                        /*-----------------银行下拉框选择--------*/
                        showBankList:function(){
                            if(this.blankOption.length <=0 ){
                                layer.open({
                                    type: 1,
                                    skin: 'bankAlert', //样式类名
                                    anim: 2,
                                    shadeClose: true, //开启遮罩关闭
                                    content: '<div class="bankTips">银行账户空,请在业务数据下的银行账户添加</div>',
                                    btn: ['确定','取消']
                                });
                                return;
                            }
                            this.blankList = !this.blankList
                        },
                        /*---银行资金统计----*/
                        bankFundCount: function () {
                            this.$http.get('{{route('fund.bankFundCount')}}').then(function (response) {
                                if (response.body.status == 1) {
                                    this.periodIncome = response.body.data.in;
                                    this.periodOut = response.body.data.out;
                                    this.totalAccount = response.body.data.total;
                                }
                            })
                        },
                    //点击空白处相应div隐藏
                    clickBlank: function () {
                        var _this = this;
                        $(document).click(function (event) {
                            var _con = $('.curList');  // 设置目标区域
                            if (!_con.is(event.target) && _con.has(event.target).length === 0) { // Mark 1
                                //银行下拉框
                                _this.blankList = false;
                                $.each(_this.allTables, function (i, arr) {
                                    arr.fund_items[0].expense = false;
                                    arr.fund_items[0].expenseUnit = false;
                                });
                            }
                        });
                    },
                    /*=-------点击生成的凭证---记—12------*/
                    addInvoice: function (expenseList) {
                        var items = expenseList['voucher_id'];
                        //console.log(items);
                        localStorage.setItem('invoiceId', items);
                        layer.open({
                            type: 2,
                            title: '凭证信息',
                            shadeClose: true,
                            shade: 0.2,
                            maxmin: true, //开启最大化最小化按钮
                            area: ['1200px', '96%'],
                            content: ['{{ url('book/voucher/add') }}', 'yes']
                        });
                    },
                    /*---点击记账,发票凭证预览页面---*/
                    show_add_voucher: function (expenseList) {
                        var id = expenseList['id'];
                        var voucher_id = expenseList['voucher_id'];
                        var voucher_num = expenseList['voucher_num'];
                        // var voucher_id = this.invoice_list;
                        //console.log(id);
                        if (voucher_num !== '') {
                            layer.msg('该发票已生成记账凭证', {icon: 2, time: 1000});
                            return;
                        }
                        var items = {"type": '7', "id": id};
                        items = JSON.stringify(items);
                        localStorage.setItem('invoiceId', items);
                        //console.log(items);
                        layer.open({
                            type: 2,
                            title: '发票凭证预览页面',
                            shadeClose: true,
                            shade: 0.2,
                            maxmin: true, //开启最大化最小化按钮
                            area: ['1200px', '96%'],
                            content: ['{{ url('book/voucher/addKeep') }}', 'yes'],
                        });
                    },
                    /*------获取当前日期---------*/
                    getDateCur: function (item) {
                        item.funditem_date = curData
                    },

                    //获取url参数
                    getQueryParam: function (name) {
                        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
                        var r = window.location.search.substr(1).match(reg);
                        if (r != null) return r[2];
                        return '';
                    },

                    //获取发票转化为银行资金信息
                    addByInvoice: function () {
                        var invoice_id = this.getQueryParam('invoice_id');
                        var _this = this;
                        if (!this.checkEmpty(invoice_id)) {
                            _this.$http.get('{{ url('book/fund/convert') }}?invoice_id=' + invoice_id).then(function (response) {
                                //console.log(response.body);
                                if (response.body.status == 1) {
                                    this.editStatus = true;
                                    var expenseList = {
                                        expense: false,
                                        top: false,
                                        select: true,
                                        select_dw: true,
                                        dw: false,
                                        editor: false,
                                        keep: true,
                                        invoice_id: invoice_id,
                                    };
                                    for (var i in response.body.data) {
                                        expenseList[i] = response.body.data[i];
                                    }

                                    expenseList['fund_items'] = [];
                                    var fund_items = response.body.data.fund_items;

                                    expenseList['fund_items'].push(response.body.data.fund_items);
                                    //console.log(expenseList);

                                    expenseList['fund_items'][0]['expense'] = false;
                                    expenseList['fund_items'][0]['top'] = false;
                                    expenseList['fund_items'][0]['select'] = true;
                                    expenseList['fund_items'][0]['select_dw'] = true;
                                    expenseList['fund_items'][0]['dw'] = false;
                                    expenseList['fund_items'][0]['editor'] = true;
                                    expenseList['fund_items'][0]['keep'] = false;


                                    this.allTables.push(expenseList);
                                }
                            });
                        }
                    },

                    //点击选择单位
                    getDwList: function (dw, item) {
                        item.dw_id = dw.id;
                        item.dw_name = dw.value;
                        item.expenseUnit = false;

                    },
                    showDw: function (item) {
                        item.dw = !item.dw;
                    },
                    showYwList: function (item) {
                        item.expense = !item.expense;
                        item.dw = false;
                    },

                    //获取收支状态
                    getFundType: function (fund_type) {
                        return this.fundTypeMap[fund_type];
                    },

                    //银行选择下拉框
                    getblankList: function () {
                        this.$http.get('{{route('bankaccount.index')}}').then(function (response) {
                            this.blankOption = response.body
                        })
                    },
                    //选中银行
                    getBlanks: function (item) {
                        this.blankList = false;
                        this.getBlank = item.name;
                        this.getBlank_id = item.id;
                        data = {bank_name: this.getBlank};

                        $('input[name=bank_name]').val(item.name);
                        $('input[name=bank_id]').val(item.id);
                        //console.log($('#query_form').serialize());

                        var invoice_id = this.getQueryParam('invoice_id');
                        if (this.checkEmpty(invoice_id)) {
                            $('#query_form').submit();
                        }

                        //location.href = '{{ url('book/fund')  }}?{!! request()->getQueryString() !!}&bank_name=' + this.getBlank;
                        return false;

                        this.$http.get('{{route('fund.banklist')}}?{!! request()->getQueryString() !!}', {params: data}).then(function (response) {
                            //console.log(response.body.data);
                            this.allTables = response.body.data;
                            for (var i in this.allTables) {
                                for (var j in this.allTables[i]['fund_items']) {
                                    this.allTables[i]['fund_items'][j]['select_dw'] = false;
                                }
                            }
                        }).then(function () {
                            this.addByInvoice();
                        });
                    },
                    //数据列表
                    getAllTables: function () {
                        data = {bank_name: this.getBlank};

                        //this.allTables = JSON.parse(JSON.stringify("{{ json_encode($list->toArray()['data']) }}"));
                        this.allTables = {!! json_encode($list->toArray()['data']) !!};
                        //console.log(this.allTables)

                        for (var i in this.allTables) {
                            if (this.allTables[i].voucher_num) {
                                this.allTables[i].disableCkeck = true;
                            }
                            for (var j in this.allTables[i]['fund_items']) {
                                this.allTables[i]['fund_items'][j]['select_dw'] = false;
                            }
                        }
                        this.addByInvoice();

                        {{--this.$http.get('{{route('fund.banklist')}}?{!! request()->getQueryString() !!}', {params: data}).then(function (response) {--}}
                        {{--//console.log(response.body.data);--}}
                        {{--this.allTables = response.body.data;--}}
                        {{--for (var i in this.allTables) {--}}
                        {{--for (var j in this.allTables[i]['fund_items']) {--}}
                        {{--this.allTables[i]['fund_items'][j]['select_dw'] = false;--}}
                        {{--}--}}
                        {{--}--}}
                        {{--}).then(function () {--}}
                        {{--this.addByInvoice();--}}
                        {{--});--}}
                    },
                    getNewAdds: function (value) {
                        this.receiptsList = false;
                        this.getDate = value
                    },
                    getExpenseList: function (expense, item) {
                        //console.log(expense);
                        item.ywlx = expense.label;
                        item.ywlx_id = expense.value;
                        item.fund_type = expense.jdfx;
                        item.dwDisabled = expense.type != 1;
                        item.dw_id = '';
                        item.dw_name = '';
                        item.expense = false;
                    },
                    /*---编辑按钮---*/
                    expenseEditor: function (index, expenseList) {
                        for (var i in this.allTables) {
                            if (this.allTables[i].keep) {
                                layer.msg('您有未保存的编辑！', {icon: 2, time: 1000});
                                return false;
                            }
                        }

                        expenseList.editor = false;
                        expenseList.keep = true;
                        for (var i in expenseList.fund_items) {
                            expenseList.fund_items[i].top = false;
                            expenseList.fund_items[i].select = true;
                            expenseList.fund_items[i].expense = false;

                            expenseList.fund_items[i].select_dw = true;
                            expenseList.fund_items[i].dw = false;
                        }
                    },
                    /*--保存按钮---*/
                    expenseKeep: function (index, expenseList) {

                        if (this.checkEmpty(this.getBlank_id)) {
                            layer.msg('请选择银行账户', {icon: 2, time: 2000});
                            return false;
                        }

                        data = {
                            id: expenseList['id'],
                            fund_date: this.getCurrentDate(),
                            bank_name: this.getBlank,
                            bank_id: this.getBlank_id,
                            invoice_id: expenseList['invoice_id'],
                            company_id: '{{ \App\Entity\Company::sessionCompany()->id  }}',
                            fiscal_period: '{{ \App\Entity\Period::currentPeriod()  }}',
                        };
                        //console.log(data);
                        //return false;
                        var money = 0;
                        data['fund_items'] = [];
                        for (var i in expenseList['fund_items']) {
                            money += Number(expenseList['fund_items'][i]['money']);
                            data['fund_items'].push({
                                id: expenseList['fund_items'][i]['id'],
                                fund_id: expenseList['id'],
                                funditem_date: expenseList['fund_items'][i]['funditem_date'],
                                ywlx_id: expenseList['fund_items'][i]['ywlx_id'],
                                ywlx: expenseList['fund_items'][i]['ywlx'],
                                fund_type: expenseList['fund_items'][i]['fund_type'],
                                //ywlx_jdfx: expenseList['fund_items'][i]['ywlx_jdfx'],
                                money: expenseList['fund_items'][i]['money'],
                                remark: expenseList['fund_items'][i]['remark'],
                                fiscal_period: '{{ \App\Entity\Period::currentPeriod()  }}',
                                dw_id: expenseList['fund_items'][i]['dw_id'],
                                dw_name: expenseList['fund_items'][i]['dw_name'],
                            });
                        }

                        data['money'] = money;
                        data['_token'] = "{{csrf_token()}}";

                        //console.log(data);
                        //return false;

                        this.$http.post('{{route('fund.newbank')}}', data).then(function (response) {
                            if (response.body.status == 1) {
                                layer.msg(response.body.info, {icon: 1, time: 1000});
                                location.href = '{{ url('/book/fund')  }}?channel_type=1';
                                //window.location.reload()
                            } else {
                                layer.msg(response.body.info, {icon: 2, time: 1000});
                            }
                        });
                        expenseList.editor = true;
                        expenseList.keep = false;
                        for (var i in expenseList.fund_items) {
                            expenseList.fund_items[i].top = true;
                            expenseList.fund_items[i].select = false;
                            expenseList.fund_items[i].select_dw = false;
                            /*expenseList.fund_items[i].data = curData*/
                        }
                    },
                    /*---删除当前的整个table---*/
                    delTotal: function (index, item) {
                        var _this = this;
                        layer.confirm(
                                '确定删除吗？', {icon: 3, title: '提示'},
                                function () {
                                    _this.$http.post('{{ route('fund.delbank') }}', {_token: "{{csrf_token()}}", id: item.id}).then(function (response) {
                                        if (response.body.status == 1) {
                                            layer.msg(response.body.info, {icon: 1, time: 1000});
                                            this.allTables.splice(index, 1)
                                        } else {
                                            layer.msg(response.body.info, {icon: 2, time: 1000});
                                            //window.location.reload();
                                        }
                                    })
                                }
                        );
                    },
                    /*---行内的编辑----*/
                    editorLine: function (item, expenseList) {
                        //console.log(this.getBlank_id);
                        expenseList.editor = false;
                        expenseList.keep = true;
                        item.top = false;
                        item.select = true;
                        //console.log(expenseList.icon)
                        /*-------------------------------------------最后补充----------------------*/
                        /*for(var i in this.allTables){
                         console.log(this.allTables[i].keep)
                         }*/
                    },
                    /*---行内删除---*/
                    editorDel: function (index, item, expenseList) {
                        var _this = this;

                        if (this.checkEmpty(item.id)) {
                            expenseList.fund_items.splice(index, 1);
                            return false;
                        }

                        layer.confirm(
                                '确定删除吗？', {icon: 3, title: '提示'},
                                function () {
                                    _this.$http.post('{{ route('fund.delbankitem') }}', {_token: "{{csrf_token()}}", id: item.id}).then(function (response) {
                                        if (response.body.status == 1) {
                                            layer.msg(response.body.info, {icon: 1, time: 1000});
                                            expenseList.fund_items.splice(index, 1)
                                        } else {
                                            layer.msg(response.body.info, {icon: 2, time: 1000});
                                            window.location.reload();
                                        }
                                    })
                                }
                        );
                    },
                    /*---行内新增----*/
                    editorAdd: function (index, item, expenseList) {
                        expenseList.editor = false;
                        expenseList.keep = true;
                        expenseList.fund_items.splice((index + 1), 0, {
                            funditem_date: this.getCurrentDate(),
                            dw_id: '',
                            dw_name: '',
                            dwDisabled: true,
                            ywlx: '',
                            money: '',
                            remark: '',
                            expense: false,
                            expenseUnit: false,
                            top: false,
                            select: true,
                            select_dw: true,
                            dw: false,
                            editor: true,
                            keep: false,
                        });
                        // expenseList.fund_items[(index + 1)].top = false;
                        // expenseList.fund_items[(index + 1)].select = true;
                    },
                    /*----新增-----*/
                    addExpense: function () {
                        if (!this.getBlank) {
                            layer.msg('请维护银行账户！', {icon: 2, time: 2000});
                            return false;
                        }
                        for (var i in this.allTables) {
                            if (this.allTables[i].keep) {
                                layer.msg('您有未保存的编辑！', {icon: 2, time: 2000});
                                return false;
                            }
                        }

                        var cur = this.allTables.length;

                        this.editStatus = true;
                        /*  this.allTables.push({
                         money: '',
                         bank_name: '',
                         bank_id: '',
                         ywlx: "",
                         ywlx_id: "",
                         editor: false,
                         keep: true,
                         fund_items: [
                         {
                         funditem_date: this.getCurrentDate(),
                         dw_id: '',
                         dw_name: '',
                         dwDisabled: true,
                         ywlx: '',
                         money: '',
                         remark: '',

                         expense: false,
                         expenseUnit: false,
                         top: false,
                         select: true,
                         select_dw: true,
                         dw: false,
                         editor: true,
                         keep: false
                         }
                         ]
                         });*/
                        Vue.set(this.allTables, cur, {
                            money: '',
                            bank_name: '',
                            bank_id: '',
                            ywlx: "",
                            ywlx_id: "",
                            editor: false,
                            keep: true,
                            fund_items: [
                                {
                                    funditem_date: this.getCurrentDate(),
                                    dw_id: '',
                                    dw_name: '',
                                    dwDisabled: true,
                                    ywlx: '',
                                    money: '',
                                    remark: '',

                                    expense: false,
                                    expenseUnit: false,
                                    top: false,
                                    select: true,
                                    select_dw: true,
                                    dw: false,
                                    editor: true,
                                    keep: false
                                }
                            ]
                        })
                    },

                    /*----获取当前时间-----*/
                    getCurrentDate: function () {
                        var now_date = new Date();
                        return now_date.getFullYear() + '-' + (now_date.getMonth() + 1) + '-' + now_date.getDate();
                    },

                    /**
                     * 判断来源是否为空
                     * @param val
                     * @returns {boolean}
                     */
                    checkEmpty: function (val) {
                        return val == undefined || val == '' || val == null;
                    },
                    /*----------最上面的删除按钮----------delBtn*/
                    delBtn: function () {
                        var _this = this;
                        var selectCheck = [];
                        var params = {'_token': '{{ csrf_token()  }}', 'id': _this.selected};
                        if (_this.selected.length <= 0) {
                            layer.open({
                                type: 1,
                                title: '信息',
                                skin: 'bank',
                                shadeClose: true,
                                shade: false,
                                maxmin: false, //开启最大化最小化按钮
                                area: ['310px', '140px'],
                                content: '<div class="tips">请至少选择一个银行</div>',
                                btn: ['确定'],
                                yes: function (index, layero) {
                                    layer.close(index)
                                }
                            });
                            return
                        }
                        layer.confirm('确定删除选项吗？', {icon: 3, title: '提示'}, function () {
                            _this.$http.post('{{route('fund.delbank')}}', params).then(function (response) {
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
                    //全选
                    allSelect: function () {
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
                                if (this.allTables[i].voucher_num == '') {
                                    this.selected.push(this.allTables[i].id)
                                }
                            }
                        } else {
                            this.selected = [];
                        }
                    },
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
        });


        layui.use(['form'], function () {

            var form = layui.form;
            form.on('select(voucher_status)', function (data) {
                $('#query_form').submit();
            });
            form.on('select(fund_type)', function (data) {
                $('#query_form').submit();
            });

        });

    </script>
@endsection