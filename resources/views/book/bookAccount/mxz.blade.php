@extends('book.layout.base')

@section('css')
    @parent
    <!--科目余额-->
    <link rel="stylesheet" href="{{asset("css/book/cwcl/subjectBlance.css?v=2018090401")}}">
    <link rel="stylesheet" href="{{asset("css/agent/zTreeStyle.css")}}" type="text/css">
@endsection

@section('content')
    <div class="subjectBalance" id="mxz" v-cloak>
        <div class="mxzTop">
            <div class="subjectLeft layui-form">
                <div class="subject-item">
                    <select name="subject" lay-filter="subject">
                        <option>科目余额表</option>
                        {{--<option style="display:none;">数量金额科目余额表</option>--}}
                    </select>
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
                                    <ul class="showKmye" v-show="endDate" id="showList" >
                                        <li v-for="item in kmyeOption" :key="item.index" @click="getEnd(item.label)">
                                        @{{item.label}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="subjectBtn">
                            {{--<a href="javascript:;" class="resetBtn" @click="resetData">重置</a>--}}
                            <a href="javascript:;" class="sureBtn" @click="doSubmitBtn">确定</a>
                        </div>
                    </form>
                </div>
                {{--<div class="subjectRefresh">
                    <i class="iconfont" @click="doRefresh">&#xe60a;</i>
                </div>--}}
            </div>
            {{--<div class="subjectRight">--}}
                {{--<a href="javascript:void(0);" @click="doPrinting">打印</a>--}}
                {{--<a href="javascript:void(0);" @click="doAutoPrinting">连续打印</a>--}}
            {{--</div>--}}
            <div class="subjectRight">
                <a href="javascript:void(0);" @click="doPrinting" class="print">单个打印</a>
                <a href="javascript:void(0);" @click="doAutoPrinting" class="print">连续打印</a>
            </div>
        </div>
        <div class="contentWrapper">
            <div class="mxzTable">
                <p class="text textBorder">科目: @{{kmText}}</p>
                <div class="mxzTableHead fixTableHeader">
                    <table>
                        <thead>
                        <tr>
                            <th class="width12">日期</th>
                            <th class="width12">凭证字号</th>
                            <th class="width20">摘要</th>
                            <th class="width16">借方</th>
                            <th class="width16">贷方</th>
                            <th class="width8">方向</th>
                            <th class="width16">余额</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="mxzTableBody tableScroll">
                    <table >
                        <tr v-for="item in mxzTables" :key="item.id">
                            <td class="width12">@{{item.date}}</td>
                            <td class="width12">
                                <a href="javascript:void(0);" class="invoiceCode" @click="showVoucher(item)">@{{item.marks}}</a>
                            </td>
                            <td class="width20">
                              <b v-if="item.px !== 'B'">@{{item.zy}}</b>
                                <span v-else>@{{item.zy}}</span>
                            </td>
                            <td class="width16">
                                <span v-if="item.debit == '0.00'" style="display: none;">@{{item.debit}}</span>
                                <span v-else>@{{item.debit}}</span>
                            </td>
                            <td class="width16">
                                <span v-if="item.credit == '0.00'" style="display: none;">@{{item.credit}}</span>
                                <span v-else>@{{item.credit}}</span>
                            </td>
                            <td class="width8">@{{item.direction}}</td>
                            <td class="width16">@{{item.ye}}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="rTree">
                <p class="text">科目: @{{kmText}}</p>
                <form class="layui-form mxzForm">
                    <select name="modules" lay-verify="required" lay-search="" lay-filter="as_select" id="as_select">
                        {!! $options !!}
                    </select>
                </form>
                <div>
                    <div class="officeTree treeHeight" ref="treeWrapper">
                        <ul id="treeLeft" class="ztree" @click="treeNode($event)" ></ul>
                    </div>
                </div>
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
        var contentWrapper =  new Vue({
            'el': '.subjectBalance',
            data: {
                kmText: '',
                subjectForm:false,
                kmStart: '',
                subjectVal:'',
                kmEnd: '',
                kmjcStart: '',
                kmjcEnd: '',
                moneyTypeDate: '人民币',
                date: '2018年第06期',
                //会计期间
                kjqjStart: '2017年第2期',
                codeList:false,
                endDate: false,
                getDate: '',
                getDate1: '',
                moneyTypes: false,
                kmyeOption: [],
                moneyType:[],
                mxzTables:[],
                /*----z-tree------*/
                setting: {
                    view: {
                        selectedMulti: false
                    },
                    edit: {
                        enable: true,
                        showRemoveBtn: false,
                        showRenameBtn: false
                    },
                    data: {
                        keep: {
                            parent: true,
                            leaf: true
                        },
                        simpleData: {
                            enable: true,
                            idKey: "id",
                            pIdKey: "pId",
                            rootPId: 0
                        }
                    },
                    callback: {
                        //onClick:this.zTreeOnClick
                    }
                },
                zNodes: [],
                km_id:''
            },
            created:function(){
                var _this = this;
                //_this.kmText = localStorage.getItem('km_code');
                layui.use(['form','jquery'],function(){
                    var form = layui.form;
                });

                /*-------- 右侧会计科目下拉选择相关切换效果  ok  -------------*/
                layui.use('form',function(){
                    layui.form.on('select(as_select)',function(data){
                        let select_value = data.value;
                        let select_name = data.elem[data.elem.selectedIndex].text;
                        if( select_value === ''){
                            layer.msg("未选择会计科目，请重新选择！", {icon: 2, time: 1000});
                            return false;
                        }
                        _this.kmText = 'loading...';
                        _this.$http.post('{{ route('sub_ledger.api_get_list') }}', {
                            _token: "{{csrf_token()}}",
                            as_id:select_value,
                            start:_this.getDate,
                            end:_this.getDate1
                        }).then(function (response) {
                            if (response.body.status === 'success') {
                                if(response.body.data.num >= 0){
                                    layer.load(2, {shade: false, time: 500});
                                    _this.mxzTables = response.body.data.items;
                                    _this.kmText = select_name;

                                    // 设置树形选中状态
                                    let treeObj = $.fn.zTree.getZTreeObj("treeLeft");
                                    let node = treeObj.getNodeByParam("id",select_value);
                                    treeObj.selectNode(node);
                                }else{
                                    layer.load(2, {shade: false, time: 500});
                                    _this.mxzTables = [];
                                    _this.kmText = select_name;
                                    layer.msg(response.body.msg, {icon: 2, time: 1000});
                                }

                                _this.km_id = select_value;

                                localStorage.setItem('km_code','');
                                localStorage.setItem('start','');
                                localStorage.setItem('end','');
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 1000});
                            }
                        }).then(function(){
                            treeHeight()
                            computedHeight()
                        })
                    })
                });
            },
            mounted: function(){
                this.getZtree();
                this.clickBlank();
                treeHeight()
                computedHeight();
            },
            methods:{
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
                    this.codeList = false;
                    this.getDate = value;
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
                /*-------  查看凭证  ok  -------------*/
                showTz:function(item){
                    let items = item.voucher_id;
                    localStorage.setItem('voucher_id',items);
                    let curDom = this.$refs.curDom;
                    top.Hui_admin_tab(curDom)
                },
                /*-- 查看凭证信息 ok ------*/
                showVoucher:function(item){
                    let voucher_id = item.voucher_id;
                    localStorage.setItem('voucher_id',voucher_id);
                    layer.open({
                        type: 2,
                        title: '凭证预览页面',
                        shadeClose: true,
                        shade: 0.2,
                        maxmin: true, //开启最大化最小化按钮
                        area: ['1200px', '96%'],
                        content: ['{{ url('book/voucher/edit') }}', 'yes'],
                    });
                },
                /*------ 单个打印 ok ---------*/
                doPrinting:function(){
                    let start = this.getDate;
                    let end = this.getDate1;
                    let km = this.km_id;
                    let km_txt = this.kmText;
                    layer.confirm('确定要打印科目( '+km_txt+' )的明细账信息吗？', {icon: 3, title: '提示',skin: 'ledgerAlert'},
                        function () {
                            layer.closeAll();
                            window.open("/book/sub_ledger/print?id="+km+"&start="+start+"&end="+end);
                        }
                    );
                },
                /*------ 连续打印 ok ---------*/
                doAutoPrinting:function(){
                    let start = this.getDate;
                    let end = this.getDate1;
                    layer.confirm('确定要打印当期所有发生业务的会计科目的明细账信息吗？', {icon: 3, title: '提示',skin: 'ledgerAlert'},
                        function () {
                            layer.closeAll();
                            window.open("/book/sub_ledger/print_all?start="+start+"&end="+end);
                        }
                    );
                },
                /*------ 刷新页面 ok ---------*/
                doRefresh:function(){
                    this.doSubmitBtn();
                },
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
                    $('#as_select').siblings("div.layui-form-select").find('dl').find('dd[class="layui-this"]').click();
                },
                /*-------   获取科目的节点 ok   -------------*/
                getZtree:function(){
                    let km_code_val = localStorage.getItem('km_code');
                    let start_val = localStorage.getItem('start');
                    let end_val = localStorage.getItem('end');
                    let data = {
                        _token: "{{ csrf_token() }}",
                        //km_code:"{{$km_code}}"
                        km_code:km_code_val
                    };
                    this.$http.post('{{ route('sub_ledger.api_list') }}', data).then(function (response) {
                        if (response.body.status === "success") {
                            layer.load(2, {shade: false, time: 500});
                            //this.mxzTables = response.body.data.km;
                            //this.kmText = response.body.data.text;

                            // 会计期间选择 相关
                            if(start_val != '' && end_val != ''){
                                this.date = start_val+'-'+end_val;
                                this.getDate = start_val;
                                this.getDate1 = end_val;
                            }else{
                                this.date = response.body.data.belong_time;
                                this.getDate = response.body.data.belong_time;
                                this.getDate1 = response.body.data.belong_time;
                            }

                            this.kmyeOption = response.body.data.qj_options;

                            this.zNodes = response.body.data.tree;
                            let nodeData = this.zNodes;
                            $.fn.zTree.init($("#treeLeft"), this.setting, nodeData);

                            // 更新页面数据
                            let as_id = response.body.data.km_id;
                            this.km_id = as_id;
                            $('#as_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + as_id + ']').click();
                        } else {
                            layer.msg(response.body.msg, {icon: 2, time: 1000});
                        }
                    })
                },
                /*-----节点对应的内容 ok -----*/
                treeNode:function(e){
                    let _this = this;
                    let target = e.target;
                    let select_value = target.id;
                    if(select_value.search("switch") != -1){
                        // 树形展开 折叠
                        return false;
                    }else{
                        _this.kmText = 'loading...';
                        _this.$http.post('{{ route('sub_ledger.api_get_id') }}', {
                            _token: "{{csrf_token()}}",
                            as_id:select_value
                        }).then(function (response) {
                            if (response.body.status === 'success') {
                                let as_id = response.body.data.as_id;
                                $('#as_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + as_id + ']').click();
                                //console.log(as_id);
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 1000});
                            }
                        })
                    }
                }
            }
        })
    </script>
@endsection