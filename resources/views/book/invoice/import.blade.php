@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/receipts.css?v=201808221">
@endsection

@section('content')
    <div class="receipts" v-cloak>
        <div class="formWrapper">
            <form class="layui-form receiptsMenu" id="query_form">
                <div class="receiptsMenuLeft">
                    <input type="hidden" id="query_input" v-model="q_val" v-bind:name="q_key">
                    <!-- 查询input -->
                    <input type="hidden" id="q_key_input" name="q_key" v-model="q_key">
                    <input type="hidden" id="q_val_input" name="q_val" v-model="q_val">
                    <div>
                        <select id="q_key" lay-filter="q_key" v-model="q_key">
                            {{--<option value="qdzt">清单状态</option>--}}
                            <option value="">全部</option>
                            <option value="pzzt">凭证状态</option>
                            <option value="tax_rate">税率</option>
                            <option value="fphm">发票号码</option>
                            <option value="jszt">结算</option>
                            <option value="xfdw_name">销方名称</option>
                            <option value="money">金额</option>
                            <option value="remark">备注</option>
                        </select>
                    </div>
                    <div id="input_div">
                        <input type="text" id="q_val_input" class="layui-input" v-model="q_val">
                    </div>
                    <div id="select_div">
                        <select id="q_val_select" lay-filter="q_val_select" v-model="q_val"></select>
                    </div>
                    <div class="receipts-invoice">
                        <a href="javascript:;" @click="query">搜索</a>
                    </div>
                    <div class="receipts-invoice">
                        <a class="invoice_button" href="javascript:;" @click="invoiceSummary">查看发票汇总</a>
                    </div>
                    <div class="pickDate">
                        已勾选 <strong style="color: #f97d3c;">@{{ num }}</strong> / <span>@{{ getInvoiceNum() }}</span>条数据
                    </div>
                    <div class="receipts-invoice">
                        <a href="javascript:;" class="select_all_button" @click="select_all">勾选全部数据</a>
                    </div>
                </div>
                <div class="receiptsMenuRight">
                    <div class="receipts-invoice">
                        <a class="add_invoice_button" data-href="{{ url('/book/invoice/addImport')  }}" data-title="新增进项发票" href="javascript:;" onclick="top.Hui_admin_tab(this)">新增发票</a>
                    </div>
                    <div class="receipts-invoice">
                        <a href="javascript:;" @click="cashPay">现金付款</a>
                    </div>
                    <div class="receipts-del">
                        <a href="javascript:;" @click="del_selected">删除</a>
                    </div>
                    <div class="receipts-invoice">
                        <div class="receiptsFilter" ref="moreType">
                            <div class="receiptsHead" @click="receiptsList = !receiptsList">
                                <span class="titleTop">@{{getDate}}</span>
                                <i class="icon iconfont icon-xialazhishijiantou"></i>
                            </div>
                            <ul class="receipts-showTitle" v-show="receiptsList">
                                <li v-for="item in options" :key="item.index" @click="getNewAdds(item)">
                                    @{{item.label}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="receiptsTable">
            <div class="receipts-thead tableBorderHead fixTableHeader">
                <table border="0">
                    <thead>
                    <tr>
                        <th class="width3"><input id="head_check_box" type="checkbox" @click="change_all" v-model="checked"></th>
                        <th class="width14">业务类型</th>
                        <th class="width27">税目</th>
                        <th class="width14">开票项目</th>
                        <th class="width8">数量</th>
                        <th class="width8">金额</th>
                        <th class="width8">税率</th>
                        <th class="width8">税额</th>
                        <th class="width10">价税合计</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div>
                <div v-for="(invoice,index) in invoice_list" class="receiptsEach tableScroll">
                    <div class="receipts-table tableBorder">
                        <table border="0">
                            <tbody>
                            <tr>
                                <td class="width3">
                                    <input type="checkbox" :value='invoice["id"]' v-model="selected" :disabled="invoice.disableCkeck">
                                </td>
                                <td colspan="7">
                                    <div class="receipts-tableTotal">
                                        <div class="receipts-tableInvoice">
                                            <div class="receipts-tel">
                                                <i>发票号码:</i>
                                                <span>@{{ invoice['fphm']  }}</span>
                                            </div>
                                            <div class="receipts-tel">
                                                <i>发票代码:</i>
                                                <span>@{{ invoice['fpdm']  }}</span>
                                            </div>
                                            <div class="receipts-name">
                                                <i>单位名称:</i>
                                                <span>@{{ invoice['xfdw_name']  }}</span>
                                            </div>
                                            <div class="receipts-num">
                                                <i>发票张数:</i>
                                                <span>@{{ invoice['fpzs']  }}</span>
                                            </div>
                                            <div class="receipts-status">
                                                <i>结算状态:</i>
                                                <span>@{{ invoice['jszt']  }}</span>
                                            </div>
                                            <div class="receipts-invoiceText">
                                                <i>凭证号:</i>
                                                <span class="text" @click="addInvoice(invoice)">@{{ invoice['voucher_num']  }}</span>
                                            </div>
                                        </div>
                                        <div class="receipts-tableInvoiceRight">
                                            <i class="icon iconfont tableMarked" @click="show_add_voucher(invoice)">&#xe602;</i> <!-- 记账 -->
                                            <i class="icon iconfont tableEditor"  @click="edit_invoice(invoice.id)" ref="curDom" >&#xe606;</i><!-- 编辑 -->
                                            <i class="icon iconfont tableDel" @click="delTotal(index)">&#xe605;</i> <!-- 删除 -->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="receipts-tableCenter tableBorder">
                        <table border="0">
                            <tbody>
                            <tr v-for="invoice_item in invoice.invoice_item">
                                <td class="width3"></td>
                                <td class="width14">@{{ invoice_item['ywlx_name'] }}</td>
                                <td class="width27"></td> <!-- 税目 -->
                                <td class="width14">@{{ invoice_item['kpxm_name'] }}</td>
                                <td class="width8">@{{ invoice_item['num'] }}</td>
                                <td class="width8">@{{ invoice_item['money'] }}</td>
                                <td class="width8">@{{ invoice_item['tax_rate'] }}</td>
                                <td class="width8">@{{ invoice_item['tax_money'] }}</td>
                                <td class="width10">@{{ invoice_item['fee_tax_sum'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="receipts-priceTotal tableBorder">
                        <table border="0">
                            <tbody>
                            <tr>
                                <td class="width3"></td>
                                <td class="width14"></td>
                                <td class="width27">合计</td>
                                <td class="width14"></td>
                                <td class="width8"></td>
                                <td class="width8">@{{ invoice['total_money'] }}</td>
                                <td class="width8"></td>
                                <td class="width8">@{{ invoice['total_tax_money'] }}</td>
                                <td class="width10">@{{ invoice['total_fee_tax_sum'] }}</td>
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
    <script>

        function sleep(numberMillis) {
            var now = new Date();
            var exitTime = now.getTime() + numberMillis;
            while (true) {
                now = new Date();
                if (now.getTime() > exitTime)
                    return;
            }
        }

        function checkVueApp() {
            var waitVueAppRender = function () {
                //console.log(receipts);
                if (receipts != undefined) {
                    clearInterval(interval);
                } else {
                    sleep(50);
                }
                return waitVueAppRender;//若不返回时，此函数只会执行一次
            };
            var interval = setInterval(waitVueAppRender(), 50);
        }

        var receipts = new Vue({
                "el": ".receipts",
                data: {
                    num:'0',
                    checked:false,
                    selected:[],
                    getDate: '更多',
                    receiptsList: false,
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
                    invoice_list: [],
                    selected_invoice_id: [],
                    q_val: '{{request('q_val')}}',
                    q_key: '{{request('q_key')}}',
                    submit_url: ''
                },
                created: function () {


                    var list = JSON.parse(JSON.stringify({!! json_encode($list) !!}));

                    this.mapClass = ['deductible', 'noDeductible'];
                    this.dateMap = ['可抵用', '不可抵用'];
                    var arrList = list['data'];
                    for(var i in arrList){
                        arrList[i].total_money = Number(arrList[i].total_money).toFixed(2);
                        arrList[i].total_tax_money = Number(arrList[i].total_tax_money).toFixed(2);
                        arrList[i].total_fee_tax_sum = Number(arrList[i].total_fee_tax_sum).toFixed(2);
                        arrList[i].disableCkeck = false;
                        if(arrList[i].voucher_num){
                            arrList[i].disableCkeck = true;
                        }

                    }
                    this.invoice_list = arrList;
                    /*console.log(this.invoice_list);*/
                    this.initQueryDivShow();
                },
                mounted:function(){
                    this.clickBlank()
                },
                methods: {
                    //点击空白处相应div隐藏
                    clickBlank:function(){
                        /*--更多选择moreType*/
                        var moreType = this.$refs.moreType;
                        var _this = this;
                        document.addEventListener('click',function(e){
                            if(!moreType.contains(e.target)){
                                _this.receiptsList = false;
                            }
                        });
                    },
                    getNewAdds: function (item) {
                        this.receiptsList = false;
                        this.getDate = item.label;

                        //console.log(value);

                        if (item.value == 11) {
                            var download_url = '{{ url('book/invoice/exportExcel') }}?' + this.q_key + '=' + this.q_val;
                            window.open(download_url);
                        }
                    },
                    getInvoiceNum: function () {
                        return this.invoice_list.length;
                    },
                    checked: function (id) {
                        //console.log(this.selected_invoice_id.indexOf(id));
                        return this.selected_invoice_id.indexOf(id) != -1;
                    },
                    /*---删除当前的整个table---*/
                    delTotal: function (index) {
                        /*console.log(index)
                        console.log(expenseList)*/
                        var _this = this;
                        var voucher_id = _this.invoice_list[index]['voucher_id'];
                        var msg = voucher_id == 0 ? '确定删除吗？' : '当前发票已生成凭证，是否确定删除？';

                        layer.confirm(
                            msg, {icon: 3, title: '提示'},
                            function () {
                                _this.$http.post('{{ url('book/invoice/delete') }}', {_token: "{{csrf_token()}}", id: _this.invoice_list[index]['id']}).then(function (response) {
                                    if (response.body.status == 1) {
                                        layer.msg(response.body.info, {icon: 1, time: 99000, shade: 0.2});
                                        _this.invoice_list.splice(index, 1);
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1500);
                                    } else {
                                        layer.msg(response.body.info, {icon: 2, time: 99000, shade: 0.2});
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1800);
                                    }
                                });
                            }
                        );
                    },

                    /*---表头全选checkbox点击事件---*/
                    change_all: function () {
                        layer.msg('已经生成凭证，禁止选择', {icon: 3, time: 2000});
                        var arr = [];
                        for(var i in this.invoice_list){
                            if(this.invoice_list[i].voucher_num != ''){
                                arr.push(this.invoice_list[i].voucher_num)
                            }
                        }
                        if (this.selected.length != (this.invoice_list.length-arr.length)) {
                            this.selected = [];
                            for (var i in this.invoice_list) {
                                if(this.invoice_list[i].voucher_num == ''){
                                    this.selected.push(this.invoice_list[i].id)
                                }
                            }
                        } else {
                            this.selected = [];
                        }
                    },

                    /*---勾选全部数据---*/
                    select_all: function () {
                        /*var selected = [];
                        $('#head_check_box').prop('checked', true);
                        for (var i in this.invoice_list) {
                            selected.push(this.invoice_list[i]['id']);
                        }
                        this.selected_invoice_id = selected;*/
                        if (this.selected.length != this.invoice_list.length) {
                            this.selected = [];
                            for (var i in this.invoice_list) {
                                if(this.invoice_list[i].voucher_num == ''){
                                    this.selected.push(this.invoice_list[i].id)
                                }
                            }
                            this.num = this.selected.length;
                        } else {
                            this.selected = [];
                        }
                    },

                    /*---删除选择数据---*/
                    del_selected: function () {
                        var _this = this;
                        var selectCheck = [];
                        var ids = JSON.stringify(_this.selected);
                        var params = {'_token': '{{ csrf_token()  }}', 'ids': ids};
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
                            _this.$http.post('{{ url('book/invoice/deleteAll') }}', params).then(function (response) {
                                response = response.body;
                                if (response.status == '1') {
                                    console.log(1)
                                    for (var j = 0; j < _this.selected.length; j++) {
                                        for (var i = 0; i < _this.invoice_list.length; i++) {
                                            if (_this.selected[j] == _this.invoice_list[i].id) {
                                                _this.invoice_list.splice(i, 1)
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
                    /*=-------点击生成的凭证---记—12------*/
                    addInvoice:function(invoice) {
                        var items = invoice['voucher_id'];
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
                    /*---点击记账,发票凭证预览页面---*/
                    show_add_voucher: function (invoice) {
                        var id = invoice['id'];
                        var voucher_id = invoice['voucher_id'];
                        var voucher_num = invoice['voucher_num'];
                        // var voucher_id = this.invoice_list;
                        //console.log(id);
                        if (voucher_num !== '') {
                            layer.msg('该发票已生成记账凭证', {icon: 2, time: 1000});
                            return;
                        }
                        var items = {"type":'2',"id": id};
                        items = JSON.stringify(items);
                        localStorage.setItem('invoiceId',items);
                        //console.log(items);
                        layer.open({
                            type: 2,
                            title: '发票凭证预览页面',
                            shadeClose: true,
                            shade: 0.2,
                            skin:'pzAlert',
                            maxmin: true, //开启最大化最小化按钮
                            area: ['1200px', '96%'],
                            content: ['{{ url('book/voucher/addKeep') }}', 'yes'],
                        });
                    },

                    /*---编辑发票---*/
                    edit_invoice: function (id) {
                        /* var curDom = this.$refs.curDom;
                        var url = '{{url('book/invoice/editImport')}}/' + id;
                        $(curDom).attr('data-href',url);
                         top.Hui_admin_tab(curDom)
                        */
                        var url = '{{url('book/invoice/editImport')}}/' + id;
                        this.href= url
                        parent.creatIframe(url, '编辑进项发票-' + id);

                    },

                    /* ---现金付款提示--- */
                    cashPay: function () {
                        layer.msg('暂不支持现金付款，请使用【快速付款】', {icon: 2, time: 2000});
                    },

                    /* ---查看发票汇总--- */
                    invoiceSummary: function () {
                        layer.open({
                            type: 2,
                            title: '发票汇总',
                            shadeClose: true,
                            skin: 'components',
                            shade: 0.3,
                            maxmin: true, //开启最大化最小化按钮
                            area: ['893px', '600px'],
                            content: '{{ url('/book/invoice/summary')   }}?type={{ \App\Entity\Invoice::TYPE_IMPORT  }}'
                        });
                    },

                    /* ---查询表单--- */
                    query: function () {
                        $('#query_form').submit();
                    },

                    /* ---初始化查询div--- */
                    initQueryDivShow: function () {
                        $('#input_div').hide();
                    },
                },
                    watch: {
                        "selected": function () {
                            var arr = [];
                            for(var i in this.invoice_list){
                                if(this.invoice_list[i].voucher_num != ''){
                                   arr.push(this.invoice_list[i].voucher_num)
                                }
                            }
                            if (this.selected.length == (this.invoice_list.length - arr.length)) {
                                this.checked = true
                            } else {
                                this.checked = false
                            }
                        }
                    }
            })
        ;

        layui.use('form', function () {

            //checkVueApp();

            var form = layui.form;

            var opt = {
                pzzt: [{val: '', label: '全部'}, {val: '0', label: '未生成'}, {val: '1', label: '已生成'}],
                tax_rate: [
                    {val: '', label: '全部'}, {val: '0.03', label: '0.03'}, {val: '0.17', label: '0.17'},
                    {val: '0.01', label: '0.01'}, {val: '0.16', label: '0.16'}
                ],
                jszt: [{val: '', label: '全部'}, {val: '0', label: '未结算'}, {val: '1', label: '已结算'}],
            };

            if (receipts == undefined) {
                //form.render();
                return;
            }

            if (receipts != undefined && (receipts.q_key == 'fphm' || receipts.q_key == 'xfdw_name' || receipts.q_key == 'money' || receipts.q_key == 'remark')) {

                $('#select_div').hide();
                $('#input_div').show();
            } else {
                $('#select_div').show();
                $('#input_div').hide();
                $('#q_val_select').empty();


                for (var i in opt[receipts.q_key]) {
                    $('#q_val_select').append("<option value='" + opt[receipts.q_key][i]['val'] + "'>" + opt[receipts.q_key][i]['label'] + "</option>");
                }

                $('#q_val_select').val(receipts.q_val);
            }

            form.render();

            form.on("select(q_key)", function (data) {

                receipts.q_key = data.value || '{{ request('q_key') }}';
                receipts.q_val = '';

                //$('#query_input').prop('name', data.value);

                if (receipts.q_key == 'fphm' || receipts.q_key == 'xfdw_name' || receipts.q_key == 'money' || receipts.q_key == 'remark') {
                    //receipts.showInputDiv();
                    $('#select_div').hide();
                    $('#input_div').show();
                } else {
                    //receipts.showSelectDiv();
                    $('#select_div').show();
                    $('#input_div').hide();
                    $('#q_val_select').empty();
                    for (var i in opt[data.value]) {
                        $('#q_val_select').append("<option value='" + opt[data.value][i]['val'] + "'>" + opt[data.value][i]['label'] + "</option>");
                    }
                    form.render();
                }

                form.render();
            });

            form.on("select(q_val_select)", function (data) {
                receipts.q_val = data.value;
                form.render();
            });

        });

    </script>
@endsection