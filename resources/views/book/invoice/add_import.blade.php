@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/invoice.css?=2018082301">
@endsection

@section('content')
    <div class="invoiceWrapper" v-cloak>
        <div class="invoice">
            <div class="invoiceTitle" ref="titleType">
                <div class="invoiceHead" @click="codeList = !codeList">
                    <span class="titleTop">@{{getDate}}</span>
                    <i class="el-icon-edit"></i>
                </div>
                <ul class="showTitle" v-show="codeList">
                    <li v-for="item in options" :key="item.index" @click="getNewAdds(item.label)">
                        @{{item.label}}
                    </li>
                </ul>
            </div>
            <div class="invoiceInput">
                <div class="invoiceCode">
                    <label>发票代码:</label>
                    <input type="text" class="code" v-model="reqParams.fpdm">
                </div>
                <div class="invoiceCode">
                    <label>发票号码:</label>
                    <input type="text" class="code" v-model="reqParams.fphm">
                </div>
            </div>
            <div class="Radio">
                <div class="invoiceRadio">
                    <label><input type="radio" name="renzheng" checked disabled>本期认证</label>
                    <label><input type="radio" name="renzheng" disabled>暂不认证</label>
                </div>
                <div class="invoiceDate">
                    <label>开票日期:</label>
                    <div class="calendar">
                        <input type="text" id="date" readonly>
                        <span class="icon iconfont" id="dataEle">&#xe61f;</span>
                    </div>
                </div>
            </div>
            <div class="invoiceTable">
                <table cellspacing="0" border="0" style="border-collapse:collapse;" class="topTable">
                    <tbody>
                    <tr>
                        <td rowspan="4" width="26" style="border:none"></td>
                        <td rowspan="4" width="55" style="text-align: center" class="border-left border-top">销<br>方<br>单<br>位</td>
                        <td width="132" class="center border-top">名称 <span class="must"></span></td>
                        <td width="410" class="border-top">
                            <div class="select_box" ref="nameType">
                                <input name="xfdw_id" type="hidden" v-model="reqParams.xfdw_id">
                                <input name="xfdw_name" type="hidden" v-model="reqParams.xfdw_name">
                                <input class="selectText" :value="invoiceName" @focus="showUnit" v-model="invoiceName">
                                <ul class="select_ul" v-show="invoiceNameShow">
                                    <li v-for="nameList in list" :key="nameList" @click="getinvoiceName(nameList)">
                                        <em>@{{nameList.value}}</em>
                                        <span>@{{nameList.label}}</span>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td width="120" class="center border-top">
                            <div v-if="otherMenu">
                                抵扣状态 <span class="must"></span>
                            </div>
                        </td>
                        <td width="367" class="border-top">
                            <div style="padding-left: 4px" v-if="otherMenu">
                                <label>
                                    <input type="radio" name="kou" value="{{ \App\Entity\Invoice::DKZT_BQDK }}" v-model="reqParams.dkzt">
                                    本期抵扣
                                </label>
                                <label>
                                    <input type="radio" name="kou" value="{{ \App\Entity\Invoice::DKZT_NO }}" v-model="reqParams.dkzt">
                                    不予抵扣
                                </label>
                            </div>
                        </td>
                        <td rowspan="4" width="26" class="border-right border-top"></td>
                    </tr>
                    <tr>
                        <td class="center">纳税人识别号</td>
                        <td>
                            <input v-model="reqParams.xfdw_nsrsbh" type="text" class="textTitle" ref="addressTel" :disabled="typeDis == 'receiveNumberEdit'">
                        </td>
                        <td class="center">
                            <div v-if="otherMenu">
                                抵扣方式 <span class="must"></span>
                            </div>
                        </td>
                        <td>
                            <div style="padding-left: 4px" v-if="otherMenu">
                                <label>
                                    <input type="radio" name="project" value="{{ \App\Entity\Invoice::DKFS_YBXM }}" v-model="reqParams.dkfs">
                                    一般项目
                                </label>
                                <label>
                                    <input type="radio" name="project" value="{{ \App\Entity\Invoice::DKFS_JZJT }}" v-model="reqParams.dkfs">
                                    即征即退
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="center">地址、电话</td>
                        <td><input type="text" class="textTitle" v-model="reqParams.xfdw_dzdh"></td>
                        <td rowspan="2" class="center">备注</td>
                        <td rowspan="2"><textarea placeholder="在此输入凭证摘要" class="textT" v-model="reqParams.remark"></textarea></td>
                    </tr>
                    <tr>
                        <td class="center">开户行及帐号</td>
                        <td><input type="text" class="textTitle" v-model="reqParams.xfdw_yhzh"></td>
                    </tr>
                    </tbody>
                </table>
                <table cellspacing="0" border="0" style="border-collapse:collapse;" class="invoiceTable2" ref="invoiceTable2">
                    <thead>
                    <tr>
                        <th width="26" class="border-bottomLeft"></th>
                        <th width="220">业务类型<span class="must"></span></th>
                        <th width="260">开票项目</th>
                        <th width="72">规格型号</th>
                        <th width="60">单位</th>
                        <th width="160" ref="invoiceNum">数量</th>
                        <th width="80" class="addedTax" v-if="otherMenu" ref="invoiceMoney">金额 <span class="must"></span></th>
                        <th width="160" class="customsEntry" v-show="customsEntry">完税价格 <span class="must"></span></th>
                        <th width="50" v-show="otherInvoice">税率<span class="must"></span></th>
                        <th width="77" v-show="otherInvoice">税额<span class="must"></span></th>
                        <th width="105" class="addedTax" v-show="addedTax" ref="invoicePriceTotal">价税合计 <span class="must"></span></th>
                        <th width="26" class="border-bottom"></th>
                    </tr>
                    </thead>
                    <tbody ref="invoiceTable" id="myTableProduct">
                    <tr v-for="(item,index) in tablesDate" :key="item">
                        <td width="26" class="border-bottomLeft">
                            <div class="addLine oparate">
                                <a href="javascript:;" class="icon iconfont" title="增加分录1" @click="addUp(index)">&#xe615;</a>
                                <a href="javascript:;" class="icon iconfont" title="增加分录2" @click="addDown(index)">&#xe614;</a>
                            </div>
                        </td>
                        <td>
                            <div class="select_box curList">
                                <input class="selectText" :value="item.ywMaterial" @focus="item.yw = true">
                                <ul class="select_ul" v-show="item.yw">
                                    <li v-for="yw in ywOptions" @click="getYw(yw,item)">
                                        <em>@{{yw.name}}</em>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="textD select_box curList">
                                <input type="text" class="textbox selectText" :disabled="item.tickDisabled" @focus="item.ticket = true" v-model="item.ticketVal">
                                <ul class="select_ul" v-show="item.ticket">
                                    <li v-for="ticketList in item.ticketOptions" @click="getTicketList(ticketList,item)">
                                        <em>@{{ticketList.name}}</em>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="text-input" v-model="item.ggxh" style="color:#333">
                        </td>
                        <td>
                            <input type="text" class="text-input" v-model="item.dw" style="color:#333">
                        </td>
                        <td>
                            <input type="text" class="text-input" v-model="item.num">
                        </td>
                        <td v-if="otherMenu">
                            <input type="text" class="text-input mustMoney" :disabled="typeMoney == 'receiveMoney'" :value="item.money" @blur="mustMoney($event,index,item)">
                        </td>
                        <td v-show="customsEntry">
                            <input type="text" class="text-input">
                        </td>
                        <td v-show="otherInvoice">
                            <div class="curList">
                                <input type="text" class="text-input" :value="item.rate" @focus="item.ratesTotal = true">
                                <div class="select_box">
                                    <ul class="select_ul" v-show="item.ratesTotal">
                                        <li class="" v-for="ratesList in rateOptions" :key="ratesList" @click="getRateData(ratesList,item,index)">
                                            <em>@{{ratesList}}</em>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td v-show="otherInvoice">
                            <input type="text" class="text-input rateMoney" :value="item.rateMoneyLine" v-model="item.rateMoneyLine" @blur="computedRate">
                        </td>
                        <td v-show="addedTax">
                            <input type="text" class="text-input priceTotal" :disabled="typePrice=='receivePrice'" :value="item.ratePriceLine">
                        </td>
                        <td width="26" class="border-bottom">
                            <div class="deleLine oparate">
                                <a href="javascript:;" class="icon iconfont iconD" title="删除分录" @click="delTableLine(index)">&#xe605;</a>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td width="26" class="border-bottomLeft"></td>
                        <td colspan="5" class="center">合计</td>
                        <td v-if="otherMenu" ref="totalPrice" class="totalPriceafter">@{{moneyTotal}}</td>
                        <td v-show="customsEntry"></td>
                        <td v-show="otherInvoice"></td>
                        <td v-show="otherInvoice" class="totalRateAfter" ref="totalRateAfter">@{{rateColTotal}}</td>
                        <td class="priceAfter" ref="priceAfter" v-show="addedTax">@{{priceColTotal}}</td>
                        <td v-show="addedTax" class="border-bottom"></td>
                        <!-- <td width="26" ></td>-->
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="numPage">
                <label>发票张数:</label>
                <input type="text" v-model="reqParams.fpzs">
            </div>
        </div>
        <div class="keep">
            <a href="javascript:;" class="keepBtn" @click="saveAdd">保存并新增</a>
            <a href="javascript:;" class="keepBtn1" @click="save">保存</a>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
        new Vue({
            'el': ".invoiceWrapper",
            data: {
                data: '',
                searchVal:'',
                invoiceName: '',
                invoiceNameShow: false,
                getDate: '{{\App\Entity\Invoice::$subTypeLabels[\App\Entity\Invoice::SUB_TYPE_IMPORT_ZZSZYFP]  }}',
                disabled: false,
                codeList: false,
                addedTax: true,
                otherMenu: true,
                typeDis: false,
                customsEntry: false,
                otherInvoice: true,
                useClick: true,
                typeMoney: false,
                selectVal: '',
                ratesTotal: false,
                typePrice: 'receivePrice',
                moneyTotal: '',
                rateColTotal: '',
                priceColTotal: '',
                options: [
                    {
                        value: '1',
                        label: '增值税专用发票'
                    },
                    {
                        value: '2',
                        label: '海关进口增值税专用缴款通知书'
                    },
                    {
                        value: '3',
                        label: '农产品发票'
                    },
                    {
                        value: '4',
                        label: '其他发票（可抵扣）'
                    },
                    {
                        value: '5',
                        label: '其他发票'
                    }
                ],
                invoiceNameOption: JSON.parse('{!!  json_encode(\App\Entity\Invoice::dwList())   !!}'),
                rateOptions: [0.03, 0.17, 0.31, 0.44, 0.78],
                ticketOptions: ['123', '123'],
                ywOptions: [],
                tablesDate: [
                    {
                        name: 1,
                        ggxh: '',
                        dw: '',
                        num: '',
                        money: '',
                        rate: '',
                        ticketVal: '',
                        ywMaterial: '',
                        rateMoneyLine: '',
                        ratePriceLine: '',
                        ratesTotal: false,
                        yw: false,
                        ticket: false,
                        tickDisabled: true,
                    },
                ],
                reqParams: {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id  }}',
                    sub_type: '{{ \App\Entity\Invoice::SUB_TYPE_IMPORT_ZZSZYFP  }}',
                    type: '{{ \App\Entity\Invoice::TYPE_IMPORT  }}',
                    dkzt: '{{ \App\Entity\Invoice::DKZT_NO }}',
                    dkfs: '{{ \App\Entity\Invoice::DKFS_YBXM }}',
                    fpzs: 1
                },
            },
            created() {
                /*---layui---*/
                layui.use(['laydate', 'form'], function () {
                    var form = layui.form;
                    var laydate = layui.laydate;
                    laydate.render({
                        elem: '#date'
                        , eventElem: '#dataEle'
                        , trigger: 'click'
                        , value: new Date()
                    })
                });
                this.ywOptions = JSON.parse('{!! json_encode(\App\Entity\Invoice::ywItem(\App\Entity\Invoice::TYPE_IMPORT)) !!}');
                //console.log(this.ywOptions);
            },
            mounted: function () {
                this.clickBlank()
            },
            methods: {
                /*------------名称下拉列表----------*/
                showUnit:function(){
                    if(this.invoiceNameOption.length <=0){
                        layer.open({
                            type: 1,
                            skin: 'unitAlert', //样式类名
                            anim: 2,
                            shadeClose: true, //开启遮罩关闭
                            content: '<div class="unitTips">单位为空,请在业务数据下添加</div>',
                            btn: ['确定','取消']
                        });
                        return;
                    }
                    this.invoiceNameShow = true
                },
                /*---------税额失去焦点重新计算价税合计税额总与价税合计总-------*/
                computedRate:function(){
                    //每行的金额、每行的税额rateMoneyLine、每行价税合计ratePriceLine、每列的税额总额rateColTotal与价税合计总额priceColTotal
                    var ratePriceLine = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    for (var i in this.tablesDate) {
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                    }
                    if (rateColTotal == 0) {
                        rateColTotal = ''
                    }
                    if (priceColTotal == 0) {
                        priceColTotal = ''
                    }
                    var _this = this;
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2)

                },
                //点击空白处相应div隐藏
                clickBlank: function () {
                    /*--发票类型titleType，名称nameType,业务类型、税目、开票项目*/
                    var titleType = this.$refs.titleType;
                    var nameType = this.$refs.nameType;
                    var _this = this;
                    document.addEventListener('click', function (e) {
                        if (!titleType.contains(e.target)) {
                            _this.codeList = false;
                        }
                        if (!nameType.contains(e.target)) {
                            _this.invoiceNameShow = false;
                        }
                    });
                    $(document).click(function (event) {
                        var _con = $('.curList');  // 设置目标区域
                        if (!_con.is(event.target) && _con.has(event.target).length === 0) { // Mark 1
                            for (var i in _this.tablesDate) {
                                _this.tablesDate[i].yw = false;
                                _this.tablesDate[i].sm = false;
                                _this.tablesDate[i].kpMenu = false;
                                _this.tablesDate[i].ticket = false;
                                _this.tablesDate[i].ratesTotal = false;
                            }
                        }

                    });
                },
                /*----增加上行----*/
                addUp: function (index) {
                    this.tablesDate.splice(index, 0, {
                        name: 1,
                        num: '',
                        money: '',
                        rate: '',
                        ticketVal: '',
                        ywMaterial: '',
                        rateMoneyLine: '',
                        ratePriceLine: '',
                        ratesTotal: false,
                        yw: false,
                        ticket: false,
                        tickDisabled: true,
                    });
                },
                /*----增加下行-------*/
                addDown: function (index) {
                    this.tablesDate.splice((index + 1), 0, {
                        name: 1,
                        num: '',
                        money: '',
                        rate: '',
                        ticketVal: '',
                        ywMaterial: '',
                        rateMoneyLine: '',
                        ratePriceLine: '',
                        ratesTotal: false,
                        yw: false,
                        ticket: false,
                        tickDisabled: true,
                    });
                },
                /*----不同发票的选择title----*/
                getNewAdds: function (value) {
                    this.codeList = false;
                    this.getDate = value;
                    /*不同的发票table的显示不同*/
                    /*addedTax-增值税--otherInvoice-其他（可抵扣）-otherMenu-其他---customsEntry-海关---typeDis(disabled)-第一个table的地址电话---invoiceNum-数量宽度---invoicePriceTotal-完税总价宽度*/
                    /*--typeMoney--金额的disabled---typePrice---价税合计的disabled*/
                    switch (value) {
                        case '{{\App\Entity\Invoice::$subTypeLabels[\App\Entity\Invoice::SUB_TYPE_IMPORT_ZZSZYFP]  }}':
                            var invoiceNum = this.$refs.invoiceNum.style.width = 160 + 'px';
                            var invoicePriceTotal = this.$refs.invoicePriceTotal.style.width = 105 + 'px';
                            this.addedTax = true;
                            this.otherInvoice = true;
                            this.otherMenu = true;
                            this.customsEntry = false;
                            this.typeDis = false;
                            this.typeMoney = false;
                            this.typePrice = 'receivePrice';
                            this.reqParams.subType = '{{ \App\Entity\Invoice::SUB_TYPE_IMPORT_ZZSZYFP  }}';
                            this.break;
                        //case '海关进口增值税专用缴款通知书' :
                        case '{{\App\Entity\Invoice::$subTypeLabels[\App\Entity\Invoice::SUB_TYPE_IMPORT_HGJKZZS]  }}':
                            var invoiceNum = this.$refs.invoiceNum.style.width = 185 + 'px';
                            var invoicePriceTotal = this.$refs.invoicePriceTotal.style.width = 105 + 'px';
                            this.addedTax = false;
                            this.otherMenu = false;
                            this.customsEntry = true;
                            this.otherInvoice = true;
                            this.typeDis = 'receiveNumberEdit';
                            this.reqParams.subType = '{{ \App\Entity\Invoice::SUB_TYPE_IMPORT_HGJKZZS  }}';
                            break;
                        //case '农产品发票' :
                        case '{{\App\Entity\Invoice::$subTypeLabels[\App\Entity\Invoice::SUB_TYPE_IMPORT_NCPFP]  }}':
                            var invoiceNum = this.$refs.invoiceNum.style.width = 160 + 'px';
                            var invoicePriceTotal = this.$refs.invoicePriceTotal.style.width = 105 + 'px';
                            this.addedTax = true;
                            this.otherMenu = true;
                            this.customsEntry = false;
                            this.otherInvoice = true;
                            this.typeDis = false;
                            this.typePrice = false;
                            this.typeMoney = 'receiveMoney';
                            this.reqParams.subType = '{{ \App\Entity\Invoice::SUB_TYPE_IMPORT_NCPFP  }}';
                            break;
                        //case '其他发票（可抵扣）' :
                        case '{{\App\Entity\Invoice::$subTypeLabels[\App\Entity\Invoice::SUB_TYPE_IMPORT_QTFP_KDK]  }}':
                            var invoiceNum = this.$refs.invoiceNum.style.width = 160 + 'px';
                            var invoicePriceTotal = this.$refs.invoicePriceTotal.style.width = 105 + 'px';
                            this.addedTax = true;
                            this.otherMenu = true;
                            this.customsEntry = false;
                            this.otherInvoice = true;
                            this.typeDis = false;
                            this.typeMoney = false;
                            this.typePrice = 'receivePrice';
                            this.reqParams.subType = '{{ \App\Entity\Invoice::SUB_TYPE_IMPORT_QTFP_KDK  }}';
                            break;
                        //case '其他发票' :
                        case '{{\App\Entity\Invoice::$subTypeLabels[\App\Entity\Invoice::SUB_TYPE_IMPORT_QTFP]  }}':
                            var invoicePriceTotal = this.$refs.invoicePriceTotal.style.width = 312 + 'px';
                            this.addedTax = true;
                            this.otherMenu = false;
                            this.customsEntry = false;
                            this.otherInvoice = false;
                            this.typeDis = false;
                            this.typePrice = false;
                            this.reqParams.subType = '{{ \App\Entity\Invoice::SUB_TYPE_IMPORT_QTFP  }}';
                            break;
                    }
                },

                delTableLine: function (index) {
                    /*--删除---最后一条的提示*/
                    let num = this.$refs.invoiceTable.rows.length;
                    if (num > 1) {
                        this.tablesDate.splice(index, 1);
                        this.$options.methods.allTotal.bind(this)();
                    } else {
                        alert("至少保留一条明细")
                    }
                },
                /*---税率的选择---*/
                getRateData: function (ratesList, item,index) {
                    item.rate = ratesList;
                    item.ratesTotal = false;
                    //每行税额、每行价税合计、金额总额、税额总额、价税合计总额
                    var rateline = 0;
                    var ratePriceLine = 0;
                    var moneyTotal = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    for (var i in this.tablesDate) {
                        this.tablesDate[index].rateMoneyLine = (Number(this.tablesDate[index].money) * Number(this.tablesDate[index].rate)).toFixed(2);
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        moneyTotal += Number(this.tablesDate[i].money);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                    }
                    if (moneyTotal == 0) {
                        moneyTotal = ''
                    }
                    if (rateColTotal == 0) {
                        rateColTotal = ''
                    }
                    if (priceColTotal == 0) {
                        priceColTotal = ''
                    }
                    var _this = this;
                    _this.moneyTotal = Number(moneyTotal).toFixed(2);
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2);
                    //this.$options.methods.allTotal.bind(this)();
                },

                //选择业务类型
                getYw: function (yw, item) {
                    item.ywMaterial = yw.name;
                    item.yw = false;
                    item.yw_id = yw.number;
                    item.ticketOptions = [];

                    if (yw.child instanceof Array && yw.child.length != 0) {
                        item.ticketOptions = yw.child;
                        item.ticket = true;
                    } else {
                        item.ticketVal = '';
                        item.ticket = false;
                    }


                    item.account_name = yw.name;
                    item.account_number = yw.number;

                    if (item.ywMaterial) {
                        item.tickDisabled = false
                    }
                },

                getinvoiceName: function (nameList) {
                    this.invoiceName = nameList.value;
                    this.invoiceNameShow = false;
                    this.reqParams.xfdw_id = nameList.id;
                    this.reqParams.xfdw_name = nameList.value;

                },

                //选择开票项目
                getTicketList: function (ticketList, item) {
                    //console.log(ticketList);

                    item.ticketVal = ticketList.name;
                    if (ticketList.asset_id != '' && ticketList.asset_id != undefined) {
                        item.asset_id = ticketList.asset_id;
                        item.ticketVal_id = ticketList.asset_id;
                    } else {
                        item.ticketVal_id = ticketList.number;
                        item.account_name = ticketList.name;
                        item.account_number = ticketList.number;
                    }
                    item.ticket = false;
                },
                toDecimal: function (x) {
                    var f = parseFloat(x);
                    if (isNaN(f)) {
                        return "";
                    }
                    var f = Math.round(x * 100) / 100;
                    var s = f.toString();
                    var rs = s.indexOf('.');
                    if (rs < 0) {
                        rs = s.length;
                        s += '.';
                    }
                    while (s.length <= rs + 2) {
                        s += '0';
                    }
                    return s;
                },
                //计算发票总数
                allTotal: function () {
                    //每行税额、每行价税合计、金额总额、税额总额、价税合计总额
                    var rateline = 0;
                    var ratePriceLine = 0;
                    var moneyTotal = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    for (var i in this.tablesDate) {
                        this.tablesDate[i].rateMoneyLine = (Number(this.tablesDate[i].money) * Number(this.tablesDate[i].rate)).toFixed(2);
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        moneyTotal += Number(this.tablesDate[i].money);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                    }
                    if (moneyTotal == 0) {
                        moneyTotal = ''
                    }
                    if (rateColTotal == 0) {
                        rateColTotal = ''
                    }
                    if (priceColTotal == 0) {
                        priceColTotal = ''
                    }
                    var _this = this;
                    _this.moneyTotal = Number(moneyTotal).toFixed(2);
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2)
                },
                //金额失去焦点
                mustMoney: function (e, index, item) {
                    var target = event.target || window.event.srcElement;

                    function toDecimal(x) {
                        var f = parseFloat(x);
                        if (isNaN(f)) {
                            return "";
                        }
                        var f = Math.round(x * 100) / 100;
                        var s = f.toString();
                        var rs = s.indexOf('.');
                        if (rs < 0) {
                            rs = s.length;
                            s += '.';
                        }
                        while (s.length <= rs + 2) {
                            s += '0';
                        }
                        return s;
                    }

                    item.money = toDecimal($(e.target).val());
                    //每行税额、每行价税合计、金额总额、税额总额、价税合计总额
                    var rateline = 0;
                    var ratePriceLine = 0;
                    var moneyTotal = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    for (var i in this.tablesDate) {
                        this.tablesDate[index].rateMoneyLine = (Number(this.tablesDate[index].money) * Number(this.tablesDate[index].rate)).toFixed(2);
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        moneyTotal += Number(this.tablesDate[i].money);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                    }
                    if (moneyTotal == 0) {
                        moneyTotal = ''
                    }
                    if (rateColTotal == 0) {
                        rateColTotal = ''
                    }
                    if (priceColTotal == 0) {
                        priceColTotal = ''
                    }
                    var _this = this;
                    _this.moneyTotal = Number(moneyTotal).toFixed(2);
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2);
                    //this.$options.methods.allTotal.bind(this)();
                },

                /*---保存---*/
                save: function (reload) {
                    this.reqParams.kprq = $('#date').val();

                    var items = [];
                    var item_data = this.tablesDate;

                    //console.log((String)(this.reqParams.xfdw_id))
                    //console.log(this.reqParams.xfdw_name)

                    if (this.reqParams.xfdw_id == undefined && this.reqParams.xfdw_name == undefined) {
                        layer.msg('请选择单位', {icon: 2, time: 2000});
                        return false;
                    }

                    for (var i in item_data) {

                        if (item_data[i]['ywMaterial'] == '' && item_data[i]['money'] == '' || item_data[i]['rate'] == '') {
                            continue;
                        }

                        //校验单条数据完整性
                        if (item_data[i]['ywMaterial'] != '' && item_data[i]['money'] == '' || item_data[i]['rate'] == '') {
                            //console.log(item_data[i]);
                            //console.log(item_data[i]['ywMaterial']);
                            //console.log(item_data[i]['money']);
                            //console.log(item_data[i]['rate']);
                            layer.msg('请完善业务类型' + item_data[i]['ywMaterial'] + '对应的金额和税率', {icon: 2, time: 3000});
                            return false;
                        }

                        //填充数据
                        if (item_data[i]['ywMaterial'] != '' && item_data[i]['money'] != '' && item_data[i]['rate'] != '') {
                            items.push({
                                ywlx_id: String(item_data[i]['account_number']),
                                ywlx_name: String(item_data[i]['ywMaterial']),
                                kpxm_id: String(item_data[i]['ticketVal_id']),
                                kpxm_name: String(item_data[i]['ticketVal']),
                                ggxh: (item_data[i]['ggxh']),
                                dw: (item_data[i]['dw']),
                                num: String(item_data[i]['num']),
                                money: String(item_data[i]['money']),
                                tax_rate: String(item_data[i]['rate']),
                                tax_money: String(item_data[i]['rateMoneyLine']),
                                fee_tax_sum: String(item_data[i]['ratePriceLine']),
                                account_name: String(item_data[i]['account_name']),
                                account_number: String(item_data[i]['account_number']),
                            });
                        }
                    }

                    if (items.length == 0) {
                        layer.msg('有效数据不能少于1行', {icon: 2, time: 2000});
                        return false;
                    }

                    this.reqParams.items = JSON.stringify(items);
                    this.reqParams._token = '{{csrf_token()}}';
                    this.$http.post('{{ url('book/invoice/create') }}', this.reqParams).then(function (response) {
                        var status = response.body.status == 1 ? 1 : 2;
                        layer.msg(response.body.info, {icon: status, time: 3000});
                        setTimeout(function () {
                            if (reload == true && response.body.status == 1) {
                                location.href = '{{ url('book/invoice/addImport')  }}';
                            } else if (response.body.status == 1) {
                                //removeIframe();
                                //parent.creatIframe('{{ url('book/invoice/import')  }}', '进项');
                                location.href = '{{ url('book/invoice/editImport')  }}/' + response.body.data.id;
                            } else {
                                var msg = '网络异常，请稍后再试';
                                layer.msg(msg, {icon: status, time: 3000});
                            }
                        }, 1000);

                    });
                },
                /*---保存并新增---*/
                saveAdd: function () {
                    this.save(true);
                }
            },
            //通过计算属性过滤数据
            computed:{
                list: function(){
                    var _this = this;
                    var arrByZM = [];
                    for (var i=0;i<this.invoiceNameOption.length;i++){
                        if(this.invoiceNameOption[i].value.search(this.invoiceName) != -1){
                            arrByZM.push(this.invoiceNameOption[i]);
                        }
                    }
                    return arrByZM;
                }
            }
        })
    </script>
@endsection