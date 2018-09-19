@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css?v=20180820010")}}">
    <!--总账-->
    <link rel="stylesheet" href="/css/book/cwcl/subjectBlance.css?v=2018090401">
    <link rel="stylesheet" href="{{asset("css/agent/zTreeStyle.css")}}" type="text/css">
@endsection

@section('content')
    <div class="generalLedger" v-cloak>
        <div>
            <div class="subjectLeft layui-form">
                <div>
                    <p class="totalText">总账</p>
                </div>
                <div class="subjectData-item" ref="dateMenu">
                    <div class="subjectData" @click="subjectForm = !subjectForm">
                        <span class="text">@{{date}}</span>
                        <i class="iconfont downTip">&#xe620;</i>
                    </div>
                    <form class="subjectForm" v-show="subjectForm" id="subjectForm" style="display:none;">
                        <div class="form-item">
                            <label>会计期间:</label>
                            <div class="kjqj">
                                <div class="kmyeItem" ref="kjStart">
                                    <div class="selectKmye" @click="codeList = !codeList">
                                        <span class="titleTop">@{{getDate}}</span>
                                        <i class="iconfont downTip">&#xe620;</i>
                                    </div>
                                    <ul class="showKmye" v-show="codeList">
                                        <li v-for="item in kmyeOption" :key="item.index" @click="getStart(item.label)">
                                        @{{item.label}}
                                        </li>
                                    </ul>
                                </div>
                                <div class="kmyeItemC">-</div>
                                <div class="kmyeItem" ref="kjEnd">
                                    <div class="selectKmye" @click="endDate = !endDate">
                                        <span class="titleTop">@{{getDate1}}</span>
                                        <i class="iconfont downTip">&#xe620;</i>
                                    </div>
                                    <ul class="showKmye" v-show="endDate">
                                        <li v-for="item in kmyeOption" :key="item.index" @click="getEnd(item.label)">
                                        @{{item.label}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="subjectBtn">
                           {{-- <a href="javascript:;" class="resetBtn" @click="resetData">重置</a>--}}
                            <a href="javascript:void(0);" class="sureBtn" @click="doSubmitBtn">确定</a>
                        </div>
                    </form>
                </div>

                <div class="subject-item" style="width: 180px;">
                    <form class="layui-form km_Form">
                        <select name="subject" lay-filter="subject" id="subject">
                            <option value="">=== 科目 ===</option>
                            <option v-for="item in km_option" :value="item.num" >@{{item.name}}</option>
                        </select>
                    </form>
                </div>

                {{--<div class="subjectRefresh">
                    <i class="iconfont" @click="doRefresh">&#xe60a;</i>
                </div>--}}
            </div>
            <div class="subjectRight">
                <a href="javascript:void(0);" @click="doExport" class="export">导出</a>
                <a href="javascript:void(0);" @click="doPrinting" class="print">打印</a>
            </div>
        </div>
        <div class="ledgerTable">
            <div class="ledgerTableHead fixTableHeader">
                <table>
                    <thead>
                    <tr>
                        <th class="width15">科目编码</th>
                        <th class="width15">科目名称</th>
                        <th class="width12">期间</th>
                        <th class="width12">摘要</th>
                        <th class="width12">借方</th>
                        <th class="width12">贷方</th>
                        <th class="width10">方向</th>
                        <th class="width12">余额</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="ledgerTableBody tableScroll">
                <table class="ledger_list">
                    <tr v-for="(item,index) in ledgerData" :key="item" :class="item.v_code">
                        <td class="width15">
                            <a class="invoiceCode" data-href="{{ route('sub_ledger.list') }}" data-title="明细账" href="javascript:void(0);" @click="showTz(item)" ref="curDom">@{{item.code}}</a>
                        </td>
                        <td class="width15">@{{item.name}}</td>
                        <td class="width12">
                            <p v-for="list in item.ledgerItem" :key="list">@{{list.date}}</p>
                        </td>
                        <td class="width12">
                            <p v-for="list in item.ledgerItem" :key="list">@{{list.abstract}}</p>
                        </td>
                        <td class="width12">
                            <p v-for="list in item.ledgerItem" :key="list">
                                <span v-if="list.borrower != '0.00'">@{{list.borrower}}</span><span v-else></span>
                            </p>
                        </td>
                        <td class="width12">
                            <p v-for="list in item.ledgerItem" :key="list">
                                <span v-if="list.lender != '0.00'">@{{list.lender}}</span><span v-else></span>
                            </p>
                        </td>
                        <td class="width10">
                            <p v-for="list in item.ledgerItem" :key="list">@{{list.direction}}</p>
                        </td>
                        <td class="width12">
                            <p v-for="list in item.ledgerItem" :key="list" style="text-align: right; margin-right: 20px;">
                                <span v-if="list.balance != '0.00'">@{{list.balance}}</span><span v-else>@{{list.balance}}</span>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="{{asset("js/agent/jquery.ztree.core-3.5.js")}}" type="text/javascript"></script>
    <script src="{{asset("js/agent/jquery.ztree.exedit-3.5.js")}}" type="text/javascript"></script>
    <script src="/js/book/table.js"></script>
    <script>
        var wrapper = new Vue({
            'el': '.generalLedger',
            data: {
                subjectForm:false,
                codeList:false,
                endDate:false,
                moneyTypes:false,
                getDate: '2017年第6期',
                getDate1: '2017年第6期',
                date: '2017年第6期',
                kmStart: '',
                kmEnd: '',
                kmjcStart: '',
                kmjcEnd: '',
                moneyTypeDate: '人民币',
                kmyeOption: [],
                moneyType:[],
                ledgerData:[],
                km_option:[],
                subject_list:''
            },
            created:function(){
                var _this = this;
                layui.use('form',function(){
                    var form = layui.form;
                    form.render();
                });

                // 下拉筛选 ok
                layui.use('form',function(){
                    layui.form.on('select(subject)',function(data){
                        let select_value = data.value;
                        //let select_name = data.elem[data.elem.selectedIndex].text;
                        if( select_value == '') {
                            //layer.msg("未选择会计科目，请重新选择！", {icon: 2, time: 1000});
                            return false;
                        }else if(select_value == 'all'){
                            $('.ledger_list tr').fadeIn('1000');
                            layer.load(2, {shade: false, time: 500});
                        }else{
                            $(".ledger_list tr").hide();
                            $('.ledger_list .v_'+select_value).fadeIn('1000');
                            layer.load(2, {shade: false, time: 500});
                        }
                    });
                });
            },
            mounted:function(){
                this.clickBlank();
                // 页面加载初始数据
                this.getPageInfo();
            },
            methods:{
                //点击编码跳转明细账
                showTz:function(item){
                    localStorage.setItem('km_code',item.code);
                    localStorage.setItem('start',this.getDate);
                    localStorage.setItem('end',this.getDate1);
                    var curDom = this.$refs.curDom;
                    top.Hui_admin_tab(curDom)
                },
                //点击空白处相应div隐藏
                clickBlank:function(){
                    /*--会计期间始kjStart，结束kjEnd,日期弹窗dateMenu*/
                    var kjStart = this.$refs.kjStart;
                    var kjEnd = this.$refs.kjEnd;
                    var dateMenu = this.$refs.dateMenu;
                    var _this = this;
                    document.addEventListener('click',function(e){
                        if(!kjStart.contains(e.target)){
                            _this.codeList = false;
                        }
                        if(!kjEnd.contains(e.target)){
                            _this.endDate = false;
                        }
                        if(!dateMenu.contains(e.target)){
                            _this.subjectForm = false;
                        }
                    });
                },
                //会计期间开始
                getStart:function(value) {
                    this.codeList = false
                    this.getDate = value
                },
                //会计期间结束
                getEnd:function(value) {
                    this.endDate = false;
                    this.getDate1 = value;
                },
                //重置按钮
                resetData:function(){
                    this.kmStart = '';
                    this.kmEnd = '';
                },
                //确定按钮
                /*-------  确定按钮  ok  -------------*/
                doSubmitBtn:function(){
                    var kjqjStart = this.getDate.replace(/[^0-9]/ig,"");
                    var kjqjEnd = this.getDate1.replace(/[^0-9]/ig,"");
                    if(Number(kjqjEnd) < Number(kjqjStart)){
                        layer.msg('会计期间开始时间不能大于结束时间！', {icon: 2, time: 1500});
                        return;
                    }

                    if(this.getDate == this.getDate1){
                        this.date = this.getDate;
                    }else{
                        this.date = this.getDate + '-'+ this.getDate1;
                    }

                    this.subjectForm = false;

                    // 确定 处理数据
                    let data = {
                        _token: "{{ csrf_token() }}",
                        start:this.getDate,
                        end:this.getDate1
                    };
                    this.$http.post('{{ route('ledger.api_change_list') }}', data).then(function (response) {
                        if (response.body.status === "success") {
                            layer.load(2, {shade: false, time: 500});

                            // 主列表数据
                            this.ledgerData = response.body.data.list;

                            //this.km_option = response.body.data.km_option;
                            let v_options = response.body.data.km_option;
                            let html = '<option value="">=== 科目 ===</option><option value="all">全部科目</option>';
                            for (i in v_options) {
                                html = html + "<option value=" + v_options[i].num + ">" + v_options[i].v_name + "</option>";
                            }
                            $("#subject").html(html);
                            layui.form.render();

                            $('#subject').siblings("div.layui-form-select").find('dl').find('dd[lay-value=""]').click();
                        } else {
                            layer.msg(response.body.msg, {icon: 2, time: 1000});
                        }
                    }).then(function(){
                        computedHeight()
                    })
                },
                /*------ 打印 delay ---------*/
                doPrinting:function(){
                    //layer.msg("打印功能暂缓开发!", {icon: 2, time: 2000});
                    let start = this.getDate;
                    let end = this.getDate1;
                    layer.confirm('确定要打印总账信息吗？', {icon: 3, title: '提示',skin: 'ledgerAlert'},
                        function () {
                            layer.closeAll();
                            //let pdf_link = "/book/ledger/print?ids=all&start="+start+"&end="+end;
                            //window.open(pdf_link);
                            window.open("/book/ledger/print?ids=all&start="+start+"&end="+end);
                        }
                    );
                },
                /*------ 下拉筛选 ok ---------*/
                changeList:function(item){
                    alert('000');
                },
                /*------ 刷新页面 ok ---------*/
                doRefresh:function(){
                    this.doSubmitBtn();
                },
                /*------ 导出excel doing ----------*/
                doExport: function () {
                    let start = this.getDate;
                    let end = this.getDate1;
                    layer.confirm('确定要导出总账信息吗？', {icon: 3, title: '提示',skin: 'ledgerAlert'},
                        function () {
                            layer.closeAll();
                            //window.location.href = "{{ route('ledger.export', ['ids'=>'all']) }}";
                            window.location.href = "/book/ledger/export?ids=all&start="+start+"&end="+end;
                        }
                    );
                },
                /*-------   获取页面加载数据 ok   -------------*/
                getPageInfo:function() {
                    let data = {
                        _token: "{{ csrf_token() }}"
                    };
                    this.$http.post('{{ route('ledger.api_list') }}', data).then(function (response) {
                        if (response.body.status === "success") {
                            layer.load(2, {shade: false, time: 500});

                            // 会计期间选择 相关
                            this.date = response.body.data.belong_time;
                            this.getDate = response.body.data.belong_time;
                            this.getDate1 = response.body.data.belong_time;
                            this.kmyeOption = response.body.data.qj_options;

                            // 主列表数据
                            this.ledgerData = response.body.data.list;

                            // 列表包含科目
                            //this.km_option = response.body.data.km_option;
                            let v_options = response.body.data.km_option;
                            let html = '<option value="">=== 科目 ===</option><option value="all">全部科目</option>';
                            for (i in v_options) {
                                html = html + "<option value=" + v_options[i].num + ">" + v_options[i].v_name + "</option>";
                            }
                            $("#subject").html(html);
                            layui.form.render();
                        } else {
                            layer.msg(response.body.msg, {icon: 2, time: 1000});
                        }
                    }).then(function(){
                        computedHeight()
                    })
                }
            }
        })
    </script>
@endsection