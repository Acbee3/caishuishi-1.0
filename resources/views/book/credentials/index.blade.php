@extends('book.layout.base')

@section('css')
    @parent
    <!--凭证-->
    <link rel="stylesheet" href="/css/book/cwcl/pingzheng.css?v=2018090401">
@endsection

@section('content')

    <div class="financeInvoice" v-cloak>
        <div class="formWrapper">
            <div class="layui-form financeForm">
                <div class="financeLeft">
                    <div class="finance-item">
                        <select name="invoice" lay-filter="invoice">
                            <option :value="item.value" v-for="item in invoiceOption"
                                    :key="item.id">@{{item.label}}
                            </option>
                        </select>
                    </div>
                    <form method="get" id="pzly" class="finance-itemA" v-show="itemA">
                        <div class="ly">
                            <select name="voucher_source" lay-filter="invoiceType">
                                <option v-for="item in invoiceTypeOption" :value="item.key"
                                        :key="item.id">@{{item.value}}
                                </option>
                            </select>
                        </div>
                        <a href="javascript:;" @click="pzly" class="searchLy">查找</a>
                    </form>
                    <div class="finance-itemB" v-show="itemB">
                        <form method="get" id="bm">
                            <input type="text" placeholder="请输入科目编码或名称" v-model="searchCode" name="kuaijikemu">
                            <span @click="searchCoad" class="bm">查找</span>
                            {{--<i class="iconfont icon-search" @click="searchCoad"></i>--}}
                        </form>
                    </div>
                    <div class="finance-itemC" v-show="itemC" >
                        <form method="get" id="pzh">
                            <input type="text" v-model="minNum" name="voucher_num_min">
                            <span>至</span>
                            <input type="text" v-model="maxNum" name="voucher_num_max">
                            <a href="javascript:;" class="search" @click="searchInvoice">查询</a>
                        </form>
                    </div>
                    <div class="finance-itemD" v-show="itemD" @click="showForm1" ref="moreSearch">
                        <input type="text" placeholder="点击显示更多搜索条件" readonly>
                        <span class="bm">查找</span>
                        {{--<i class="iconfont icon-search"></i>--}}
                    </div>
                    <form action="" class="myForm" v-show="showForm" id="myForm" ref="dateMenu" method="get">
                        <div class="more-item">
                            <label>会计期间:</label>
                            <div>
                                <div class="pzItem">
                                    <select name="fiscal_period_start" lay-filter="kjStart" id="kjqjStart">
                                        <option :value="item.value" v-for="item in kmyeOption"
                                                :key="item.index">@{{item.label}}
                                        </option>
                                    </select>
                                </div>
                                <div class="pzItemC">-</div>
                                <div class="pzItem">
                                    <select name="fiscal_period_end" lay-filter="kjEnd" id="kjqjEnd">
                                        <option :value="item.value" v-for="item in kmyeOption"
                                                :key="item.index">@{{item.label}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="more-item">
                            <label>凭证来源:</label>
                            <div>
                                <div class="pzly-item">
                                    <select name="voucher_source" lay-filter="invoiceLy">
                                        <option v-for="item in invoiceTypeOption" :key="item"
                                                :value="item.key">@{{item.value}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="more-item">
                            <label>科目:</label>
                            <div class="km-border">
                                <input type="text" placeholder="请输入科目编码或名称" name="kuaijikemu">
                                <i class="iconfont">&#xe61e;</i>
                            </div>
                        </div>
                        <div class="more-item">
                            <label>凭证号:</label>
                            <div class="pzh-item">
                                <input type="text" name="voucher_num_min">
                                <span>至</span>
                                <input type="text" name="voucher_num_max">
                            </div>
                        </div>
                        <div class="more-item">
                            <label>摘要:</label>
                            <div class="zy">
                                <input type="text" name="zhaiyao">
                            </div>
                        </div>
                        <div class="more-item">
                            <label>金额:</label>
                            <div class="pzh-item">
                                <input type="text" name="total_debit_money_min">
                                <span>至</span>
                                <input type="text" name="total_debit_money_max">
                            </div>
                        </div>
                        <div class="more-item">
                            <label>状态:</label>
                            <div>
                                <div class="pzly-item">
                                    <select name="audit_status">
                                        <option v-for="(item,index) in status" :key="item" :value="index">@{{item}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="more-itemBtn">
                            <a href="javascript:;" class="reset">重置</a>
                            <a href="javascript:;" class="searchBtn" @click="moreSearch">查询</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="financeRight">
                <a href="javascript:;" class="addBtn" data-href="{{ url('/book/voucher/addEditor') }}" data-title="新增凭证" onclick="top.Hui_admin_tab(this)">新增</a>
                <a href="javascript:;" class="delBtn" @click="delSelect">删除</a>
                <a href="javascript:;" class="printBtn" @click="showPdf">打印</a>
                <div class="moreBtn">
                    <div class="tips">
                        <span>更多</span>
                        <i class="iconfont">&#xe620;</i>
                    </div>
                    <ul class="moreList">
                        <li @click="audit">审核</li>
                        <li @click="Naudit">反审核</li>
                        {{--<li>合并凭证</li>
                        <li>红冲</li>
                        <li>列表导出</li>--}}
                    </ul>
                </div>
            </div>
        </div>
        <div class="financeTable">
            <div class="financeTable-head fixTableHeader">
                <table>
                    <thead>
                    <tr>
                        <th class="width2"><input type="checkbox" v-model="checked" @click="allSelect"></th>
                        <th class="width8">日期</th>
                        <th class="width8">凭证字号</th>
                        <th class="width12">摘要</th>
                        <th class="width22">科目</th>
                        <th class="width8">借方</th>
                        <th class="width8">贷方</th>
                        <th class="width8">附件张数</th>
                        <th class="width8">制单人</th>
                        <th class="width8">审核人</th>
                        <th class="width8">凭证来源</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="financeTable-body tableScroll">
                <table>
                    <tbody>
                    <tr v-for="item in financeTables" :key="item.index">
                        <td class="width2"><input type="checkbox" v-model="selected" :value="item.id"></td>
                        <td class="width8">@{{item.voucher_date}}</td>
                        <td class="width8">
                            <a href="javascript:;" class="marksNum" @click="addInvoice(item)">
                                记-@{{item.voucher_num}}
                            </a>
                        </td>
                        <td class="width12 textL">
                            <div>
                                <p v-for="list in item.voucher_item" :key="list">@{{list.zhaiyao}}</p>
                            </div>
                        </td>
                        <td class="width22 textL">
                            <div>
                                <p v-for="list in item.voucher_item" :key="list">@{{list.kuaijikemu}}</p>
                            </div>
                        </td>
                        <td class="width8 textR">
                            <div>
                                <p v-for="list in item.voucher_item" :key="list">@{{list.debit_money}}</p>
                            </div>
                        </td>
                        <td class="width8 textR">
                            <div>
                                <p v-for="list in item.voucher_item" :key="list">@{{list.credit_money}}</p>
                            </div>
                        </td>
                        <td class="width8">@{{item.attach}}</td>
                        <td class="width8">@{{item.creator_name}}</td>
                        <td class="width8">@{{item.auditor_name}}</td>
                        <td class="width8">@{{item.voucher_source}}</td>
                    </tr>
                    </tbody>
                </table>
                @if( !empty($data) )
                    {{ $data->appends(request()->toArray())->links() }}
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script')
    @parent
    <script src="/common/js/jquery.serializejson.min.js"></script>
    <script src="/js/book/table.js"></script>
    <!--公用-->
    <script>

        new Vue({
            'el': '.financeInvoice',
            data: {
                itemA: true,
                itemB: false,
                itemC: false,
                itemD: false,
                checked: false,
                showForm: false,
                searchCode: '',
                minNum: '',
                maxNum: '',
                //form提交的数据
                selected: [],
                kmyeOption: [],
                financeTables: [],
                invoiceOption: [
                    {
                        label: '凭证来源',
                        value: '0'
                    },
                    {
                        label: '科目',
                        value: '1'
                    },
                    {
                        label: '凭证号',
                        value: '2'
                    },
                    {
                        label: '更多',
                        value: '3'
                    }
                ],
                invoiceTypeOption: [],
                status: [],
            },
            created: function () {
                var _this = this;
                layui.use('form', function () {
                    var form = layui.form;
                    form.on('select(invoice)', function (data) {
                        var invoiceVal = data.value;
                        if (invoiceVal == 0) {
                            //凭证来源
                            _this.itemA = true;
                            _this.itemB = false;
                            _this.itemC = false;
                            _this.itemD = false;
                            _this.showForm = false;
                        }
                        if (invoiceVal == 1) {
                            //科目
                            _this.itemB = true;
                            _this.itemA = false;
                            _this.itemC = false;
                            _this.itemD = false;
                            _this.showForm = false;
                        }
                        if (invoiceVal == 2) {
                            //凭证号
                            _this.itemC = true;
                            _this.itemA = false;
                            _this.itemB = false;
                            _this.itemD = false;
                            _this.showForm = false;
                        }
                        if (invoiceVal == 3) {
                            //更多
                            _this.itemC = false;
                            _this.itemA = false;
                            _this.itemB = false;
                            _this.itemD = true;
                            _this.showForm = true;
                        }
                    });
                    //凭证来源对应的table数据
                    form.on('select(invoiceType)', function (data) {

                        /*----此处ajax请求二期再用(option不同请求的数据)------*/
                        /*var strId = data.value;
                        var param = {'_token': '{{ csrf_token()  }}', 'voucher_source': data.value};
                        _this.$http.get('{{route('voucher.index')}}', {params: param}).then(function (response) {
                            response = response.body;
                            _this.financeTables = response.data.data;
                        }).then(function () {
                            computedHeight()
                        })*/
                    });
                    //会计期间的option
                    form.on('select(kjStart)', function (data) {

                    });
                })
                _this.getfinanceTables();
            },
            mounted: function () {
                this.clickBlank()
            },
            methods: {
                /*-----凭证来源查找-------*/
                pzly:function(){
                    $("#pzly").submit();
                },
                /*------测试-----*/
                showForm1: function () {
                    /*console.log(1)
                    this.showForm = true;*/
                },
                //点击空白处相应div隐藏
                clickBlank: function () {
                    /*----更多选项里的会计期间与header的日期对应---------*/
                    var kjqj = '{{ \App\Entity\Period::currentPeriod() }}';
                    var kjMonth = kjqj.split('-')[1];
                    if (kjMonth.indexOf(0) == '0') {
                        kjMonth = kjMonth.slice(1);
                    }
                    var data  = kjqj.split('-')[0] + '-' + kjMonth;
                    var kjSatrt = $("#kjqjStart").children();
                    var kjqjEnd = $("#kjqjEnd").children();
                    for(var i = 0; i < kjSatrt.length; i++){
                        if(kjSatrt[i].value==data){
                            kjSatrt[i].selected = 'selected'
                        }
                    }
                    for(var i = 0; i < kjqjEnd.length; i++){
                        if(kjqjEnd[i].value==data){
                            kjqjEnd[i].selected = 'selected'
                        }
                    }
                    /*--会计期间始kjStart，结束kjEnd,日期弹窗dateMenu*/
                    var dateMenu = this.$refs.dateMenu;
                    var moreSearch = this.$refs.moreSearch;
                    var _this = this;
                    document.addEventListener('click', function (e) {
                        if (!dateMenu.contains(e.target)) {
                            _this.showForm = false;
                        }
                        if (moreSearch.contains(e.target)) {
                            _this.showForm = true;
                        }
                    })
                },
                //table数据获取
                getfinanceTables: function () {

                    //this.$http.get('{{route('voucher.index')}}').then(function (response) {
                        response = JSON.parse(JSON.stringify({!! json_encode($data) !!}));
                        // console.log(response.data.data)
                        for (var i = 0; i < response.data.length; i++) {
                            for (var j = 0; j < response.data[i].voucher_item.length; j++) {
                                // console.log(response.data.data[i].voucher_item[j].credit_money == '0.00')
                                if (response.data[i].voucher_item[j].credit_money == '0.00') {
                                    response.data[i].voucher_item[j].credit_money = '';
                                }
                                if (response.data[i].voucher_item[j].debit_money == '0.00') {
                                    response.data[i].voucher_item[j].debit_money = '';
                                }
                            }
                        }
                        // console.log(response.data.data)
                        this.status = JSON.parse(JSON.stringify({!! json_encode($audit_status) !!}));
                        this.invoiceTypeOption = JSON.parse(JSON.stringify({!! json_encode($voucher_source) !!}));
                        this.financeTables = response.data;
                        this.kmyeOption = JSON.parse(JSON.stringify({!! json_encode($period) !!}));
                        for (var i in this.financeTables) {
                            if (this.financeTables[i].auditor_name == '') {
                                this.financeTables[i].auditor_name = '未审核';
                            }
                        }
                    //}).then(function () {
                        layui.use('form', function () {
                            var form = layui.form;
                            form.render();
                        })
                        computedHeight()
                    //});
                },
                /*---点击记账,发票凭证预览页面---*/
                addInvoice: function (item) {
                    var items = item.id;
                    //localStorage.setItem('invoiceId',items);
                    localStorage.setItem('voucher_id', items);

                    layer.open({
                        type: 2,
                        title: '凭证信息',
                        shadeClose: true,
                        shade: 0.2,
                        skin: 'pzAlert',
                        maxmin: true, //开启最大化最小化按钮
                        area: ['1200px', '96%'],
                        //content: ['{{ url('book/voucher/add') }}', 'yes']
                        content: ['{{ url('book/voucher/edit') }}', 'yes']
                    });
                },
                //全选
                allSelect: function () {
                    if (this.selected.length != this.financeTables.length) {
                        this.selected = [];
                        for (var i in this.financeTables) {
                            this.selected.push(this.financeTables[i].id)
                        }
                    } else {
                        this.selected = [];
                    }
                },
                //删除按钮
                delSelect: function () {
                    var _this = this;
                    var selectCheck = [];
                    var params = {'_token': '{{ csrf_token()  }}', 'id': _this.selected};
                    if (_this.selected.length <= 0) {
                        layer.open({
                            type: 1,
                            title: '信息',
                            skin: 'pzAleart',
                            shadeClose: true,
                            shade: false,
                            maxmin: false, //开启最大化最小化按钮
                            area: ['310px', '140px'],
                            content: '<div>请选择需要删除的凭证</div>',
                            btn: ['确定'],
                            yes: function (index, layero) {
                                layer.close(index)
                            }
                        });
                        return
                    }
                    layer.confirm('确定删除吗？', {icon: 3, title: '提示'}, function () {
                        _this.$http.post('{{route('voucher.del')}}', params).then(function (response) {
                            response = response.body;
                            if (response.status == '1') {
                                for (var j = 0; j < _this.selected.length; j++) {
                                    for (var i = 0; i < _this.financeTables.length; i++) {
                                        if (_this.selected[j] == _this.financeTables[i].id) {
                                            _this.financeTables.splice(i, 1)
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
                //审核
                audit: function () {
                    var _this = this;
                    var params = {'_token': '{{ csrf_token()  }}', 'id': _this.selected, 'audit_status': '1'};
                    if (_this.selected.length <= 0) {
                        layer.open({
                            type: 1,
                            title: '信息',
                            skin: 'pzAleart',
                            shadeClose: true,
                            shade: false,
                            maxmin: false, //开启最大化最小化按钮
                            area: ['310px', '140px'],
                            content: '<div>请选择需要审核的凭证。</div>',
                            btn: ['确定'],
                            yes: function (index, layero) {
                                layer.close(index)
                            }
                        });
                        return
                    }
                    //被选中的每行的信息
                    var indexs = [];
                    for (var j = 0; j < _this.selected.length; j++) {
                        for (var i in _this.financeTables) {
                            if (_this.financeTables[i].id == _this.selected[j]) {
                                indexs.push(i);
                                if (_this.financeTables[i].auditor_name == '未审核') {
                                    _this.$http.post('{{route('voucher.audit')}}', params).then(function (response) {
                                        response = response.body;
                                        if (response.status == '1') {
                                            window.location.reload();
                                            layer.msg(response.info, {icon: 1, time: 1000});
                                        }
                                    })
                                } else {
                                    layer.confirm('所选的凭证已审核', {icon: 3, title: '审核凭证'})
                                }
                            }
                        }
                    }
                },
                //反审核
                Naudit: function () {
                    var _this = this;
                    var params = {'_token': '{{ csrf_token()  }}', 'id': _this.selected, 'audit_status': '0'};
                    if (_this.selected.length <= 0) {
                        layer.open({
                            type: 1,
                            title: '信息',
                            skin: 'pzAleart',
                            shadeClose: true,
                            shade: false,
                            maxmin: false, //开启最大化最小化按钮
                            area: ['310px', '140px'],
                            content: '<div>请选择需要反审核的凭证。</div>',
                            btn: ['确定'],
                            yes: function (index, layero) {
                                layer.close(index)
                            }
                        });
                        return
                    }
                    //被选中包含未审核
                    var indexs = [];
                    for (var j = 0; j < _this.selected.length; j++) {
                        for (var i in _this.financeTables) {
                            if (_this.financeTables[i].id == _this.selected[j]) {
                                indexs.push(i);
                                if (_this.financeTables[i].auditor_name != '未审核') {
                                    //console.log(6)
                                    layer.confirm('正在努力帮您审核凭证,请耐心等待哦？', {icon: 3, title: '审核凭证'}, function () {
                                        _this.$http.post('{{route('voucher.audit')}}', params).then(function (response) {
                                            response = response.body;
                                            if (response.status == '1') {
                                                window.location.reload();
                                                layer.msg(response.info, {icon: 1, time: 1000});
                                            }
                                        })
                                    })
                                } else {
                                    layer.confirm('所选的凭证未审核', {icon: 3, title: '信息'})
                                }
                            }
                        }
                    }
                },
                //输入编码或者科目查找显示的table
                searchCoad: function () {
                    $("#bm").submit()
                    /*var _this = this;
                    var param = {'kuaijikemu': _this.searchCode};
                    //console.log(params);
                    _this.$http.get('{{route('voucher.index')}}', {params: param}).then(function (response) {
                        response = response.body;
                        _this.financeTables = response.data.data;
                    })*/
                },
                //查询最小凭证
                searchInvoice: function () {
                    $("#pzh").submit();
                    /*-----此下是ajax请求暂时不用---------*/
                    /* var _this = this;
                    var data = {'voucher_num_min': _this.minNum, 'voucher_num_max': _this.maxNum};

                    _this.$http.get('{{route('voucher.index')}}', {params: data}).then(function (response) {
                        response = response.body;
                        _this.financeTables = response.data.data;
                    })*/
                },
                //更多查找
                moreSearch: function () {
                    $("#myForm").submit();
                    /*-----此下ajax二期再用-----*/
                   /* var _this = this;
                    var data = $("#myForm").serializeJSON();
                    //console.log(data)
                    _this.$http.get('{{route('voucher.index')}}', {params: data}).then(function (response) {
                        response = response.body;
                        _this.financeTables = response.data.data;
                    });*/
                    this.showForm = false;
                },
                //显示pdf
                showPdf: function () {
                    window.open('/book/voucher/pdf');
                }

            },
            watch: {
                "selected": function () {
                    if (this.selected.length == this.financeTables.length) {
                        this.checked = true
                    } else {
                        this.checked = false
                    }
                }
            }
        })

    </script>
@endsection