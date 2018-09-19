@extends('book.layout.base')
<!--公用-->
@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/receipts.css">
@endsection

@section('content')
<div class="receipts" v-cloak>
    <div class="formWrapper" style="z-index: 99">
        <form class="layui-form receiptsMenu">
            <div class="receiptsMenuLeft">
                <div>
                    <select v-model="Status" lay-filter="invoiceStatus">
                        <option v-for="s in Status" :value="s.key">@{{ s.value }}</option>
                    </select>
                </div>
                {{--<div>--}}
                {{--<select name="date">--}}
                {{--<option value="">全部</option>--}}
                {{--<option value="0">数据不全</option>--}}
                {{--<option value="1">数据完整</option>--}}
                {{--</select>--}}
                {{--</div>--}}
            </div>
            <div class="receiptsMenuRight">
                <div class="cashTop">
                    <span>余额:</span>
                    <i>111</i>
                    <span>元</span>
                </div>
                <div class="receipts-invoice">
                    <a class="add" href="javascript:;" @click="addExpense">新增</a>
                </div>
            </div>
        </form>
    </div>
    <div class="receiptsTable">
        <div class="receipts-thead tableBorderHead fixTableHeader">
            <table border="0">
                <thead>
                <tr>
                    <th class="width12">日期</th>
                    <th class="width50" colspan="2">业务类型</th>
                    <th class="width10">金额</th>
                    <th class="width10">凭证号</th>
                    <th class="width18">操作</th>
                </tr>
                </thead>
            </table>
        </div>
        <div>
            <div class="receiptsEach" ref="receiptsEach">
                <div class="receipts-tableCenter expense-tableCenter tableBorder tableScroll">
                    <table border="0" id="cashTable">
                        <tbody>
                        <tr v-for="(item,index) in expenseTable" :key="item">
                            <td class="width12 dateCur">
                                <span v-show="item.top" class="curData" ref="dataG">@{{item.fund_date}}</span>
                                <div class="expense-date" v-show="item.select">
                                    <input type="text" class="date dataCur" readonly
                                           onclick="WdatePicker({onpicking:onpick})" :value="item.fund_date" @blur="getDateCur(item)">
                                    <span class="icon iconfont dataEle">&#xe61f;</span>
                                </div>
                            </td>
                            <td class="width25">
                                <span v-show="item.top">@{{item.ywlx}}</span>
                                <div class="expense-menu curList" v-show="item.select">
                                    <div class="expense-select">
                                        <div @click="item.cash =! item.cash">
                                            <input type="text" class="expenseSelectText" :value="item.ywlx">
                                            <span class="textIcon">
                                                <i class="icon iconfont">&#xe620;</i>
                                            </span>
                                        </div>
                                    </div>
                                    <ul class="expenseSelect_ul" v-show="item.cash">
                                        <li v-for="cash in cashOption" @click="getCashList(cash,item)">
                                            <em>@{{cash.name}}</em>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="width25"> <!-- 单位 -->
                                <span v-show="item.top">@{{item.dw_name}}</span>
                                <div class="expense-menu curList" v-show="item.select">
                                    <div class="expense-select">
                                        <div @click="item.dw =! item.dw">
                                        <input type="text" class="expenseSelectText" :value="item.dw_name">
                                        <span class="textIcon">
                                            <i class="icon iconfont">&#xe620;</i>
                                        </span>
                                    </div>
                                    <ul class="expenseSelect_ul" v-show="item.dw">
                                        <li v-for="dw in dwList" @click="getDwList(dw,item)">
                                        <em>@{{dw.full_name}}</em>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="width10">
                                <span v-show="item.top">@{{item.money}}</span>
                                <input type="text" :value="item.money" v-show="item.select" v-model="item.money">
                            </td>
                            <td class="width10">
                                <span @click="addInvoice(item)" class="invoiceCode">@{{item.voucher ? "记-"+item.voucher.voucher_num : ''}}</span>
                            </td>
                            <td class="width18">
                                <i class="icon iconfont addMarked" @click="show_add_voucher(item)">&#xe602;</i>
                                <i class="icon iconfont addEditor" @click="editorKeep(index,item)" v-show="item.keep">&#xe60b;</i>
                                <i class="icon iconfont addEditor" @click="editorLine(index,item)" v-show="item.editor">&#xe606;</i>
                                <i class="icon iconfont del" @click="editorDel(index,item)">&#xe605;</i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div v-if="expenseTable.length">
        @if( !empty($list) )
            {{ $list->appends(request()->toArray())->links() }}
        @endif
    </div>

</div>

@endsection

@section('script')
    @parent
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
    var curData = '';
    function onpick(dp) {
        curData = dp.cal.getNewDateStr();
    }
    new Vue({
        "el": ".receipts",
        data: {
            /*模拟数据*/
            dw: false,
            dwDate: '',
            cashOption: [],
            dwList:[],
            Status: [
                {
                    key: "",
                    value: "请选择",
                },
                {
                    key: "voucher_id",
                    value: "凭证状态",
                },
            ],
            expenseTable: [],
        },
        created: function () {
            _this = this;
            layui.use(['form', 'layer'], function () {
                var form = layui.form;
                form.on('select(invoiceStatus)', function (data) {
                    var status = data.value;
                    data = {'channel_type':{{\App\Entity\Fund::CASH}}, 'status': status};
                    _this.$http.get('{{route('fund.index')}}', {params: data}).then(function (response) {
                        _this.expenseTable = response.body.data
                    })
                });
            });
            this.mapClass = ['deductible', 'noDeductible'];
            this.dateMap = ['可抵用', '不可抵用']
            this.cashList();
            this.getywlxList();
        },
        mounted() {
            this.clickBlank()
        },
        methods: {
            /*------获取当前日期---------*/
            getDateCur: function (item) {
                //console.log(curData)
                item.fund_date = curData
            },
            /*=-------点击生成的凭证---记—12------*/
            addInvoice:function(item) {
                var items = item.id;
                //console.log(items);
                localStorage.setItem('invoiceId',items);
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
            show_add_voucher: function (item) {
                var id = item['id'];
                var voucher_id = item['voucher_id'];
                var voucher_num = item.voucher;
                // var voucher_id = this.invoice_list;
                //console.log(id);
                if (voucher_num != null) {
                    layer.msg('该发票已生成记账凭证', {icon: 2, time: 1000});
                    return;
                }
                var items = {"type":'6',"id": id};
                items = JSON.stringify(items);
                localStorage.setItem('invoiceId',items);
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
            //点击空白处相应div隐藏
            clickBlank:function(){
                var _this = this;
                $(document).click(function(event){
                    var _con = $('.curList');  // 设置目标区域
                    if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
                        for(var i in _this.expenseTable){
                            _this.expenseTable[i].cash = false;
                            _this.expenseTable[i].dw = false;
                        }
                    }
                });
            },
            /*----获取当前时间-----*/
            getCurrentDate: function () {
                var now_date = new Date();
                return now_date.getFullYear() + '-' + (now_date.getMonth() + 1) + '-' + now_date.getDate();
            },
            //跳转到本页 url取参
            getQueryString: function (name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
                var r = window.location.search.substr(1).match(reg);
                if (r != null) return unescape(r[2]); return null;
            },
            //跳转打开新增
            addByInvoice:function () {
                var invoice_id = this.getQueryString('invoice_id');
                data = {'invoice_id':invoice_id, 'channel_type':"{{ \App\Entity\Fund::CASH }}"};
                var _this = this;
                if (!this.checkEmpty(invoice_id)){
                    _this.$http.get('{{ route('fund.convert') }}', {params: data}).then(function (response) {
                        var newcash = {
                            id: '',
                            dw:false,
                            cash: false,
                            top: true,
                            select: false,
                            editor: true,
                            keep: false,
                            invoice_id: invoice_id,
                        };
                        for (var i in response.body.data) {
                            newcash[i] = response.body.data[i];
                        }
                        var _newcash = newcash;
                        var ywlxNew = newcash.ywlx;
                        var dwnameNew = newcash.dw_name
                        for(var i in _this.cashOption){
                            if(_this.cashOption[i].name == ywlxNew){
                                _newcash.ywlx_num = _this.cashOption[i].number;
                                console.log(_this.cashOption[i])
                                _newcash.fund_type = _this.cashOption[i].JDFX;
                                for (var j in _this.cashOption.child){
                                    if (_this.cashOption.child[j].full_name == dwnameNew) {
                                        _newcash.dw_id = _this.cashOption.child[j].id;
                                        _newcash.dw_num = _this.cashOption.child[j].number;
                                    }
                                }
                            }
                        }
                        console.log(newcash)
                        _this.expenseTable.splice(_this.expenseTable.length, 0, newcash)
                        /*console.log(this.expenseTable[this.expenseTable.length-1])*/
                        /*----新增对象的所有数据-----*/
                        _this.expenseTable[_this.expenseTable.length - 1].editor = false
                        _this.expenseTable[_this.expenseTable.length - 1].keep = true
                        _this.expenseTable[_this.expenseTable.length - 1].top = false
                        _this.expenseTable[_this.expenseTable.length - 1].select = true
                    })
                }
            },
            checkEmpty: function (val) {
                return val == undefined || val == '' || val == null;
            },
            getywlxList: function () {
                this.$http.get('{{route('fund.ywlxList', ['channel_type'=>\App\Entity\Fund::CASH])}}').then(function (response) {
                    this.cashOption = response.body.data;
                })
            },
            cashList: function () {
                this.$http.get('{{route('fund.index')}}?{!! request()->getQueryString() !!}').then(function (response) {
                    this.expenseTable = response.body.data
                }).then(function () {
                    this.addByInvoice();
                });
            },
            getCashList: function (cash, item) {
                item.ywlx_num = cash.number;
                item.ywlx = cash.name;
                item.cash = false;
                item.dw_name = '';
                item.dw_id = '';
                item.fund_type = cash.JDFX;
                this.dwList = cash.child;
            },
            /*------单位获取------*/
            getDwList: function (dw, item) {
                item.dw_name = dw.full_name;
                item.dw_id = dw.id;
                item.dw_num = dw.number;
                item.dw = false;
            },
            /*---行内的编辑----*/
            editorLine: function (index, item) {
                id:this.expenseTable[index].id;
                item.editor = false;
                item.keep = true;
                item.top = false;
                item.select = true;
            },
            /*---行内删除---*/
            editorDel: function (index, item) {
                var _this = this;
                layer.confirm(
                        '确定删除吗？', {icon: 3, title: '提示'},
                        function () {
                            _this.$http.post('{{ route('fund.del') }}', {
                                _token: "{{csrf_token()}}",
                                id: item.id
                            }).then(function (response) {
                                if (response.body.status == 1) {
                                    layer.msg(response.body.info, {icon: 1, time: 1000});
                                    this.expenseTable.splice(index, 1)
                                } else {
                                    layer.msg(response.body.info, {icon: 2, time: 1000});
                                    window.location.reload();
                                }
                            })
                        }
                );
            },
            /*---行内保存----*/
            editorKeep: function (index, item) {
                curData = item.fund_date;
                data = {
                    _token: "{{csrf_token()}}",
                    id: item.id,
                    company_id: item.company_id,
                    fund_date: curData,
                    fund_type:item.fund_type,
                    ywlx: item.ywlx,
                    ywlx_num:item.ywlx_num,
                    dw_id:item.dw_id,
                    dw_name:item.dw_name,
                    money: item.money,
                    channel_type: "{{\App\Entity\Fund::CASH}}",
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod()  }}'
                };
                this.$http.post('{{route('fund.store')}}', data).then(function (response) {
                    if (response.body.status == 1) {
                        layer.msg(response.body.info, {icon: 1, time: 1000});
                        data = {'channel_type':{{\App\Entity\Fund::CASH}}};
                        this.$http.get('{{route('fund.index')}}', {params: data}).then(function (response) {
                            this.expenseTable = response.body.data
                        })
                    } else {
                        layer.msg(response.body.info, {icon: 2, time: 1000});
                        //window.location.reload();
                    }
                });
                item.editor = true;
                item.keep = false;
                item.top = true;
                item.select = false;
                window.location.reload();
            },
            /*----新增-----*/
            addExpense: function () {
                for(var i in this.expenseTable){
                    if(this.expenseTable[i].keep){
                        layer.msg('您有未保存的编辑！', {icon: 2, time: 2000});
                        return false;
                    }
                }
                var cur = this.expenseTable.length;
                this.expenseTable.splice(this.expenseTable.length, 0, {
                            id: '',
                            fund_date: this.getCurrentDate(),
                            ywlx: '收销售款',
                            money: '0.00',
//                            voucher_id: '',
                            dw:false,
                            cash: false,
                            top: true,
                            select: false,
                            editor: true,
                            keep: false,
                        }
                )
                /*console.log(this.expenseTable[this.expenseTable.length-1])*/
                /*----新增对象的所有数据-----*/
                this.expenseTable[this.expenseTable.length - 1].editor = false;
                this.expenseTable[this.expenseTable.length - 1].keep = true;
                this.expenseTable[this.expenseTable.length - 1].top = false;
                this.expenseTable[this.expenseTable.length - 1].select = true;
            }
        },
    })
</script>
@endsection