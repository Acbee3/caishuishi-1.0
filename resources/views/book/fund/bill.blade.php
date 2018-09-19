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
                    <i>-129328943874,32809838734</i>
                    <span>元</span>
                </div>
                <div class="receipts-invoice">
                    <a class="add" href="javascript:;" @click="addExpense">新增</a>
                </div>
            </div>
        </form>
    </div>
    <div class="receiptsTable">
        <div class="receipts-thead tableBorderHead">
            <table border="0">
                <thead>
                <tr>
                    <th class="width44" colspan="2">业务类型</th>
                    <th class="width18">金额</th>
                    <th class="width18">凭证号</th>
                    <th class="width20">操作</th>
                </tr>
                </thead>
            </table>
        </div>
        <div>
            <div class="receiptsEach" ref="receiptsEach">
                <div class="receipts-tableCenter expense-tableCenter tableBorder">
                    <table border="0" id="cashTable">
                        <tbody>
                            <tr v-for="(item,index) in expenseTable" :key="item">
                                <td class="width22">
                                    <span v-show="item.top">@{{item.ywlx}}</span>
                                    <div class="expense-menu curList" v-show="item.select">
                                        <div class="expense-select">
                                            <div @click="item.cash =! item.cash">
                                            <input type="text" class="expenseSelectText" :value="item.ywlx">
                                            <span class="textIcon">
                                                <i class="icon iconfont">&#xe620;</i>
                                            </span>
                                        </div>
                                        <ul class="expenseSelect_ul" v-show="item.cash">
                                            <li v-for="cash in ywlxOption" @click="getCashList(cash,item)">
                                            <em>@{{cash.name}}</em>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="width22"> <!-- 单位 -->
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
                                <td class="width18">
                                    <span v-show="item.top">@{{item.money}}</span>
                                    <input type="text" :value="item.money" v-show="item.select" v-model="item.money">
                                </td>
                                <td class="width18">
                                    <span @click="addInvoice(item)" class="invoiceCode">@{{item.voucher ? "记-"+item.voucher.voucher_num : ''}}</span>
                                </td>
                                <td class="width20">
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
@if( !empty($list) )
{{ $list->appends(request()->toArray())->links() }}
@endif
</div>
@endsection

@section('script')
    @parent
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
    layui.use(['form'],function(){
        var form = layui.form;
    })
    new Vue({
        "el":".receipts",
        data: {
            dw: false,
            dwDate: '',
            dwList:[],
            ywlxOption:[],
            Status:[
                {
                    key     : "",
                    value   : "请选择",
                },
                {
                    key     : "voucher_id",
                    value   : "凭证状态",
                },
            ],
            expenseTable:[],
        },
        created:function(){
            _this = this;
            layui.use(['form', 'layer'],function(){
                var form = layui.form;
                form.on('select(invoiceStatus)', function (data) {
                    var status = data.value;
                    data = {'channel_type':{{\App\Entity\Fund::BILL}}, 'status':status};
                    _this.$http.get('{{route('fund.index')}}?{!! request()->getQueryString() !!}', {params:data}).then(function (response) {
                        _this.expenseTable = response.body.data
                    });
                });
            })
            this.mapClass = ['deductible','noDeductible']
            this.dateMap = ['可抵用','不可抵用']
            this.billList();
            this.getywlxList();
        },
        mounted() {
            this.clickBlank()
        },
        methods: {
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
                if (voucher_num != null) {
                    layer.msg('该发票已生成记账凭证', {icon: 2, time: 1000});
                    return;
                }
                var items = {"type":'5',"id": id};
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
            getywlxList:function(){
                this.$http.get('{{route('fund.ywlxList', ['channel_type'=>\App\Entity\Fund::BILL])}}').then(function (response) {
                    this.ywlxOption = response.body.data;
                })
            },
            billList:function () {
                this.$http.get('{{route('fund.index')}}?{!! request()->getQueryString() !!}').then(function (response) {
                    this.expenseTable = response.body.data;
                    //console.log(this.expenseTable)
                })
            },
            getCashList:function(cash,item){
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
                item.dw_num = dw.number;
                item.dw_id = dw.id;
                //console.log(item.dw_id)
                item.dw = false;
            },
            /*---行内的编辑----*/
            editorLine:function(index,item){
                id:this.expenseTable[index].id;
                item.editor = false;
                item.keep = true;
                item.top = false;
                item.select = true
            },
            /*---行内删除---*/
            editorDel:function(index,item){
                var _this = this;
                layer.confirm(
                        '确定删除吗？', {icon: 3, title:'提示'},
                        function () {
                            _this.$http.post('{{ route('fund.del') }}', {_token:"{{csrf_token()}}",id:item.id}).then(function (response) {
                                if (response.body.status == 1){
                                    layer.msg(response.body.info, {icon:1,time:1000});
                                    this.expenseTable.splice(index,1)
                                }else{
                                    layer.msg(response.body.info, {icon:2,time:1000});
                                    window.location.reload();
                                }
                            })
                        }
                );
            },
            /*---行内保存----*/
            editorKeep:function(index,item){
                data = {
                    _token: "{{csrf_token()}}",
                    id: item.id,
                    company_id: item.company_id,
//                    fund_date: curData,
                    fund_type:item.fund_type,
                    ywlx: item.ywlx,
                    ywlx_num:item.ywlx_num,
                    dw_id:item.dw_id,
                    dw_num:item.dw_num,
                    dw_name:item.dw_name,
                    money: item.money,
                    channel_type: "{{\App\Entity\Fund::BILL}}",
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod()  }}',
                };
                this.$http.post('{{route('fund.store')}}', data).then(function (response) {
                    if (response.body.status == 1) {
                        layer.msg(response.body.info, {icon: 1, time:1000});
                    } else {
                        layer.msg(response.body.info, {icon: 2, time:1000});
                        window.location.reload();
                    }
                });
                item.editor = true;
                item.keep = false;
                item.top = true;
                item.select = false;
                window.location.reload();
            },
            /*----新增-----*/
            addExpense:function(){
                for(var i in this.expenseTable){
                    if(this.expenseTable[i].keep){
                        layer.msg('您有未保存的编辑！', {icon: 2, time: 2000});
                        return false;
                    }
                }
                var cur = this.expenseTable.length
                this.expenseTable.splice(this.expenseTable.length,0,{
                            id: '',
                            ywlx: '添加',
                            money: '0.00',
//                            remarks: '记-12',
                            dw:false,
                            cash: false,
                            top: true,
                            select: false,
                            editor:true,
                            keep: false,
                        }
                )
                /*console.log(this.expenseTable[this.expenseTable.length-1])*/
                /*----新增对象的所有数据-----*/
                this.expenseTable[this.expenseTable.length-1].editor = false
                this.expenseTable[this.expenseTable.length-1].keep = true
                this.expenseTable[this.expenseTable.length-1].top = false
                this.expenseTable[this.expenseTable.length-1].select = true
            },
        },
    })
</script>
@endsection