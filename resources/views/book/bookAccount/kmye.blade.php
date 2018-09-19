@extends('book.layout.base')

@section('css')
    @parent
    <!--科目余额-->
    <link rel="stylesheet" href="/css/book/cwcl/subjectBlance.css?v=2018090401">
    <link rel="stylesheet" href="{{asset("css/agent/zTreeStyle.css")}}" type="text/css">
@endsection

@section('content')
    <div class="subjectBalance" v-cloak>
        <div>
            <div class="subjectLeft layui-form">
                <div class="subject-item">
                    <select name="subject" lay-filter="subject">
                        <option>科目余额表</option>
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
                                        <li v-for="item in kmyeOption" :key="item.index" @click="getStart(item)">
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
                                        <li v-for="item in kmyeOption" :key="item.index" @click="getEnd(item)">
                                        @{{item.label}}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="subjectBtn">
                            <a href="javascript:;" class="sureBtn" @click="submitBtn">确定</a>
                        </div>
                    </form>
                </div>
                {{--<div class="subjectRefresh">
                    <i class="iconfont" @click="doRefresh">&#xe60a;</i>
                </div>--}}
                <div  class="hiddenYear">
                    <a href="javascript:;" @click="showCurYear($event)" class="curYear">隐藏本年累计</a>
                </div>
            </div>
            <div class="subjectRight">
                <a href="javascript:;" class="export" @click="kmyeExport">导出</a>
                {{--<a href="javascript:;">打印</a>--}}
            </div>
        </div>
        <div class="subjectTable subjectTable1">
            <div class="subjectTableHead fixTableHeader">
                <table>
                    <tbody>
                    <tr>
                        <td rowspan="2" class="width10">科目编码</td>
                        <td rowspan="2" class="width18">科目名称</td>
                        <td colspan="2" class="width18">期初余额</td>
                        <td colspan="2" class="width18">本期发生额</td>
                        <td colspan="2" v-show="curYear" class="width18">本年累计发生额</td>
                        <td colspan="2" class="width18">期末余额</td>
                    </tr>
                    <tr>
                        <td class="width9">借方</td>
                        <td class="width9">贷方</td>
                        <td class="width9">借方</td>
                        <td class="width9">贷方</td>
                        <td v-show="curYear" class="width9">借方</td>
                        <td v-show="curYear" class="width9">贷方</td>
                        <td class="width9">借方</td>
                        <td class="width9">贷方</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="subjectTableBody tableScroll">
                <table>
                    <tr v-for="item in subjectTables" :key="item.id">
                        <td class="width10">
                            <a  class="invoiceCode" data-href="{{ route('sub_ledger.list') }}" data-title="明细账" href="javascript:void(0);" @click="showTz(item)" ref="curDom">@{{item.account_subject_number}}</a>
                        </td>
                        <td class="width18">@{{item.account_subject_name}}</td>
                        <td class="width9">@{{item.qcye_j!=0 ? item.qcye_j : '' }}</td>
                        <td class="width9">@{{item.qcye_d!=0 ? item.qcye_d : '' }}</td>
                        <td class="width9">@{{item.bqfse_j!=0 ? item.bqfse_j : '' }}</td>
                        <td class="width9">@{{item.bqfse_d!=0 ? item.bqfse_d : '' }}</td>
                        <td v-show="curYear" class="width9">@{{item.bnljfse_j!=0 ? item.bnljfse_j : '' }}</td>
                        <td v-show="curYear" class="width9">@{{item.bnljfse_d!=0 ? item.bnljfse_d : '' }}</td>
                        <td class="width9">@{{item.qmye_j!=0 ? item.qmye_j : '' }}</td>
                        <td class="width9">@{{item.qmye_d!=0 ? item.qmye_d : '' }}</td>
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
        var contentWrapper =  new Vue({
            'el': '.subjectBalance',
            data: {
                curYear:true,
                subjectForm:false,
                kmStart: '',
                subjectVal:'',
                kmEnd: '',
                kmjcStart: '',
                kmjcEnd: '',
                moneyTypeDate: '人民币',
                date: '2018年第6期',
                //会计期间
                kjqjStart: '2018年第2期',
                codeList:false,
                endDate: false,
                startkjqj:'',
                endkjqj:'',
                getDate: '2018年第6期',
                getDate1: '2018年第6期',
                getDateExport:'',
                getDate1Export:'',
                moneyTypes: false,
                kmyeOption: [
                ],
                moneyType:[
                    {
                        label: '人民币',
                        value:'0'
                    },
                    {
                        label: '日元',
                        value:'1'
                    },
                    {
                        label: '美元',
                        value:'2'
                    },
                    {
                        label: '欧元',
                        value:'3'
                    },
                    {
                        label: '港元',
                        value:'4'
                    },
                    {
                        label: '英镑',
                        value:'5'
                    },
                    {
                        label: '澳大利亚元',
                        value:'6'
                    },
                    {
                        label: '新加坡元',
                        value:'7'
                    }

                ],
                subjectTables:[]
            },
            created:function(){
                var _this = this;
                _this.getKmye();
                layui.use(['form','layer','jquery'],function(){
                    var form = layui.form;
                    form.on('select(subject)', function (data) {
                        var subjectVal = data.value;
                        if(subjectVal == '科目余额表'){
                            $('.subjectTable1').show();
                            $('.curYear').show();
                            $('.subjectTable2').hide();
                        }else{
                            $('.subjectTable1').hide();
                            $('.curYear').hide();
                            $('.subjectTable2').show();
                        }
                    });
                });
            },
            mounted:function(){
                this.clickBlank()
            },
            methods:{
                //点击编码跳转明细账
                showTz:function(item){
                    localStorage.setItem('km_code',item.account_subject_number);
                    localStorage.setItem('start',this.getDate);
                    localStorage.setItem('end',this.getDate1);
                    var curDom = this.$refs.curDom;
                    top.Hui_admin_tab(curDom)
                },
                /*-----------获取数据------*/
                getKmye:function(){
                    var kjqj = '{{ \App\Entity\Period::currentPeriod() }}';
                    var kjMonth = kjqj.split('-')[1];
                    if (kjMonth.indexOf(0) == '0') {
                        kjMonth = kjMonth.slice(1);
                    }
                    /*-----导出时带的参数--------*/
                    this.getDateExport = kjqj.split('-')[0] + '-' + kjMonth;
                    this.getDate1Export = kjqj.split('-')[0] + '-' + kjMonth;
                    /*--------会计期间的值--------*/
                    this.date = kjqj.split('-')[0] + '年第' + kjMonth + '期';
                    this.getDate =  this.date;
                    this.getDate1 = this.date;
                    this.$http.get('{{ route('subjectBalance.subjectBalanceList') }}').then(function(response){
                        this.subjectTables = response.body.data.result;
                        this.kmyeOption = response.body.data.qj_options;
                    }).then(function(){
                        computedHeight()
                    });
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
                //显示与隐藏本年数据
                showCurYear:function(e){
                    if(e.target.innerHTML == '隐藏本年累计'){
                        e.target.innerHTML = '显示本年累计'
                    }else{
                        e.target.innerHTML = '隐藏本年累计'
                    }
                    this.curYear = !this.curYear
                },
                //会计期间开始
                getStart:function(item) {
                    this.codeList = false;
                    this.getDate = item.label;
                    this.startkjqj = item.value;
                    this.getDateExport = this.startkjqj;
                },
                //会计期间结束
                getEnd:function(item) {
                    this.endDate = false;
                    this.getDate1 = item.label;
                    this.endkjqj = item.value;
                    this.getDate1Export = this.endkjqj
                },

                // 刷新页面
                doRefresh:function(){
                    this.submitBtn();
                },

                //确定按钮
                submitBtn:function(){
                    //var date =  $("#subjectForm").serialize();
                    //会计期间始getDate、会计期间末getDate1、始起始科目kmStart、结束科目kmEnd、科目级次始kmjcStart、科目级次末kmjcEnd、币别moneyType
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
                    var start1 = kjqjStart.substr(0, 4);
                    var start2 = kjqjStart.substr(4, kjqjStart.length);
                    kjqjStart = start1 + '-' + start2;
                   var end1 = kjqjEnd.substr(0, 4);
                   var end2 = kjqjEnd.substr(4, kjqjStart.length);
                    kjqjEnd = end1 + '-' + end2;
                    var data ={
                        startkjqj: kjqjStart,
                        endkjqj:kjqjEnd,
                    };
                    //根据需求请求数据
                    this.$http.get('{{ route('subjectBalance.subjectBalanceList') }}', {params : data}).then(function(response){
                        this.subjectTables = response.body.data.result;
                    }).then(function(){
                        computedHeight()
                    });
                },
                kmyeExport:function(){
                    let start = this.getDateExport;
                    let end = this.getDate1Export;
                    layer.confirm('确定要导出科目余额信息吗？', {icon: 3, title: '提示',skin: 'ledgerAlert'},
                            function () {
                                layer.closeAll();
                                window.location.href = "/book/subjectBalance/subjectBalanceList?startkjqj="+start+"&endkjqj="+end+"&export=1";
                            }
                    );
                }
            }
        })

    </script>
@endsection
