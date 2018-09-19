@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/certificate.css?v=201807251039">
@endsection

@section('content')
    <div class="recoardWrapper" v-cloak>
        <div class="recoardContain">
            <div class="recoardMenu">
                <div class="shImg" id="shImg" v-show="shImg" style="display:none;"></div>
                <div class="recoardHead">
                    <h2>记账凭证</h2>
                </div>
                <div class="recoardBar">
                    <div class="barMark">
                        <span class="text">记-</span>
                        <input  :min="1"  step="1" type="number" v-model="numVal">
                        <span class="text" style="margin-left:6px">号</span>
                    </div>
                    <div class="barDate">
                        <label>日期:</label>
                        <div class="dateInvoice">
                            <input type="text" onclick="WdatePicker({onpicking:onpick})" :value="dateVal">
                            <i class="iconfont">&#xe65b;</i>
                        </div>
                    </div>
                    <div class="barYear">
                        <div>
                            <span>@{{period}}</span>
                        </div>
                    </div>
                    <div class="barZ">
                        附单据
                        <input type="text" class="barZin" v-model="attachPage" :value="attachPage">
                        张
                    </div>
                </div>
            </div>
            <div class="recoardTable">
                <table cellspacing="0" border="0" style="border-collapse:collapse;">
                    <thead>
                    <tr class="recoardTable-head">
                        <th width="28" class="border-Leftbottom"></th>
                        <th width="200" class="border-top">摘要</th>
                        <th class="border-top">会计科目</th>
                        <th width="261" class="border-top">
                            <span class="moneySend">借方金额</span>
                            <div class="borderTop">
                                <span>百</span>
                                <span>十</span>
                                <span>亿</span>
                                <span>千</span>
                                <span>百</span>
                                <span>十</span>
                                <span>万</span>
                                <span>千</span>
                                <span>百</span>
                                <span>十</span>
                                <span>元</span>
                                <span>角</span>
                                <span>分</span>
                            </div>
                        </th>
                        <th width="261" class="border-top">
                            <span class="moneySend">贷方金额</span>
                            <div class="borderTop">
                                <span>百</span>
                                <span>十</span>
                                <span>亿</span>
                                <span>千</span>
                                <span>百</span>
                                <span>十</span>
                                <span>万</span>
                                <span>千</span>
                                <span>百</span>
                                <span>十</span>
                                <span>元</span>
                                <span>角</span>
                                <span>分</span>
                            </div>
                        </th>
                        <th width="28" class="border-bottom"></th>
                    </tr>
                    </thead>
                    <tbody class="recoardBody" ref="recoardTable">
                    <tr v-for="(certificate,index) in certificateTable" :key="certificate">
                        <td width="28" class="border-Leftbottom">
                            <div class="addLine oparate">
                                <a href="javascript:;" class="icon iconfont" title="增加分录1" @click="addUp(index)">&#xe609;</a>
                                <a href="javascript:;" class="icon iconfont" title="增加分录2" @click="addDown(index)">&#xe648;</a>
                            </div>
                        </td>
                        <td ref="tdContent">
                            <input type="text" class="celInput" ref="celInput" :value="certificate.zhaiyao" v-model="certificate.zhaiyao" :disabled="disableTrue">
                        </td>
                        <td>
                            <div @click="getAdd(certificate)" ref="kjkmMenu" class="curList">
                                <input type="text" class="celInput hiddenInput" :value="certificate.account_name" v-model="certificate.account_name" :disabled="disableTrue">
                                <input type="hidden" :value="certificate.account_id">
                                {{--<div class="getAdd" v-show="certificate.balance">
                                    <span class="text">余额:</span>
                                    <span ref="nums" class="nums">@{{certificate.money}}</span>
                                </div>--}}
                            </div>
                            <div v-show="certificate.newAdd">
                                <ul class="showTitle">
                                    <li v-for="item in newAdds" :key="item.index" @click="getNewAdds(item,certificate)">
                                        <span>@{{item.number+item.name}}</span>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td class="colSend" @click="total(certificate)">
                            <input type="text" class="celInput bigWord entry" maxlength="11" :value="certificate.debit_money" v-model="certificate.debit_money" @blur="moneyVal(certificate,index)" @focus="moneyValue(certificate,index)" :disabled="disableTrue">
                        <!--<p v-show="certificate.hiddenText" class="showText">@{{val}}</p>-->
                        </td>
                        <td class="colSend">
                            <input type="text" class="celInput bigWord" maxlength="11" :value="certificate.credit_money" v-model="certificate.credit_money" @blur="sendVal(certificate,index)" @focus="sendValue(certificate,index)" :disabled="disableTrue">
                        </td>
                        <td width="28" class="border-bottom">
                            <div class="deleLine oparate">
                                <a href="javascript:;" class="icon iconfont iconD" title="删除分录" @click="delTableLine(index)">&#xe620;</a>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td width="28" class="border-Leftbottom"></td>
                        <td colspan="2">
                            <i class="total">合计:</i>
                            <span>@{{dxMoney}}</span>
                        </td>
                        <td class="colSend">@{{valTotal}}</td>
                        <td class="colSend">@{{sendTotal}}</td>
                        <td width="28" class="border-bottom"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="recoardFooter">
                <div class="auth">
                    <i>制作人:</i>
                    <span></span>
                </div>
                <div class="makeDate">
                    <i> 制作时间:</i>
                    <span></span>
                </div>
                <div class="auth">
                    <i>审核人:</i>
                    <span></span>
                </div>
                <div class="makeDate">
                    <i>审核时间:</i>
                    <span></span>
                </div>
            </div>
        </div>
        <div class="footer">
            <a href="javascript:;" class="recoardItem" @click="keepAdd" v-show="keepShow">保存并新增(F12)</a>
            <a href="javascript:;" class="recoardItem" @click="keepBtn" v-show="keeps">保存(Ctrl+S)</a>
            <a href="javascript:;" class="recoardItem" @click= "auditeBtn" v-show="auditeShow">审核</a>
            <a href="javascript:;" class="recoardDel" v-show="auditeShow" @click="del">删除</a>
            <a href="javascript:;" class="recoardItem" v-show="Naudite" @click="antiAudit">反审核</a>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script src="/js/My97DatePicker/WdatePicker.js"></script>
    <script>
        var curData;
        var listShow;
        function onpick(dp){
            curData = dp.cal.getNewDateStr()
        }
        new Vue({
            'el': ".recoardWrapper",
            data: {
                numVal: '',
                value1: '',
                dateVal:'',
                period:'',
                dxMoney: '',
                attachPage: '0',
                keepShow: true,
                auditeShow:false,
                Naudite:false,
                keeps:true,
                ids: '',
                shImg:false,
                disableTrue:false,
                /*以上确定用到*/
                colVal: true,
                celInput: false,
                focusState: false,
                value: '',
                newAdd: false,
                newAdds: [],
                balance: false,
                entryV: '',
                valTotal: '',
                sendTotal: '',
                certificateTable: [

                ]
            },
            created:function(){
                /*---后台获取数据------*/
                this.getVoice();
                this.getVoiceBook();
            },
            mounted() {
                this.clickBlank()
            },
            methods: {
                //点击空白处相应div隐藏
                clickBlank:function(){
                    var _this = this;
                    $(document).click(function(event){
                        var _con = $('.curList');  // 设置目标区域
                        if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
                            for(var i in _this.certificateTable){
                                _this.certificateTable[i].newAdd = false;
                            }
                        }
                    });
                },
                getVoiceBook:function(){
                    var _this = this;
                    // console.log(1)
                    var item = localStorage.getItem('invoiceId');
                    //console.log(item)
                    var params = {'_token': '{{ csrf_token()  }}','id': item};
                    //获取凭证预览数据
                    _this.$http.post('{{route('voucher.preview')}}',params).then(function(response){
                        //console.log(response.body)
                        response = response.body.data;
                        _this.numVal = response.maxVoucherNum;
                        _this.dateVal = response.voucherDate;
                        _this.period = response.period;
                        _this.certificateTable = response.data;
                         curData = response.voucherDate;
                       /*-----------处理后端的数据---------*/
                        var arr = response.data;
                        for(var i in arr){
                            arr[i].account_name = arr[i].account_number + arr[i].account_name
                            if(arr[i].debit_money != ''){
                                arr[i].debit_money = (arr[i].debit_money * 100).toFixed(0)
                            }
                            if(arr[i].credit_money != ''){
                                arr[i].credit_money = (arr[i].credit_money * 100).toFixed(0)
                            }
                        }
                        _this.certificateTable = arr;

                    }).then(function(){
                        var valTotal = 0;
                        var sendTotal = 0;
                        for (var i in this.certificateTable) {
                            valTotal += Number(this.certificateTable[i].debit_money);
                            sendTotal += Number(this.certificateTable[i].credit_money)
                        }
                        this.valTotal = valTotal/100;
                        this.sendTotal = sendTotal/100;
                        var number = this.valTotal;
                        var ret = "";
                        if (number != "" && number != null && number != "0") {
                            var unit = "仟佰拾亿仟佰拾万仟佰拾元角分";
                            var str = "";
                            number += "00";
                            var point = number.indexOf('.');
                            if (point >= 0) {
                                number = number.substring(0, point) + number.substr(point + 1, 2);
                            }
                            unit = unit.substr(unit.length - number.length);
                            for (var i = 0; i < number.length; i++) {
                                str += '零壹贰叁肆伍陆柒捌玖'.charAt(number.charAt(i)) + unit.charAt(i);
                            }
                            ret = str.replace(/零(仟|佰|拾|角)/g, "零").replace(/(零)+/g, "零").replace(/零(万|亿|元)/g, "$1").replace(/(亿)万|(拾)/g, "$1$2").replace(/^元零?|零分/g, "").replace(/元$/g, "元") + "整";
                        }
                        this.dxMoney = ret;
                    });
                },
                /*--------获取数据-----*/
                getVoice:function(){
                    //获取凭证页面内的参数
                    // console.log(1)
                    var _this = this;
                    _this.$http.get('{{route('voucher.add')}}').then(function(response){
                        response = response.body;
                        _this.newAdds = response.kuaijikemu;
                    })
                },
                /*--------增加上行----*/
                addUp: function (index) {
                    this.certificateTable.splice(index, 0, {
                        zhaiyao:'',
                        account_name: '',
                        debit_money: '',
                        credit_money: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: false,
                        hiddenText: false,
                    });
                },
                /*--------增加下行-------*/
                addDown: function (index) {
                    this.certificateTable.splice((index + 1), 0, {
                        zhaiyao:'',
                        account_name: '',
                        debit_money: '',
                        credit_money: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: false,
                        hiddenText: false,
                    });
                },
                /*--------删除每行-------*/
                delTableLine: function (index) {
                    /*--删除---最后一条的提示*/
                    let num = this.$refs.recoardTable.rows.length;
                    if (num > 1) {
                        this.certificateTable.splice(index, 1)
                    } else {
                        alert("至少保留一条明细")
                    }
                    /*---当列和--*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for (var i in this.certificateTable) {
                        valTotal += Number(this.certificateTable[i].val)
                        sendTotal += Number(this.certificateTable[i].credit_money)
                    }
                    this.valTotal = valTotal/100;
                    this.sendTotal = sendTotal/100
                },
                /*---每行的会计科目-*/
                getNewAdds(item, certificate) {
                    certificate.newAdd = false;
                    certificate.balance = true;
                    certificate.account_name = item.number + item.name;
                    certificate.account_id = item.id;

                },
                /*---每行科目为空时----*/
                getAdd(certificate) {
                    //e.target.parentNode-----nextSbiling
                    //var listShow = $(e.target).parents('.cur').siblings();
                    if (certificate.account_name === '') {
                        certificate.newAdd = true
                    }

                },
                total(certificate) {
                    certificate.hiddenInput = true;
                    certificate.hiddenText = false
                },
                condition:function(){
                    /*------条件符合的情况下传给后端----*/
                    /*----摘要会计科目同行必填---不能少于2行数据----*/
                    var zhInfo = [];
                    var kjkmInfo = [];
                    var jfMoney = [];
                    var dfMoney = [];
                    var kjkmId = [];
                    for(var i in this.certificateTable){
                        jfMoney.push(this.certificateTable[i].debit_money);
                        dfMoney.push(this.certificateTable[i].credit_money);
                        if(this.certificateTable[i].zhaiyao != ''){
                            zhInfo.push(this.certificateTable[i].zhaiyao);
                        }
                        if(this.certificateTable[i].account_name != ''){
                            kjkmInfo.push(this.certificateTable[i].account_name);
                            kjkmId.push(this.certificateTable[i].account_id)
                        }
                        if(this.certificateTable[i].zhaiyao != '' && this.certificateTable[i].account_name == ''){
                            layer.msg('请输入科目', {icon: 2, time: 1000});
                            return;
                        }
                        if(this.certificateTable[i].zhaiyao == '' && this.certificateTable[i].account_name != ''){
                            layer.msg('请输入摘要', {icon: 2, time: 1000});
                            return;
                        }
                        /*---借方与贷方的金额不相等的情况下----*/
                        if(this.valTotal != this.sendTotal){
                            layer.msg('录入借贷不平', {icon: 2, time: 1000});
                            return;
                        }
                    }
                    if(zhInfo.length < 2 && kjkmInfo.length <2){
                        layer.msg('有效数据不能少于2行', {icon: 2, time: 1000});
                        return;
                    }
                    if(this.certificateTable[0].debit_money == '' && this.certificateTable[0].credit_money == ''){
                        layer.msg('第一张凭证金额异常,请核对', {icon: 2, time: 1000});
                        return;
                    }
                    var data = [];
                    for (var i in zhInfo){
                        data.push({
                            'zhaiyao':zhInfo[i],
                            'kuaijikemu_id':kjkmId[i],
                            'debit_money':jfMoney[i]/100,
                            'credit_money':dfMoney[i]/100
                        });
                    }
                    var param = {
                        '_token': '{{ csrf_token()  }}', 'voucher_num':this.numVal, 'attach':this.attachPage,
                        'voucher_date':curData,'voucher_source':'14','total_debit_money':this.valTotal,
                        'total_credit_money':this.sendTotal,'total_cn':this.dxMoney,'items':data
                    };
                    return param;
                },
                /*--------借方的获取焦点-------*/
                moneyValue:function(certificate, index){
                    if(certificate.debit_money != ''){
                        certificate.debit_money = (certificate.debit_money/100).toFixed(2);
                    }
                },
                /*-----贷方获取焦点------*/
                sendValue:function(certificate, index){
                    if(certificate.credit_money != ''){
                        certificate.credit_money = (certificate.credit_money/100).toFixed(2);
                    }
                },
                /*----借方与贷方input失去焦点的debit_money*/
                moneyVal: function (certificate, index) {
                    console.log(certificate)
                    //借方
                    if (certificate.credit_money) {
                        if (certificate.debit_money != '') {
                            certificate.credit_money = ''
                        }
                    }
                    if (isNaN(certificate.debit_money)) {
                        certificate.debit_money = ''
                    } else {
                        //console.log(certificate.debit_money)
                        if(certificate.debit_money != ''){
                            //console.log(certificate.debit_money)
                            certificate.debit_money = Math.abs(certificate.debit_money);
                            certificate.debit_money = (certificate.debit_money * 100).toFixed(0)
                        }
                    }
                   /* /!*---当列和--*!/*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for (var i in this.certificateTable) {
                        valTotal += Number(this.certificateTable[i].debit_money);
                        sendTotal += Number(this.certificateTable[i].credit_money)
                    }

                    this.valTotal = valTotal/100;
                    this.sendTotal = sendTotal/100;
                   if(this.valTotal == 0){
                       this.valTotal = '';
                   }
                   if(this.sendTotal == 0){
                       this.sendTotal = ''
                   }
                  // console.log(certificate.debit_money);
                   if(certificate.debit_money>=10000000000000){
                       layer.msg('只能输入1000亿以下的数据', {icon: 2, time: 1000});
                       certificate.debit_money = 9999999999999;
                       return;
                   }
                   //console.log(this.valTotal == this.sendTotal)
                   if(this.valTotal == this.sendTotal){
                       var number = this.valTotal;
                       var ret = "";
                       if (number != "" && number != null && number != "0") {
                           var unit = "仟佰拾亿仟佰拾万仟佰拾元角分";
                           var str = "";
                           number += "00";
                           var point = number.indexOf('.');
                           if (point >= 0) {
                               number = number.substring(0, point) + number.substr(point + 1, 2);
                           }
                           unit = unit.substr(unit.length - number.length);
                           for (var i = 0; i < number.length; i++) {
                               str += '零壹贰叁肆伍陆柒捌玖'.charAt(number.charAt(i)) + unit.charAt(i);
                           }
                           ret = str.replace(/零(仟|佰|拾|角)/g, "零").replace(/(零)+/g, "零").replace(/零(万|亿|元)/g, "$1").replace(/(亿)万|(拾)/g, "$1$2").replace(/^元零?|零分/g, "").replace(/元$/g, "元") + "整";
                       }
                       this.dxMoney = ret;
                   }else{
                       this.dxMoney = '';
                   }
                },
                //--------贷方失去焦点-----
                sendVal: function (certificate, index) {
                    //贷方
                    if (certificate.debit_money) {
                        if (certificate.credit_money != '') {
                            certificate.debit_money = ''
                        }
                    }
                    if (isNaN(certificate.credit_money)) {
                        certificate.credit_money = ''
                    } else {
                        if(certificate.credit_money != ''){
                            certificate.credit_money = Math.abs(certificate.credit_money);
                            certificate.credit_money = (certificate.credit_money * 100).toFixed(0)
                        }
                    }
                    /* /!*---当列和--*!/*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for (var i in this.certificateTable) {
                        valTotal += Number(this.certificateTable[i].debit_money);
                        sendTotal += Number(this.certificateTable[i].credit_money)
                    }

                    this.valTotal = valTotal/100;
                    this.sendTotal = sendTotal/100;
                    if(this.valTotal == 0){
                        this.valTotal = '';
                    }
                    if(this.sendTotal == 0){
                        this.sendTotal = ''
                    }
                    // console.log(certificate.debit_money);
                    if(certificate.debit_money>=10000000000000){
                        layer.msg('只能输入1000亿以下的数据', {icon: 2, time: 1000});
                        certificate.debit_money = 9999999999999;
                        return;
                    }
                    //console.log(this.valTotal == this.sendTotal)
                    if(this.valTotal == this.sendTotal){
                        var number = this.valTotal;
                        var ret = "";
                        if (number != "" && number != null && number != "0") {
                            var unit = "仟佰拾亿仟佰拾万仟佰拾元角分";
                            var str = "";
                            number += "00";
                            var point = number.indexOf('.');
                            if (point >= 0) {
                                number = number.substring(0, point) + number.substr(point + 1, 2);
                            }
                            unit = unit.substr(unit.length - number.length);
                            for (var i = 0; i < number.length; i++) {
                                str += '零壹贰叁肆伍陆柒捌玖'.charAt(number.charAt(i)) + unit.charAt(i);
                            }
                            ret = str.replace(/零(仟|佰|拾|角)/g, "零").replace(/(零)+/g, "零").replace(/零(万|亿|元)/g, "$1").replace(/(亿)万|(拾)/g, "$1$2").replace(/^元零?|零分/g, "").replace(/元$/g, "元") + "整";
                        }
                        this.dxMoney = ret;

                    }else{
                        this.dxMoney = '';
                    }
                },
                /*---保存按钮-*/
                keepBtn:function(){
                    /*------条件符合的情况下传给后端----*/
                    /*----摘要会计科目同行必填---不能少于2行数据----*/
                    var items = localStorage.getItem('invoiceId');
                    var item = JSON.parse(items);
                    var zhInfo = [];
                    var kjkmInfo = [];
                    var jfMoney = [];
                    var dfMoney = [];
                    var kjkmId = [];
                    for(var i in this.certificateTable){
                        jfMoney.push(this.certificateTable[i].debit_money);
                        dfMoney.push(this.certificateTable[i].credit_money);
                        if(this.certificateTable[i].zhaiyao != ''){
                            zhInfo.push(this.certificateTable[i].zhaiyao);
                        }
                        if(this.certificateTable[i].account_name != ''){
                            kjkmInfo.push(this.certificateTable[i].account_name);
                            kjkmId.push(this.certificateTable[i].account_id)
                        }
                        if(this.certificateTable[i].zhaiyao != '' && this.certificateTable[i].account_name == ''){
                            layer.msg('请输入科目', {icon: 2, time: 1000});
                            return;
                        }
                        if(this.certificateTable[i].zhaiyao == '' && this.certificateTable[i].account_name != ''){
                            layer.msg('请输入摘要', {icon: 2, time: 1000});
                            return;
                        }
                        /*---借方与贷方的金额不相等的情况下----*/
                        if(this.valTotal != this.sendTotal){
                            layer.msg('录入借贷', {icon: 2, time: 1000});
                            return;
                        }
                    }
                    if(zhInfo.length < 2 && kjkmInfo.length <2){
                        layer.msg('有效数据不能少于2行', {icon: 2, time: 1000});
                        return;
                    }
                    if(this.certificateTable[0].debit_money == '' && this.certificateTable[0].credit_money == ''){
                        layer.msg('第一张凭证金额异常,请核对', {icon: 2, time: 1000});
                        return;
                    }
                    var data = [];
                    for (var i in zhInfo){
                        data.push({
                            'zhaiyao':zhInfo[i],
                            'kuaijikemu_id':kjkmId[i],
                            'debit_money':jfMoney[i]/100,
                            'credit_money':dfMoney[i]/100
                        });
                    }
                    var params = {
                        '_token': '{{ csrf_token()  }}', 'voucher_num':this.numVal, 'attach':this.attachPage,
                        'voucher_date':curData,'voucher_source':'14','total_debit_money':this.valTotal,
                        'total_credit_money':this.sendTotal,'total_cn':this.dxMoney,'items':data,'type':item.type,'yw_id': item.id
                    };
                    var _this = this;
                    _this.$http.post('{{route('voucher.make')}}',params).then(function(response){
                        response = response.body;
                        if(response.status == 1){
                            _this.ids = response.data.id;
                            /*-----保存时显示的按钮不同----*/
                            _this.keepShow = false;
                            _this.auditeShow = true;
                            layer.msg(response.info, {icon: 1, time: 1000});
                        }

                    })
                },
                /*----审核按钮----*/
                auditeBtn:function(){
                    /*------条件符合的情况下传给后端----*/
                    var _this = this;
                    _this.disableTrue = true;
                    var params = {'_token': '{{ csrf_token() }}', 'id': [_this.ids],'audit_status':'1'};
                    //console.log(params);
                    _this.$http.post('{{route('voucher.audit')}}',params).then(function(response){
                        response = response.body;
                        //console.log(response);
                        if(response.status == 1){
                            layer.msg(response.info, {icon: 1, time: 1000});
                            _this.keepShow = false;
                            _this.keeps = false;
                            _this.auditeShow = false;
                            _this.Naudite = true;
                            _this.shImg = true;
                        }
                    })
                },
                /*-----反审核-------*/
                antiAudit:function(){
                    /*------条件符合的情况下传给后端----*/
                    /*----摘要会计科目同行必填---不能少于2行数据----*/
                    var zhInfo = [];
                    var kjkmInfo = [];
                    var jfMoney = [];
                    var dfMoney = [];
                    var kjkmId = [];
                    for(var i in this.certificateTable){
                        jfMoney.push(this.certificateTable[i].debit_money);
                        dfMoney.push(this.certificateTable[i].credit_money);
                        if(this.certificateTable[i].zhaiyao != ''){
                            zhInfo.push(this.certificateTable[i].zhaiyao);
                        }
                        if(this.certificateTable[i].account_name != ''){
                            kjkmInfo.push(this.certificateTable[i].account_name);
                            kjkmId.push(this.certificateTable[i].account_id)
                        }
                        if(this.certificateTable[i].zhaiyao != '' && this.certificateTable[i].account_name == ''){
                            layer.msg('请输入科目', {icon: 2, time: 1000});
                            return;
                        }
                        if(this.certificateTable[i].zhaiyao == '' && this.certificateTable[i].account_name != ''){
                            layer.msg('请输入摘要', {icon: 2, time: 1000});
                            return;
                        }
                        /*---借方与贷方的金额不相等的情况下----*/
                        if(this.valTotal != this.sendTotal){
                            layer.msg('录入借贷', {icon: 2, time: 1000});
                            return;
                        }
                    }
                    if(zhInfo.length < 2 && kjkmInfo.length <2){
                        layer.msg('有效数据不能少于2行', {icon: 2, time: 1000});
                        return;
                    }
                    if(this.certificateTable[0].debit_money == '' && this.certificateTable[0].credit_money == ''){
                        layer.msg('第一张凭证金额异常,请核对', {icon: 2, time: 1000});
                        return;
                    }
                    var _this = this;
                    _this.disableTrue = false;
                    var params = {'_token': '{{ csrf_token() }}', 'id': [_this.ids],'audit_status':'0'};
                    _this.$http.post('{{route('voucher.audit')}}',params).then(function(response){
                        response = response.body;
                        //console.log(response);
                        if(response.status == 1){
                            layer.msg(response.info, {icon: 1, time: 1000});
                            _this.keeps = true;
                            _this.auditeShow = true;
                            _this.Naudite = false;
                            _this.shImg = false;
                        }
                    })
                },
                /*-----删除按钮--------*/
                del:function(){
                    var  _this = this;
                    var params = {'_token': '{{ csrf_token()  }}', 'id': [_this.ids]};
                    layer.open({
                        type: 1,
                        title: '信息',
                        skin: 'delAleart',
                        shadeClose: true,
                        shade: false,
                        maxmin: false, //开启最大化最小化按钮
                        area: ['310px', 'auto'],
                        content: '<div class="delMenu">'
                        +'<p>您确定要删除凭证吗</p>'
                        +'<p>删除后将不可恢复,并会产生断号</p>'
                            +'</div>',
                        btn: ['确定','取消'],
                        yes: function (index, layero) {
                            _this.$http.post('{{route('voucher.del')}}',params).then(function(response){
                                response = response.body;
                                console.log(response)
                                if(response.status == 1){
                                    layer.close(index);
                                    layer.msg(response.info, {icon: 1, time: 1000});
                                    window.location.reload()
                                }
                            });

                        }
                    });
                },
                /*----保存并新增-------*/
                keepAdd:function(){
                    var params = this.condition();
                    //console.log(params);
                     this.$http.post('{{route('voucher.make')}}',params).then(function(response){
                        response = response.body;
                         if(response.status == 1){
                             layer.msg(response.info, {icon: 1, time: 1000});
                             window.location.reload()
                         }
                    });
                }
            }
        })
    </script>
@endsection