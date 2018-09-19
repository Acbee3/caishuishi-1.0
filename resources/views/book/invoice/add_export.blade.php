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
                    <i class="iconfont">&#xe61f;</i>
                </div>
                <ul class="showTitle" v-show="codeList">
                    <li v-for="item in options" :key="item.index" @click="getNewAdds(item)">
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
                <div class="invoiceRadio" style="display: none">
                    <label><input type="radio" name="renzheng" checked>本期认证</label>
                    <label><input type="radio" name="renzheng">暂不认证</label>
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
                        <td rowspan="4" width="55" style="text-align: center" class="border-left border-top">购<br>方<br>单<br>位</td>
                        <td width="132" class="center border-top">名称 <span class="must"></span></td>
                        <td width="410" class="border-top">
                            <div class="select_box" ref="nameType">
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
                            <div>
                                开票类型 <span class="must"></span>
                            </div>
                        </td>
                        <td width="367" class="border-top">
                            <div style="padding-left: 4px" v-if="otherMenu">
                                <label>
                                    <input type="radio" name="kou" v-model="reqParams.kplx" value="代开">
                                    代开
                                </label>
                                <label>
                                    <input type="radio" name="kou" v-model="reqParams.kplx" value="自开">
                                    自开
                                </label>
                            </div>
                        </td>
                        <td rowspan="4" width="26" class="border-right border-top"></td>
                    </tr>
                    <tr>
                        <td class="center">纳税人识别号</td>
                        <td>
                            <input type="text" v-model="reqParams.gfdw_nsrsbh" class="textTitle" ref="addressTel" :disabled="typeDis == 'receiveNumberEdit'">
                        </td>
                        <td class="center">
                            <div>
                                征收方式 <span class="must"></span>
                            </div>
                        </td>
                        <td>
                            <div style="padding-left: 4px">
                                <label v-show="jzjt">
                                    <input type="checkbox" value="即征即退" v-model="reqParams.zsfs">
                                    即征即退
                                </label>
                                <label v-show="ydzk">
                                    <input type="checkbox" value="异地自开" v-model="reqParams.zsfs">
                                    异地自开
                                </label>
                                <label v-show="ydzk">
                                    <input type="checkbox" value="差额征税" v-model="reqParams.zsfs" @change="askCezz">
                                    差额征税
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="center">地址、电话</td>
                        <td><input type="text" class="textTitle" v-model="reqParams.gfdw_dzdh"></td>
                        <td rowspan="2" class="center">备注</td>
                        <td rowspan="2">
                            <textarea placeholder="在此输入凭证摘要" class="textT" v-model="reqParams.remark"></textarea>
                            <div class="wb-content" v-show="wxBzAdd" ref="bzType">
                                <span>币种:</span>
                                <span v-text="selectBzVal" data-hl="0.36">无</span>
                                <i class="iconfont " @click="wxMenu = !wxMenu">&#xe600;</i>
                                <span class="tips">请选择外币币种</span>
                            </div>
                            <div class="bzType" v-show="wxMenu">
                                <p class="text">请选择币种</p>
                                <div class="selectBz">
                                    <label v-for="(item,index) in bzSelectOptions" :key="item">
                                        <input type="radio" name="bz" v-model="selectBzVal" :value="item.label" @change="selectRadio(item,index)">
                                        @{{item.label}}
                                    </label>
                                </div>
                                <div class="bz-bottom">
                                    <div class="choose-type" @click="addBzType">
                                        <i class="iconfont ">&#xe693;</i>
                                        <span>添加币种</span>
                                    </div>
                                    <div class="choose-btn">
                                        <a href="javascript:;" @click="wxMenu = false">关闭</a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="center">开户行及帐号</td>
                        <td><input type="text" class="textTitle"></td>
                    </tr>
                    </tbody>
                </table>
                <table cellspacing="0" border="0" style="border-collapse:collapse;" class="invoiceTable2" ref="invoiceTable2">
                    <thead>
                    <tr>
                        <th width="26" class="border-bottomLeft"></th>
                        <th width="180">业务类型 <span class="must"></span></th>
                        <th width="180">税目 <span class="must"></span></th>
                        <th width="220">开票项目</th>
                        <th width="72">规格型号</th>
                        <th width="60">单位</th>
                        <th width="80" ref="invoiceNum">数量</th>
                        <th v-show="wxxs">原币金额</th>
                        <th v-show="wxxs">汇率</th>
                        <th width="80" class="addedTax" ref="invoiceMoney" v-show="money">金额 <span class="must"></span></th>
                        <th width="80" class="addedTax" v-show="wxMoney">金额外销 <span class="must"></span></th>
                        <th width="50">税率<span class="must"></span></th>
                        <th width="77" v-show="hiddenProject">税额<span class="must"></span></th>
                        <th width="85" class="addedTax" v-show="hiddenProject" ref="invoicePriceTotal">价税合计 <span class="must"></span></th>
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
                                <input class="selectText" :value="item.ywMaterial" @focus="item.yw = true" v-model="item.ywMaterial">
                                <ul class="select_ul" v-show="item.yw">
                                    <li v-for="yw in ywOptions" @click="getYw(yw,item)">
                                        <em>@{{yw.name}}</em>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="select_box curList">
                                <input class="selectText" :value="item.smVal" @focus="item.sm = true">
                                <ul class="select_ul" v-show="item.sm">
                                    <li v-for="sm in smOptions" @click="getSm(sm,item,index)">
                                        <em>@{{sm.label}}</em>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <div class="textSm curList">
                                <input type="text" class="textbox selectText" :disabled="item.tickDisabled" :value="item.kpVal" @focus="item.kpMenu = true">
                                <div class="smWrapper" v-show="item.kpMenu">
                                    <ul class="smTab">
                                        <li v-for="(list,index) in smTitle" :key="list" :class="{activeSm:index===item.nowIndex}" @click="showTable(index,item)">@{{list}}</li>
                                    </ul>
                                    <div class="kpTotal">
                                        <div class="kpselect_box" v-show="item.nowIndex===0">
                                            <div class="kpSelect" @click="item.kpxm = !item.kpxm">
                                                <input class="">
                                                <i class="iconfont icon-xialazhishijiantou"></i>
                                            </div>
                                            <ul class="kpselect_ul" v-show="item.kpxm">
                                                <li v-for="kp in kpOptions" @click="getKp(kp,item)">
                                                    @{{kp}}
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="kpselect_box" v-show="item.nowIndex===1">
                                            <div class="kpSelect" @click="item.kpxm = !item.kpxm">
                                                <input class="">
                                                <i class="iconfont icon-xialazhishijiantou"></i>
                                            </div>
                                            <ul class="kpselect_ul" v-show="item.kpxm">
                                                <li v-for="kpfw in kpfwOptions" @click="getKpfw(kpfw,item)">
                                                    @{{kpfw}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="text-input" style="color:#333" :value="item.ggxh" v-model="item.ggxh">
                        </td>
                        <td>
                            <input type="text" class="text-input" style="color:#333" :value="item.dw" v-model="item.dw">
                        </td>
                        <td>
                            <input type="text" class="text-input" :value="item.num" v-model="item.num" @blur="numComputed(item)">
                        </td>
                        <td v-show="wxxs">
                            <input type="text" class="wxInfo" :value="item.ybje" v-model="item.ybje" @blur="ybComputed(item)">
                        </td>
                        <td v-show="wxxs">
                            <input type="text" class="wxInfo" disabled :value="item.hl" v-model="item.hl">
                        </td>
                        <td v-show="money">
                            <input type="text" class="text-input mustMoney" :disabled="typeMoney == 'receiveMoney'" :value="item.money" v-model="item.money" @blur="mustMoney($event,index,item)">
                        </td>
                        <td v-show="wxMoney">
                            <input type="text" class="text-input mustMoney" :disabled="typeMoney == 'receiveMoney'" :value="item.wxmoneyLine" v-model="item.wxmoneyLine">
                        </td>
                        <td>
                            <div class="curList">
                                <input type="text" class="text-input" :value="item.rate" @focus="item.ratesTotal = true" :disabled="typeRate == 'receiveRate'">
                                <div class="select_box">
                                    <ul class="select_ul" v-show="item.ratesTotal">
                                        <li class="" v-for="ratesList in rateOptions" :key="ratesList" @click="getRateData(ratesList,item)">
                                        <em>@{{ratesList}}</em>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td v-show="hiddenProject">
                            <input type="text" class="text-input rateMoney" :value="item.rateMoneyLine" v-model="item.rateMoneyLine" @blur="computedRate">
                        </td>
                        <td v-show="hiddenProject">
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
                        <td colspan="6" class="center">合计</td>
                        <td v-show="wxxs">@{{ybTotal}}</td>
                        <td v-show="wxxs"></td>
                        <td ref="totalPrice" class="totalPriceafter" v-show="money">@{{moneyTotal}}</td>
                        <td ref="totalPrice" class="totalPriceafter" v-show="wxMoney">@{{wxTotal}}</td>
                        <td></td>
                        <td class="totalRateAfter" ref="totalRateAfter" v-show="hiddenProject">@{{rateColTotal}}</td>
                        <td class="priceAfter" ref="priceAfter" v-show="hiddenProject">@{{priceColTotal}}</td>
                        <td class="border-bottom"></td>
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
            <a href="javascript:;" class="keepBtn" v-show="keepAdd" @click="save_reload">保存并新增</a>
            <a href="javascript:;" class="keepBtn" v-show="keepAfter">新增</a>
            <a href="javascript:;" class="keepBtn1" @click="showKeepafter">保存</a>
            <a href="javascript:;" class="delBtn" v-show="keepAfter">删除</a>
            <div href="javascript:;" class="importBtn1" @mouseenter="otherShow = true" @mouseleave="otherShow = false">
                <a href="javascript:;" v-show="keepAfter">快速收款</a>
                <ul class="invoice-wx" v-show="otherShow">
                    <li><a href="javascript:;">银行</a></li>
                    <li><a href="javascript:;">现金</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div id="newAddBz" style="display:none;">
        <form>
            <div class="item-bz">
                <label class="bz-code">币种编码:</label>
                <div class="codeMenu" ref="uicodeShows">
                    <div class="selectBz-type" @click="uicodeList = !uicodeList">
                        <input type="text" class="textWrapper" :value="getCode">
                        <i class="iconfont icon-xialazhishijiantou"></i>
                    </div>
                    <ul class="showCode" v-show="uicodeList">
                        <li v-for="item in uicodeOptions" :key="item.index" @click="getNewCodes(item)">
                            @{{item.label}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="item-bz">
                <label class="bz-code">币种名称:</label>
                <div class="codeMenu">
                    <input type="text" readonly :value="codeName" class="readInput">
                </div>
            </div>
            <div class="item-bz">
                <label class="bz-code">汇率制度:</label>
                <div class="codeMenu" ref="rateShows">
                    <div class="selectBz-type" @click="uicodeRate = !uicodeRate">
                        <input type="text" class="textWrapper" :value="getCodeRate">
                        <i class="iconfont icon-xialazhishijiantou"></i>
                    </div>
                    <ul class="showCode" v-show="uicodeRate">
                        <li v-for="item in rateOption" :key="item.index" @click="getCodeRates(item)">
                            @{{item.label}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="item-bz" v-if="dataRates">
                <label class="bz-code">本期汇率:</label>
                <div class="codeMenu">
                    <!--<input type="text" :value="dataRate" v-model="dataRate">-->
                    <input type="text" :value="hlRate" v-model="hlRate">
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    @parent
    <script>

        var invoiceWrapper = new Vue({
            'el': ".invoiceWrapper",
            data: {
                selectBzVal: '',
                searchVal:'',
                hlVal: '',
                ybTotal: '',
                wxTotal: '',
                data: '',
                invoiceName: '',
                otherShow: false,
                keepAfter: false,
                keepAdd: true,
                invoiceNameShow: false,
                getDate: '增值税专用发票',
                disabled: false,
                codeList: false,
                otherMenu: true,
                typeDis: false,
                otherInvoice: true,
                useClick: true,
                typeMoney: false,
                wxBzAdd: false,
                typeRate: 'receiveRate',
                selectVal: '',
                ratesTotal: false,
                typePrice: 'receivePrice',
                hiddenProject: true,
                wxMenu: false,
                wxxs: false,
                jzjt: true,
                ydzk: true,
                money: true,
                wxMoney: false,
                moneyTotal: '',
                rateColTotal: '',
                priceColTotal: '',
                /*
                options: [
                    {
                        value: '1',
                        label: '增值税专用发票'
                    },
                    {
                        value: '2',
                        label: '增值税普通发票'
                    },
                    {
                        value: '3',
                        label: '货物运输业增值税专用发票'
                    },
                    {
                        value: '4',
                        label: '机动车销售统一发票'
                    },
                    {
                        value: '5',
                        label: '国税通用机打发票'
                    },
                    {
                        value: '6',
                        label: '国税其他发票'
                    },
                    {
                        value: '7',
                        label: '地税发票'
                    },
                    {
                        value: '8',
                        label: '外销形式发票'
                    },
                    {
                        value: '9',
                        label: '无票收入(增值税)'
                    },
                    {
                        value: '10',
                        label: '无票收入(营业税)'
                    },
                ],
                */
                options: [],
                invoiceNameOption: JSON.parse('{!!  json_encode(\App\Entity\Invoice::dwList())   !!}'),
                kpOptions: [
                    '广告服务', '娱乐服务', '技术服务', '加工承揽', '财产租赁', '建设工程勘察设计', '货物运输', '仓储保管', '建筑安装工程承包'
                ],
                kpfwOptions: [
                    '显示空白', '具体是啥', '看不到'
                ],
                smOptions: [
                    {
                        value: '0.03',
                        label: '一般计税方法-3%'
                    },
                    {
                        value: '0.01',
                        label: '一般计税方法-1%'
                    },
                    {
                        value: '0.02',
                        label: '简易计税方法-2%'
                    },
                    {
                        value: '0.17',
                        label: '简易计税方法-17%'
                    },
                    {
                        value: '0.78',
                        label: '简易计税方法-78%'
                    },
                    {
                        value: '0.00',
                        label: '免税货物'
                    },
                ],
                rateOptions: [0.11, 0.21, 0.31, 0.44, 0.78],
                ywOptions: [],
                smTitle: [
                    '存货', '服务'
                ],
                tablesDate: [
                    {
                        name: 1,
                        num: '',
                        money: '',
                        hl: '',
                        rate: '',
                        ticketVal: '',
                        ywMaterial: '',
                        rateMoneyLine: '',
                        wxmoneyLine: '',
                        ratePriceLine: '',
                        smVal: '',
                        kpVal: '',
                        ggxh: '',
                        dw: '',
                        ybje: '',
                        nowIndex: 0,
                        ratesTotal: false,
                        yw: false,
                        sm: false,
                        kpxm: false,
                        kpMenu: false,
                        tickDisabled: true,
                    },
                ],
                bzSelectOptions: [],
                copySelect: [],
                reqParams: {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id  }}',
                    sub_type: '{{ \App\Entity\Invoice::SUB_TYPE_EXPORT_ZZSPTFP  }}',
                    type: '{{ \App\Entity\Invoice::TYPE_EXPORT  }}',
                    kplx: '自开',
                    dkfs: '{{ \App\Entity\Invoice::DKFS_YBXM }}',
                    fpzs: 1,
                    zsfs: [],
                },
            },
            created: function () {
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
                this.ywOptions = JSON.parse('{!! json_encode((new \App\Entity\BusinessDataConfig\BusinessConfig(2))->getData()) !!}');

                var options = JSON.parse('{!! json_encode(\App\Entity\Invoice::$subTypeLabelsExport) !!}');
                //console.log(options);
                for (var i in options) {
                    this.options.push({value: i, label: options[i]});
                }
            },
            mounted:function(){
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
                    this.invoiceNameShow = true;
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
                clickBlank:function(){
                    /*--发票类型titleType，名称nameType,业务类型、税目、开票项目*/
                    var titleType = this.$refs.titleType;
                    var nameType = this.$refs.nameType;
                    var bzType = this.$refs.bzType;
                    var _this = this;
                    document.addEventListener('click',function(e){
                        if(!titleType.contains(e.target)){
                            _this.codeList = false;
                        }
                        if(!nameType.contains(e.target)){
                            _this.invoiceNameShow = false;
                        }
                        if(!bzType.contains(e.target)){
                            _this.wxMenu = false;
                        }
                    });
                    $(document).click(function(event){
                        var _con = $('.curList');  // 设置目标区域
                        if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
                            for(var i in _this.tablesDate){
                                _this.tablesDate[i].yw = false;
                                _this.tablesDate[i].sm = false;
                                _this.tablesDate[i].kpMenu = false;
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
                        hl: '',
                        rate: '',
                        ticketVal: '',
                        ywMaterial: '',
                        rateMoneyLine: '',
                        wxmoneyLine: '',
                        ratePriceLine: '',
                        smVal: '',
                        kpVal: '',
                        ggxh: '',
                        dw: '',
                        ybje: '',
                        nowIndex: 0,
                        ratesTotal: false,
                        yw: false,
                        sm: false,
                        kpxm: false,
                        kpMenu: false,
                        tickDisabled: true,
                    });
                },
                /*----增加下行-------*/
                addDown: function (index) {
                    this.tablesDate.splice((index + 1), 0, {
                        name: 1,
                        num: '',
                        money: '',
                        hl: '',
                        rate: '',
                        ticketVal: '',
                        ywMaterial: '',
                        rateMoneyLine: '',
                        wxmoneyLine: '',
                        ratePriceLine: '',
                        smVal: '',
                        kpVal: '',
                        ggxh: '',
                        dw: '',
                        ybje: '',
                        nowIndex: 0,
                        ratesTotal: false,
                        yw: false,
                        sm: false,
                        kpxm: false,
                        kpMenu: false,
                        tickDisabled: true,
                    });
                },
                /*----不同发票的选择title----*/
                getNewAdds: function (item) {
                    value = item.label;
                    this.codeList = false;
                    this.getDate = value;
                    this.reqParams.sub_type = item.value;
                    //console.log(this.subtype);
                    /*不同的发票table的显示不同*/
                    /*addedTax-增值税--otherInvoice-其他（可抵扣）-otherMenu-其他---customsEntry-海关---typeDis(disabled)-第一个table的地址电话---invoiceNum-数量宽度---invoicePriceTotal-完税总价宽度*/
                    /*--typeMoney--金额的disabled---typePrice---价税合计的disabled*/
                    switch (value) {
                        case '增值税专用发票':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.ydzk = true;
                            this.jzjt = true;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '增值税普通发票':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.ydzk = true;
                            this.jzjt = true;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '货物运输业增值税专用发票':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.ydzk = true;
                            this.jzjt = true;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '机动车销售统一发票':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.ydzk = true;
                            this.jzjt = true;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '国税通用机打发票':
                            this.typeMoney = 'receiveMoney';
                            this.typeRate = 'receiveRate';
                            this.typePrice = '';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.jzjt = true;
                            this.ydzk = false;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '国税其他发票':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.jzjt = true;
                            this.ydzk = false;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '地税发票':
                            this.typeMoney = 'receiveMoney';
                            this.typeRate = 'receiveRate';
                            this.hiddenProject = false;
                            this.wxxs = false;
                            this.otherMenu = false;
                            this.ydzk = false;
                            this.jzjt = false;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 242 + 'px';
                            break;
                        case '外销形式发票':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = false;
                            this.wxxs = true;
                            this.otherMenu = false;
                            this.ydzk = false;
                            this.jzjt = false;
                            this.wxBzAdd = true;
                            this.wxMoney = true;
                            this.money = false;
                            //清空原有的金额、税率、税目
                            //计算原币金额、金额
                            for (var i in this.tablesDate) {
                                //每行的税额
                                this.tablesDate[i].ywMaterial = '';
                                this.tablesDate[i].smVal = '';
                                this.tablesDate[i].kpVal = '';
                                this.tablesDate[i].ggxh = '';
                                this.tablesDate[i].dw = '';
                                this.tablesDate[i].num = '';
                                this.tablesDate[i].hl = '';
                                this.tablesDate[i].money = '';
                                this.tablesDate[i].rate = '';
                                //每行的金额
                                if (this.tablesDate[i].ywMaterial == '') {
                                    this.tablesDate[i].tickDisabled = true;
                                }
                            }
                            this.moneyTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '无票收入(增值税)':
                            this.typeMoney = false;
                            this.typeRate = 'receiveRate';
                            this.typePrice = 'receivePrice';
                            this.hiddenProject = true;
                            this.wxxs = false;
                            this.otherMenu = true;
                            this.ydzk = false;
                            this.jzjt = true;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 80 + 'px';
                            break;
                        case '无票收入(营业税)':
                            this.typeMoney = 'receiveMoney';
                            this.typeRate = 'receiveRate';
                            this.hiddenProject = false;
                            this.wxxs = false;
                            this.otherMenu = false;
                            this.ydzk = false;
                            this.jzjt = false;
                            this.wxBzAdd = false;
                            this.wxMoney = false;
                            this.money = true;
                            this.moneyTotal = '';
                            this.rateColTotal = '';
                            this.priceColTotal = '';
                            this.$refs.invoiceNum.style.width = 242 + 'px';
                            break;
                    }
                },
                delTableLine: function (index) {
                    /*--删除---最后一条的提示*/
                    var num = this.$refs.invoiceTable.rows.length;
                    if (num > 1) {
                        this.tablesDate.splice(index, 1);
                        this.$options.methods.allTotal.bind(this)();
                    } else {
                        alert("至少保留一条明细")
                    }
                },
                /*---税率的选择---*/
                getRateData: function (ratesList, item) {
                    item.rate = ratesList;
                    item.ratesTotal = false;
                    this.$options.methods.allTotal.bind(this)();
                },
                getYw: function (yw, item) {
                    item.ywMaterial = yw.name;
                    item.ywMaterial_id = yw.number;

                    item.yw = false;
                    if (item.ywMaterial) {
                        item.tickDisabled = false;
                        //外销发票时更改币种所有汇率都变化-----
                        item.hl = this.hlVal;
                    }
                    this.$options.methods.allTotal.bind(this)();
                },
                /*----税目tab的选择---*/
                showTable: function (index, item) {
                    item.nowIndex = index
                },
                /*------------税目的选择-------*/
                getSm: function (sm, item,index) {
                    item.smVal = sm.label;
                    item.rate = sm.value;
                    item.sm = false;
                    //每行税额、每行价税合计、金额总额、税额总额rateColTotal、价税合计总额priceColTotal
                    var rateline = 0;
                    var ratePriceLine = 0;
                    var moneyTotal = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    var wxMoneyTotal = 0;
                    var ybMoneyTotal = 0;
                    var _this = this;
                    for (var i in this.tablesDate) {
                        //每行的税额
                        this.tablesDate[index].rateMoneyLine = (Number(this.tablesDate[index].money) * Number(this.tablesDate[index].rate)).toFixed(2);
                        //外销的每行金额
                        this.tablesDate[i].wxmoneyLine = (Number(this.tablesDate[i].ybje) * Number(this.tablesDate[i].hl)).toFixed(2);
                        //每行的价税合计
                        // ybTotal,wxTotal
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        moneyTotal += Number(this.tablesDate[i].money);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        ybMoneyTotal += Number(this.tablesDate[i].ybje);
                        wxMoneyTotal += Number(this.tablesDate[i].wxmoneyLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                        if (this.tablesDate[i].ybje == '' || this.tablesDate[i].hl == '') {
                            this.tablesDate[i].wxmoneyLine = ''
                        }
                    }
                    _this.moneyTotal = Number(moneyTotal).toFixed(2);
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2);
                    _this.ybTotal = Number(ybMoneyTotal).toFixed(2);
                    _this.wxTotal = Number(wxMoneyTotal).toFixed(2);
                    if (_this.moneyTotal == 0) {
                        _this.moneyTotal = ''
                    }
                    if (_this.rateColTotal == 0) {
                        _this.rateColTotal = ''
                    }
                    if (_this.priceColTotal == 0) {
                        _this.priceColTotal = ''
                    }
                    if (_this.ybTotal == 0) {
                        _this.ybTotal = ''
                    }
                    if (_this.wxTotal == 0) {
                        _this.wxTotal = ''
                    }
                },
                /*----开票项目的获取-----*/
                getKp: function (kp, item) {
                    item.kpVal = kp;
                    item.kpxm = false;
                    item.kpMenu = false;
                },
                getKpfw: function (kpfw, item) {
                    item.kpVal = kpfw;
                    item.kpxm = false;
                    item.kpMenu = false;
                },
                getinvoiceName: function (nameList) {
                    //console.log(nameList);
                    this.invoiceName = nameList.value;
                    this.reqParams.gfdw_name = nameList.value;
                    this.reqParams.gfdw_id = nameList.id;
                    this.invoiceNameShow = false;
                },
                /*-----------------数量失去焦点--------*/
                numComputed: function (item) {
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

                    item.num = toDecimal(item.num)
                },
                //计算发票总数
                allTotal: function () {
                    //每行税额、每行价税合计、金额总额、税额总额rateColTotal、价税合计总额priceColTotal
                    var rateline = 0;
                    var ratePriceLine = 0;
                    var moneyTotal = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    var wxMoneyTotal = 0;
                    var ybMoneyTotal = 0;
                    var _this = this;
                    for (var i in this.tablesDate) {
                        //每行的税额
                        this.tablesDate[i].rateMoneyLine = (Number(this.tablesDate[i].money) * Number(this.tablesDate[i].rate)).toFixed(2);
                        //外销的每行金额
                        this.tablesDate[i].wxmoneyLine = (Number(this.tablesDate[i].ybje) * Number(this.tablesDate[i].hl)).toFixed(2);
                        //每行的价税合计
                        // ybTotal,wxTotal
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        moneyTotal += Number(this.tablesDate[i].money);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        ybMoneyTotal += Number(this.tablesDate[i].ybje);
                        wxMoneyTotal += Number(this.tablesDate[i].wxmoneyLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                        if (this.tablesDate[i].ybje == '' || this.tablesDate[i].hl == '') {
                            this.tablesDate[i].wxmoneyLine = ''
                        }
                    }
                    _this.moneyTotal = Number(moneyTotal).toFixed(2);
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2);
                    _this.ybTotal = Number(ybMoneyTotal).toFixed(2);
                    _this.wxTotal = Number(wxMoneyTotal).toFixed(2);
                    if (_this.moneyTotal == 0) {
                        _this.moneyTotal = ''
                    }
                    if (_this.rateColTotal == 0) {
                        _this.rateColTotal = ''
                    }
                    if (_this.priceColTotal == 0) {
                        _this.priceColTotal = ''
                    }
                    if (_this.ybTotal == 0) {
                        _this.ybTotal = ''
                    }
                    if (_this.wxTotal == 0) {
                        _this.wxTotal = ''
                    }
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

                    item.money = toDecimal(item.money);
                    //每行税额、每行价税合计、金额总额、税额总额rateColTotal、价税合计总额priceColTotal
                    var rateline = 0;
                    var ratePriceLine = 0;
                    var moneyTotal = 0;
                    var rateColTotal = 0;
                    var priceColTotal = 0;
                    var wxMoneyTotal = 0;
                    var ybMoneyTotal = 0;
                    var _this = this;
                    for (var i in this.tablesDate) {
                        //每行的税额
                        this.tablesDate[index].rateMoneyLine = (Number(this.tablesDate[index].money) * Number(this.tablesDate[index].rate)).toFixed(2);
                        //外销的每行金额
                        this.tablesDate[i].wxmoneyLine = (Number(this.tablesDate[i].ybje) * Number(this.tablesDate[i].hl)).toFixed(2);
                        //每行的价税合计
                        // ybTotal,wxTotal
                        this.tablesDate[i].ratePriceLine = (Number(this.tablesDate[i].money) + Number(this.tablesDate[i].rateMoneyLine)).toFixed(2);
                        moneyTotal += Number(this.tablesDate[i].money);
                        rateColTotal += Number(this.tablesDate[i].rateMoneyLine);
                        priceColTotal += Number(this.tablesDate[i].ratePriceLine);
                        ybMoneyTotal += Number(this.tablesDate[i].ybje);
                        wxMoneyTotal += Number(this.tablesDate[i].wxmoneyLine);
                        if (this.tablesDate[i].money == '' || this.tablesDate[i].rate == '') {
                            this.tablesDate[i].rateMoneyLine = '';
                            this.tablesDate[i].ratePriceLine = ''
                        }
                        if (this.tablesDate[i].ybje == '' || this.tablesDate[i].hl == '') {
                            this.tablesDate[i].wxmoneyLine = ''
                        }
                    }
                    _this.moneyTotal = Number(moneyTotal).toFixed(2);
                    _this.rateColTotal = Number(rateColTotal).toFixed(2);
                    _this.priceColTotal = Number(priceColTotal).toFixed(2);
                    _this.ybTotal = Number(ybMoneyTotal).toFixed(2);
                    _this.wxTotal = Number(wxMoneyTotal).toFixed(2);
                    if (_this.moneyTotal == 0) {
                        _this.moneyTotal = ''
                    }
                    if (_this.rateColTotal == 0) {
                        _this.rateColTotal = ''
                    }
                    if (_this.priceColTotal == 0) {
                        _this.priceColTotal = ''
                    }
                    if (_this.ybTotal == 0) {
                        _this.ybTotal = ''
                    }
                    if (_this.wxTotal == 0) {
                        _this.wxTotal = ''
                    }
                    //this.$options.methods.allTotal.bind(this)();
                },
                //外销原币金额失去焦点计算金额
                ybComputed: function (item) {
                    this.$options.methods.allTotal.bind(this)();
                },
                //选择不同的radio
                selectRadio: function (item, index) {
                    this.hlVal = this.bzSelectOptions[index].rates;
                    for (var i in this.tablesDate) {
                        if (this.tablesDate[i].ywMaterial) {
                            this.tablesDate[i].hl = this.bzSelectOptions[index].rates
                        }
                    }
                    this.$options.methods.allTotal.bind(this)();
                },
                //添加币种的弹窗
                addBzType: function () {
                    var _this = this;
                    layer.open({
                        type: 1,
                        title: '新增币种',
                        skin: 'bz-wrapper', //样式类名
                        /*closeBtn: 0, //不显示关闭按钮*/
                        shadeClose: true, //开启遮罩关闭
                        content: $('#newAddBz'),
                        area: ['360px', 'auto'],
                        btn: ['保存并新增', '保存', '取消'],
                        yes: function (index, layero) {
                            _this.hlVal = bz.hlRate;
                            _this.copySelect.splice(_this.copySelect.length, 0, {
                                    label: bz.codeName,
                                    rates: bz.hlRate
                                }
                            );
                            var arr = _this.copySelect;
                            var b = [];
                            var Fun = function Fun(arr, type, cType) {
                                arr.map(function (v, i) {
                                    if (b.length === 0) {
                                        b.push(v)
                                    } else {
                                        var isAdd = true;
                                        b.forEach(function (v1, i1) {
                                            if (v[type] === v1[type]) {
                                                isAdd = false;
                                                v1[cType] = v[cType]
                                            }
                                        });
                                        if (isAdd) b.push(v)
                                    }
                                })
                            };
                            Fun(arr, 'label', 'rates');
                            _this.bzSelectOptions = b;
                            layer.close(index)
                        },
                        btn2: function (index, layero) {

                        }
                    });
                },
                //点击保存出现删除、新增、快速收款按钮
                showKeepafter: function () {
                    this.save();
                },


                askCezz: function ($event) {

                },

                // 保存按钮点击
                save: function (reload) {

                    /** 验证数据 开始 */
                    var tmp_tablesDate = [];
                    for (var i in this.tablesDate) {
                        if (this.tablesDate[i]['ywMaterial'] != '') {
                            tmp_tablesDate.push(this.tablesDate[i]);
                        }
                    }
                    if (tmp_tablesDate.length == 0) {
                        layer.msg('业务明细数据不能少于1行', {icon: 2, time: 1000});
                        return false;
                    }
                    for (var i in tmp_tablesDate) {
                        if (tmp_tablesDate[i]['ywMaterial_id'] != ''
                            && (tmp_tablesDate[i]['smVal'] == ''
                                || tmp_tablesDate[i]['money'] == ''
                                || tmp_tablesDate[i]['rate'] == ''
                                || tmp_tablesDate[i]['rateMoneyLine'] == ''
                                || tmp_tablesDate[i]['ratePriceLine'] == ''
                            )) {

                            layer.msg('请完善' + tmp_tablesDate[i]['ywMaterial'] + '业务明细数据', {icon: 2, time: 1000});
                            return false;
                        }
                    }
                    /** 验证数据 结束 */
                    var items = [];
                    for (var i in tmp_tablesDate) {
                        items.push({
                            ywlx_id: String(tmp_tablesDate[i]['ywMaterial_id']),
                            ywlx_name: String(tmp_tablesDate[i]['ywMaterial']),
                            //kpxm_id: String(tmp_tablesDate[i]['ticketVal_id']),
                            kpxm_name: String(tmp_tablesDate[i]['kpVal']),
                            ggxh: (tmp_tablesDate[i]['ggxh']),
                            dw: (tmp_tablesDate[i]['dw']),
                            num: String(tmp_tablesDate[i]['num']),
                            money: String(tmp_tablesDate[i]['money']),
                            tax_rate: String(tmp_tablesDate[i]['rate']),
                            tax_name: String(tmp_tablesDate[i]['smVal']),
                            tax_money: String(tmp_tablesDate[i]['rateMoneyLine']),
                            fee_tax_sum: String(tmp_tablesDate[i]['ratePriceLine']),
                            account_name: String(tmp_tablesDate[i]['ywMaterial']),
                            account_number: String(tmp_tablesDate[i]['ywMaterial_id']),
                        });
                    }
                    this.reqParams.items = JSON.stringify(items);
                    this.reqParams.kprq = $('#date').val();
                    //console.log(this.reqParams.zsfs);
                    this.reqParams.zsfs = JSON.stringify(this.reqParams.zsfs);
                    this.reqParams._token = '{{csrf_token()}}';
                    //console.log(this.tablesDate);
                    this.$http.post('{{ url('book/invoice/create') }}', this.reqParams).then(function (response) {
                        var status = response.body.status == 1 ? 1 : 2;
                        layer.msg(response.body.info, {icon: status, time: 3000});
                        setTimeout(function () {
                            if (reload == true && response.body.status == 1) {
                                location.href = '{{ url('book/invoice/addExport')  }}';
                            } else if (response.body.status == 1) {
                                //跳转到编辑页
                                location.href = '{{ url('book/invoice/editImport')  }}/' + response.body.data.id;
                            }else {
                                var msg = '网络异常，请稍后再试';
                                layer.msg(msg, {icon: status, time: 3000});
                            }
                        }, 1000);

                    });
                },

                // 保存并新增按钮点击
                save_reload: function () {
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
        });
        var bz = new Vue({
            'el': '#newAddBz',
            data: {
                uicodeList: false,
                uicodeRate: false,
                dataRates: true,
                getCode: 'USD',
                codeName: '美元',
                codeRate: '',
                hlRate: '0.30',
                getCodeRate: '固定汇率',
                dataRate: '',
                uicodeOptions: [
                    {
                        label: 'USD',
                        value: '美元',
                        hlRates: '0.30'
                    },
                    {
                        label: 'EUR',
                        value: '欧元',
                        hlRates: '0.20'
                    },
                    {
                        label: 'JPY',
                        value: '日元',
                        hlRates: '0.40'
                    },
                    {
                        label: 'HKD',
                        value: '港元',
                        hlRates: '0.50'
                    },
                    {
                        label: 'GBP',
                        value: '英镑',
                        hlRates: '0.10'
                    },
                    {
                        label: 'AUD',
                        value: '澳大利亚元',
                        hlRates: '0.60'
                    }
                ],
                rateOption: [
                    {
                        label: '固定汇率'
                    },
                    {
                        label: '浮动汇率'
                    }
                ]
            },
            methods: {
                //获取币种编码
                getNewCodes: function (item) {
                    this.uicodeList = false;
                    this.getCode = item.label;
                    this.codeName = item.value;
                    this.hlRate = item.hlRates;
                },
                //获取汇率
                getCodeRates: function (item) {
                    this.uicodeRate = false;
                    this.getCodeRate = item.label;
                    if (this.getCodeRate == '浮动汇率') {
                        this.dataRates = false;
                    } else {
                        this.dataRates = true;
                        //console.log(this.dataRate)
                    }
                },


            }
        })
    </script>
@endsection