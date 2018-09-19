@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/certificate.css?v=2018082701">
@endsection

@section('content')
    <div class="recoardWrapper" v-cloak>
        <div class="keyWrapper">
            <div>
                <i class="iconfont keyIcon" @mouseenter="keyShow" @mouseleave="keyHidden">&#xe629;</i>
            </div>
            <div class="keyContent" style="display:none;" v-show="keyShowMenu" @mouseenter="keyShow" @mouseleave="keyHidden">
                <h3>键盘常用操作</h3>
                <div class="keyTable">
                    <table>
                        <thead>
                        <tr>
                            <th>快捷键</th>
                            <th>说明</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Enter/Tab</td>
                            <td>光标跳转到下一输入框</td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td>Shift+Enter/Shift+Tab</td>--}}
                            {{--<td>光标跳转到上一输入框</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td>=</td>
                            <td>自动平衡借贷方金额</td>
                        </tr>
                        <tr>
                            <td>空格键</td>
                            <td>借贷方金额互换</td>
                        </tr>
                        <tr>
                            <td>"//"(小键盘)</td>
                            <td>复制第一条分录的摘要</td>
                        </tr>
                        <tr>
                            <td>".."(小键盘)</td>
                            <td>复制上条分录的摘要</td>
                        </tr>
                        {{--<tr>--}}
                            {{--<td>F12</td>--}}
                            {{--<td>保存并新增</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<td>F10</td>--}}
                            {{--<td>新增科目</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <td>Ctrl+S</td>
                            <td>保存</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="recoardContain">
            <div class="recoardMenu">
                <div class="shImg" id="shImg" v-show="shImg" style="display:none;"></div>
                <div class="recoardHead">
                    <h2>记账凭证</h2>
                </div>
                <div class="recoardBar">
                    <div class="barMark">
                        <span class="text">记-</span>
                        <input  :min="1"  step="5" type="number" v-model="numVal">
                        <span class="text" style="margin-left:6px">号</span>
                    </div>
                    <div class="barDate">
                        <label>日期:</label>
                        <div class="dateInvoice">
                            <input type="text" onclick="WdatePicker({onpicking:onpick})" :value="dateVal">
                            <i class="iconfont">&#xe61f;</i>
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
                    <tr v-for="(certificate,index) in certificateTable" :key="certificate" class="contentItem">
                        <td width="28" class="border-Leftbottom">
                            <div class="addLine oparate" v-if="sh">
                                <a href="javascript:;" class="iconfont addScore" title="增加分录1" @click="addUp(index)">&#xe615;</a>
                                <a href="javascript:;" class="iconfont" title="增加分录2" @click="addDown(index)">&#xe614;</a>
                            </div>
                        </td>
                        <td ref="tdContent">
                            <input type="text" class="celInput abstract" ref="celInput" onfocus="this.select()" @keyup="keyup(certificate, $event)" :value="certificate.zy" v-model="certificate.zy" :disabled="disableTrue" @keyup.enter="inputNext(certificate,index,$event)">
                        </td>
                        <td>
                            <div @click="flag && getAdd(certificate,index,$event)" ref="kjkmMenu" class="curList">
                                <input type="text" class="celInput hiddenInput" @input="value == '' ? getAdd(certificate,index,$event) : ''" @focus="getAdd(certificate,index,$event)" :value="certificate.codeInput" v-model="certificate.codeInput" :disabled="disableTrue" @keyup.enter="inputNext(certificate,index,$event)">
                                <input type="hidden" :value="certificate.id">
                            </div>
                            <div class="keDataBox" v-show="certificate.newAdd">
                                <ul class="showTitle">
                                    <li v-for="(item, index1) in list" :class="{'on':index1 == liIndex}" @mouseover="addOnClass($event)" :key="item.index" @click="getNewAdds(item,certificate)">
                                        <span>@{{item.number+item.name}}</span>
                                    </li>
                                </ul>
                                <div class="xinzengkemu" @click="showAddNewKm(certificate)">
                                    <i class="iconfont">&#xe60c;</i>
                                    <span>新增科目</span>
                                </div>
                            </div>
                        </td>
                        <td class="colSend jf" @click="flag && total(certificate,$event)">
                            <input type="text" class="celInput bigWord entry debit" maxlength="11" v-show="certificate.lendInput" @keyup.enter="inputNext(certificate,index,$event)" :value="certificate.val" v-model="certificate.val" @blur="moneyVal(certificate,index)" :disabled="disableTrue" @input="phLend(certificate,index)">
                            <p class="showText" v-show="certificate.lendShow" :class="{'active':certificate.val<0}">@{{certificate.jfVal}}</p>
                        </td>
                        <td class="colSend df" @click="flag && loan(certificate,$event)">
                            <input type="text" class="celInput bigWord credit" maxlength="11" v-show="certificate.loanInput" :value="certificate.sendVal" v-model="certificate.sendVal" @blur="sendVal(certificate,index)" :disabled="disableTrue" @keyup.enter="inputNext(certificate,index,$event)" @input="phLoan(certificate)">
                            <p class="showText" v-show="certificate.loanShow" :class="{'active':certificate.sendVal<0}">@{{certificate.dfVal}}</p>
                        </td>
                        <td width="28" class="border-bottom">
                            <div class="deleLine oparate" v-if="sh">
                                <a href="javascript:;" class="icon iconfont iconD " title="删除分录" @click="delTableLine(index)">&#xe605;</a>
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
                        <td class="colSend" :class="{'fs':this.valTotal<0}">@{{lendTotal}}</td>
                        <td class="colSend" :class="{'fs':this.sendTotal<0}">@{{loanTotal}}</td>
                        <td width="28" class="border-bottom"></td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <div class="recoardFooter">
                <div class="auth">
                    <i>制作人:</i>
                    <span>@{{create_by}}</span>
                </div>
                <div class="makeDate">
                    <i> 制作时间:</i>
                    <span>@{{create_at}}</span>
                </div>
                <div class="auth">
                    <i>审核人:</i>
                    <span>@{{review_by}}</span>
                </div>
                <div class="makeDate">
                    <i>审核时间:</i>
                    <span>@{{review_at}}</span>
                </div>
            </div>
        </div>
        <div class="footer">
            <a href="javascript:;" class="keepAdd" @click="keepAdd" v-show="keepShow">保存并新增</a>
            <a href="javascript:void(0);" class="keepAdd" @click="addNew" v-show="addShow">新增</a>
            <a href="javascript:;" class="recoardItem" @click="keepBtn" v-show="keeps">保存(Ctrl+S)</a>
            <a href="javascript:void(0);" class="recoardItem" v-show="loading">loading...</a>
            <a href="javascript:;" class="recoardItem" @click= "auditeBtn" v-show="auditeShow">审核</a>
            <a href="javascript:;" class="recoardDel" v-show="auditeShow" @click="del">删除</a>
            <a href="javascript:;" class="recoardItem" v-show="Naudite" @click="antiAudit">反审核</a>
        </div>
    </div>
    <div style="display: none;">
        <a id="to_Code" data-href="{{ route('sub_ledger.list') }}" data-title="凭证" href="javascript:void(0);"  ref="curDom"></a>
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
                //借贷,会计科目的点击事件
                flag: true,
                //审核后icon
                sh:true,
                //下拉框操作
                liIndex: 0,
                //键盘
                keyShowMenu:false,
                curIndex:'0',
                numVal: '',
                searchVal:'',
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
                //逻辑处理的借与贷
                valTotal: '',
                sendTotal: '',
                //页面显示的借与贷
                lendTotal:'',
                loanTotal:'',
                certificateTable: [
                    {
                        zy:'',
                        codeInput: '',
                        val: '',
                        sendVal: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: true,
                        hiddenText: true,
                        //借与贷
                        lendShow:true,
                        lendInput:false,
                        jfVal:'',
                        loanShow:true,
                        loanInput:false,
                        dfVal:''
                    },
                    {
                        zy:'',
                        codeInput: '',
                        val: '',
                        sendVal: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: false,
                        hiddenText: false,
                        //借与贷
                        lendShow:true,
                        lendInput:false,
                        jfVal:'',
                        loanShow:true,
                        loanInput:false,
                        dfVal:''
                    },
                    {
                        zy:'',
                        codeInput: '',
                        val: '',
                        sendVal: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: false,
                        hiddenText: false,
                        //借与贷
                        lendShow:true,
                        lendInput:false,
                        jfVal:'',
                        loanShow:true,
                        loanInput:false,
                        dfVal:''
                    }
                ],
                create_by:'',
                create_at:'',
                review_by:'',
                review_at:'',
                vid:'',
                loading:false,
                addShow:false,
                // 快捷键锁
                kjjSwitch: true,
                doublePoint: true,
                mount: 0,
                time1: 0,
                time2: 0,
                isKmListSpread: 0
            },
            created:function(){
                var _this = this;
                /*---后台获取数据------*/
                this.getVoice();

                layui.use(['form', 'jquery', 'layer'], function () {
                    var form = layui.form;
                    var layer = layui.layer;
                });

                // 快捷键设置 ctrl + s
                document.onkeydown = function (evt) {
                    var e = window.event || evt;
                    var key = e.keyCode;
                    // console.log(e.ctrlKey + ',' + key)
                    // ctrl + s
                    if (e.keyCode == 83 && e.ctrlKey) {
                        // 取消浏览器默认行为
                        e.preventDefault()//
                        e.cancelBubble = true//IE
                        // console.log('push')
                        _this.keepBtn();
                        // _this.kjjSwitch = false;
                    } else if (e.keyCode == 32) {
                        // 空格
                        if ($('input:focus').length == 1) {
                            if(window.event) {
                                e.returnValue = false;
                            }
                            else {
                                e.preventDefault();//for firefox
                            }
                            _this.exchangeDebitAndCredit()
                        }
                    } else if (e.keyCode == 38) {


                        var index = $('.hiddenInput').index($('.hiddenInput:focus'))
                        if (_this.isKmListSpread == 1) {
                            if (_this.liIndex > 0) {
                                _this.liIndex--;
                                if ($('.showTitle li.on')[index].offsetTop < $('.showTitle')[index].scrollTop + 55) {
                                    $('.showTitle')[index].scrollTop = ($('.showTitle li.on')[index].offsetTop - 24)
                                }
                            } else if (_this.liIndex == 0) {
                                _this.liIndex = _this.list.length - 1;
                                // console.log($('.showTitle li:last-child')[index].offsetTop)
                                $('.showTitle')[index].scrollTop = ($('.showTitle li:last-child')[index].offsetTop - 24)

                            }
                        }

                    } else if (e.keyCode == 40) {
                        var index = $('.hiddenInput').index($('.hiddenInput:focus'))

                        if (_this.isKmListSpread == 1) {
                            if (_this.liIndex < _this.list.length - 1) {
                                _this.liIndex++;
                                // console.log(_this.liIndex)
                                if ($('.showTitle li.on')[index].offsetTop > $('.showTitle')[index].scrollTop + 72) {
                                    $('.showTitle')[index].scrollTop = ($('.showTitle li.on')[index].offsetTop - 84)
                                }
                            } else if (_this.liIndex == _this.list.length - 1) {
                                _this.liIndex = 0;
                                // console.log($('.showTitle li:eq(0)')[index])
                                $('.showTitle')[index].scrollTop = ($('.showTitle li:first-child')[index].offsetTop - 24)
                            }
                        }

                    }
                }
            },
            mounted() {
                this.clickBlank();
                // 点开后直接获得焦点
                $('.recoardBody input').eq(0).focus()

            },
            methods: {
                /*-----------阿拉伯数字转换成人民币--------*/
                DX:function(number) {
                    if (!/^-?(0|[1-9]\d*)(\.\d+)?$/.test(number)) return "数据非法";
                    var ret = "";
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

                    if (/^-(0|[1-9]\d*)(\.\d+)?$/.test(number)){
                        var num = ret.slice(1);
                        ret = "负" + num;
                    }
                    return ret;
                },
                // 鼠标悬停加类名
                addOnClass: function (e) {
                    var fxx = $('.hiddenInput').index($('.hiddenInput:focus'))
                    if (e.target.nodeName == 'LI') {
                        $(e.target).siblings().removeClass('on')
                        e.target.className = "on";
                        this.liIndex = $($('.showTitle')[fxx]).find('li').index(e.target)
                        // console.log($('.showTitle')[fxx])
                    } else if (e.target.nodeName == 'SPAN') {
                        e.target.parentElement.className = 'on'
                        $(e.target.parentElement).siblings().removeClass('on')
                        this.liIndex = $($('.showTitle')[fxx]).find('li').index(e.target.parentElement)
                    }
                },
                /*------enter后Input下个获取焦点----*/
                inputNext:function(certificate,index,event){
                    var _this = this;
                    var cur = $(event.target);
                    // console.log(cur.parents('td').next().find('input.debit'))
                    // console.log($(event.target).parents('tr').next())
                    if (cur[0].className.search('hiddenInput') != -1) {
                        // console.log(cur.parents('.curList').next().find('li'))
                        for(var i = 0; i < cur.parents('.curList').next().find('li').length; i++) {
                            if (cur.parents('.curList').next().find('li').eq(i).hasClass('on')) {
                                cur.parents('.curList').next().find('li').eq(i).click();
                            }
                        }
                        if(cur.parents('td').next().hasClass('jf')){
                            certificate.lendInput = true;
                            certificate.lendShow = false;
                            _this.$nextTick(function(){
                                cur.parents('td').next().find('input.celInput').focus()
                            })
                        }
                    } else {
                        // 到头后换行
                        if (cur[0].className.search('credit') != -1 && $('.credit').index(cur[0]) < $('.credit').length - 1) {
                            cur = $(event.target).parents('tr').next().find('input.abstract')
                            if ($(cur[0].parentNode.parentNode.previousElementSibling).find('.abstract').val() != 'undefined') {
                                this.certificateTable[index+1].zy = $(cur[0].parentNode.parentNode.previousElementSibling).find('.abstract').val();
                            }
                            cur.focus()
                            return false;
                        }
                        if(cur.parents('td').next().hasClass('jf')){
                            certificate.lendInput = true;
                            certificate.lendShow = false;
                            _this.$nextTick(function(){
                                cur.parents('td').next().find('input.celInput').focus()
                            })

                        }
                        if(cur.parents('td').next().hasClass('df')){
                            // console.log(cur[0].value)
                            if (cur[0].value != '') {
                                // 借方有值时，直接换行
                                _this.$nextTick(function(){
                                    cur = $(event.target).parents('tr').next().find('input.abstract')
                                    if ($(cur[0].parentNode.parentNode.previousElementSibling).find('.abstract').val() != 'undefined') {
                                        this.certificateTable[index+1].zy = $(cur[0].parentNode.parentNode.previousElementSibling).find('.abstract').val();
                                    }
                                    cur.focus()
                                })
                            } else {
                                certificate.loanInput = true;
                                certificate.loanShow = false;
                                _this.$nextTick(function(){
                                    cur.parents('td').next().find('input.celInput').focus()
                                })
                            }
                        }
                        cur.parents('td').next().find('input.celInput').focus()
                    }

                },
                /*-------借方与贷方输入‘=’借贷自动平衡-----------*/
                phLend:function(certificate,index){
                    if(certificate.val == '='){
                        //贷的总和-借的总和
                        certificate.val = (this.sendTotal - this.valTotal).toFixed(2)
                    }
                },
                /*-------借方与贷方输入‘=’借贷自动平衡-----------*/
                phLoan:function(certificate){
                    if(certificate.sendVal == '='){
                        //借的总和valTotal-贷的总和sendTotal
                        certificate.sendVal = (this.valTotal - this.sendTotal).toFixed(2)
                    }
                },
                /*------小键盘显示---------*/
                keyShow:function(){
                    this.keyShowMenu = true;
                },
                /*-------小键盘隐藏-------*/
                keyHidden:function(){
                    this.keyShowMenu = false;
                },
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
                        _this.liIndex = 0;
                    });
                },
                /*-----获取数据-----*/
                getVoice:function(){
                    var _this = this;
                    //获取凭证页面内的参数
                    _this.$http.get('{{route('voucher.add')}}').then(function(response){
                        response = response.body;
                        _this.numVal = response.voucherNum;
                        _this.dateVal = response.date;
                        _this.period = response.period;
                        _this.newAdds = response.kuaijikemu;
                        curData = response.date
                    })
                },
                /*----增加上行----*/
                addUp: function (index) {
                    this.certificateTable.splice(index, 0, {
                        zy:'',
                        codeInput: '',
                        val: '',
                        sendVal: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: false,
                        hiddenText: false,
                        //借与贷
                        lendShow:false,
                        lendInput:true,
                        jfVal:'',
                        loanShow:false,
                        loanInput:true,
                        dfVal:''
                    });
                },
                /*----增加下行-------*/
                addDown: function (index) {
                    this.certificateTable.splice((index + 1), 0, {
                        zy:'',
                        codeInput: '',
                        val: '',
                        sendVal: '',
                        balance: false,
                        newAdd: false,
                        hiddenInput: false,
                        hiddenText: false,
                        //借与贷
                        lendShow:false,
                        lendInput:true,
                        jfVal:'',
                        loanShow:false,
                        loanInput:true,
                        dfVal:''
                    });
                },
                /*---删除每行-------*/
                delTableLine: function (index) {
                    /*--删除---最后一条的提示*/
                    let num = this.$refs.recoardTable.rows.length;
                    if (num > 1) {
                        this.curIndex = index-1;
                        this.certificateTable.splice(index, 1)
                    } else {
                        alert("至少保留一条明细")
                    }
                    /*---当列和--*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for (var i in this.certificateTable) {
                        valTotal += Number(this.certificateTable[i].val);
                        sendTotal += Number(this.certificateTable[i].sendVal)
                    }
                    this.valTotal = valTotal.toFixed(2);
                    this.sendTotal = sendTotal.toFixed(2);
                    this.lendTotal = Math.abs(this.valTotal);
                    this.loanTotal = Math.abs(this.sendTotal);
                    if(this.lendTotal == 0){
                        this.lendTotal = '';
                    }
                    if(this.loanTotal == 0){
                        this.loanTotal = ''
                    }
                    if(this.lendTotal != this.loanTotal){
                        this.dxMoney = ''
                    }
                },
                /*---每行的会计科目-*/
                getNewAdds(item, certificate) {
                    // console.log(certificate)
                    certificate.newAdd = false;
                    certificate.balance = true;
                    certificate.codeInput = item.number + item.name;
                    certificate.account_id = item.id;
                    this.liIndex = 0;
                },
                /*---每行科目为空时----*/
                getAdd: function (certificate,index,e) {
                    //e.target.parentNode-----nextSbiling
                    //var listShow = $(e.target).parents('.cur').siblings();
                    var _this = this;
                    this.curIndex = index;
                    if (certificate.codeInput === '') {
                        for(var i = 0; i < _this.certificateTable.length; i++) {
                            _this.certificateTable[i].newAdd = false;
                            _this.isKmListSpread = 0;
                        }
                        certificate.newAdd = true
                        _this.isKmListSpread = 1;
                    }
                    _this.liIndex = 0;
                    $('.showTitle')[index].scrollTop = 0;

                },
                /*------点击借方----------*/
                total(certificate,event) {
                    certificate.hiddenInput = true;
                    certificate.hiddenText = false;
                    certificate.lendInput = true;
                    certificate.lendShow = false;
                    this.$nextTick(function(){
                        var cur = $(event.target);
                        cur.parents('td').children('input').focus();
                        certificate.jfVal = certificate.val;
                    });
                },
                /*--------点击贷方---------*/
                loan:function(certificate,event){
                    certificate.loanInput = true;
                    certificate.loanShow = false;
                    this.$nextTick(function(){
                        var cur = $(event.target);
                        cur.parents('td').children('input').focus();
                        certificate.dfVal = certificate.sendVal;
                    });
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
                        jfMoney.push(this.certificateTable[i].val);
                        dfMoney.push(this.certificateTable[i].sendVal);
                        if(this.certificateTable[i].zy != ''){
                            zhInfo.push(this.certificateTable[i].zy);
                        }
                        if(this.certificateTable[i].codeInput != ''){
                            kjkmInfo.push(this.certificateTable[i].codeInput);
                            kjkmId.push(this.certificateTable[i].account_id)
                        }
                        if(this.certificateTable[i].zy != '' && this.certificateTable[i].codeInput == ''){
                            layer.msg('请输入科目', {icon: 2, time: 1000});
                            return;
                        }
                        if(this.certificateTable[i].zy == '' && this.certificateTable[i].codeInput != ''){
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
                    if(this.certificateTable[0].val == '' && this.certificateTable[0].sendVal == ''){
                        layer.msg('第一张凭证金额异常,请核对', {icon: 2, time: 1000});
                        return;
                    }
                    var data = [];
                    for (var i in zhInfo){
                        data.push({
                            'zhaiyao':zhInfo[i],
                            'kuaijikemu_id':kjkmId[i],
                            'debit_money':jfMoney[i],
                            'credit_money':dfMoney[i]
                        });
                    }
                    var param = {
                        '_token': '{{ csrf_token()  }}', 'voucher_num':this.numVal, 'attach':this.attachPage,
                        'voucher_date':curData,'voucher_source':'14','total_debit_money':this.valTotal,
                        'total_credit_money':this.sendTotal,'total_cn':this.dxMoney,'id':this.vid,'items':data
                    };
                    return param;
                },
                /*----借方与贷方input失去焦点的val*/
                moneyVal: function (certificate, index) {
                    certificate.lendShow = true;
                    certificate.lendInput = false;
                    //借方
                    if (certificate.sendVal) {
                        if (certificate.val != '') {
                            certificate.sendVal = '';
                            certificate.dfVal = ''
                        }

                    }
                    if (isNaN(certificate.val)) {
                        certificate.val = ''

                    } else {
                        if(certificate.val != ''){
                            /*----借方失去焦点input(val)与p的innerHtml(jfVal)*/
                            certificate.val = Number(certificate.val).toFixed(2);
                            // console.log(Number(certificate.val))
                            certificate.jfVal = Math.abs(Number(certificate.val));
                            certificate.jfVal = parseFloat(((certificate.val) * 100).toPrecision(12));
                            // console.log(parseFloat(((certificate.val) * 100).toPrecision(12)))
                        }else{
                            certificate.jfVal = ''
                        }
                    }

                    /* /!*---当列和--*!/*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for (var i in this.certificateTable) {
                        valTotal += Number(this.certificateTable[i].val);
                        sendTotal += Number(this.certificateTable[i].sendVal)
                    }
                    this.valTotal = valTotal.toFixed(2);
                    this.sendTotal = sendTotal.toFixed(2);
                    this.lendTotal = Math.abs(this.valTotal);
                    this.loanTotal = Math.abs(this.sendTotal);
                    if(this.lendTotal == 0){
                        this.lendTotal = '';
                    }
                    if(this.loanTotal == 0){
                        this.loanTotal = ''
                    }
                    // console.log(certificate.val);
                    if(certificate.val>=10000000000000){
                        layer.msg('只能输入1000亿以下的数据', {icon: 2, time: 1000});
                        certificate.val = 9999999999999;
                        return;
                    }
                    if(this.valTotal == this.sendTotal && this.valTotal != 0){
                        this.dxMoney = this.DX(this.valTotal);
                    }else{
                        this.dxMoney = '';
                    }
                },
                //贷方
                sendVal: function (certificate, index) {
                    certificate.loanShow = true;
                    certificate.loanInput = false;
                    //贷方
                    if (certificate.val) {
                        if (certificate.sendVal != '') {
                            certificate.val = '';
                            certificate.jfVal = ''
                        }
                    }
                    if (isNaN(certificate.sendVal)) {
                        certificate.sendVal = ''
                    } else {
                        if(certificate.sendVal != ''){
                            /*----贷方失去焦点input(val)与p的innerHtml(dfVal)*/
                            certificate.sendVal = Number(certificate.sendVal).toFixed(2);
                            certificate.dfVal = Math.abs(Number(certificate.sendVal));
                            certificate.dfVal = (parseFloat(((certificate.sendVal) * 100).toPrecision(12)));
                        }else{
                            certificate.dfVal = ''
                        }
                    }
                    /* /!*---当列和--*!/*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for (var i in this.certificateTable) {
                        valTotal += Number(this.certificateTable[i].val);
                        sendTotal += Number(this.certificateTable[i].sendVal)
                    }
                    this.valTotal = valTotal.toFixed(2);
                    this.sendTotal = sendTotal.toFixed(2);
                    this.lendTotal = Math.abs(this.valTotal);
                    this.loanTotal = Math.abs(this.sendTotal);
                    if(this.lendTotal == 0){
                        this.lendTotal = '';
                    }
                    if(this.loanTotal == 0){
                        this.loanTotal = ''
                    }
                    // console.log(certificate.val);
                    if(certificate.val>=10000000000000){
                        layer.msg('只能输入1000亿以下的数据', {icon: 2, time: 1000});
                        certificate.val = 9999999999999;
                        return;
                    }
                    //console.log(this.valTotal == this.sendTotal)
                    if(this.valTotal == this.sendTotal && this.valTotal != 0){
                        this.dxMoney = this.DX(this.valTotal);

                    }else{
                        this.dxMoney = '';
                    }
                },
                /*---保存按钮-*/
                keepBtn:function(){
                    /*------条件符合的情况下传给后端----*/
                    /*----摘要会计科目同行必填---不能少于2行数据----*/
                    var zhInfo = [];
                    var kjkmInfo = [];
                    var jfMoney = [];
                    var dfMoney = [];
                    var kjkmId = [];
                    for(var i in this.certificateTable){
                        jfMoney.push(this.certificateTable[i].val);
                        dfMoney.push(this.certificateTable[i].sendVal);
                        if(this.certificateTable[i].zy != ''){
                            zhInfo.push(this.certificateTable[i].zy);
                        }
                        if(this.certificateTable[i].codeInput != ''){
                            kjkmInfo.push(this.certificateTable[i].codeInput);
                            kjkmId.push(this.certificateTable[i].account_id)
                        }
                        if(this.certificateTable[i].zy != '' && this.certificateTable[i].codeInput == ''){
                            layer.msg('请输入科目', {icon: 2, time: 1000});
                            return;
                        }
                        if(this.certificateTable[i].zy == '' && this.certificateTable[i].codeInput != ''){
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
                    if(this.certificateTable[0].val == '' && this.certificateTable[0].sendVal == ''){
                        layer.msg('第一张凭证金额异常,请核对', {icon: 2, time: 1000});
                        return;
                    }
                    var data = [];
                    for (var i in zhInfo){
                        data.push({
                            'zhaiyao':zhInfo[i],
                            'kuaijikemu_id':kjkmId[i],
                            'debit_money':jfMoney[i],
                            'credit_money':dfMoney[i]
                        });
                    }
                    var params = {
                        '_token': '{{ csrf_token()  }}', 'voucher_num':this.numVal, 'attach':this.attachPage,
                        'voucher_date':curData,'voucher_source':'14','total_debit_money':this.valTotal,
                        'total_credit_money':this.sendTotal,'total_cn':this.dxMoney,'id':this.vid,'items':data
                    };
                    /*-----保存时显示的按钮不同----*/
                    var _this = this;
                    _this.keeps = false;
                    _this.loading = true;
                    _this.$http.post('{{route('voucher.make')}}',params).then(function(response){
                        response = response.body;
                        if(response.status == 1){
                            _this.ids = response.data.id;
                            _this.keepShow = false;
                            _this.auditeShow = true;
                            //_this.keeps = false;

                            _this.vid = response.data.id;
                            layer.msg(response.info, {icon: 1, time: 1000});


                            let data = {
                                '_token': '{{ csrf_token()  }}',
                                'voucher_id':_this.vid
                            };
                            _this.$http.post('{{ route('voucher.api_get_simple_voucher') }}', data).then(function (response) {
                                response = response.body;
                                _this.create_by = response.data.create_by;
                                _this.create_at = response.data.create_at;
                            });
                        }

                        setTimeout(function(){_this.loading = false;_this.keeps = true;_this.keepShow = false;_this.addShow = true;}, 1200);
                    })
                },
                /*----审核按钮----*/
                auditeBtn:function(){
                    /*------条件符合的情况下传给后端----*/
                    var _this = this;
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
                            //input disabled
                            _this.disableTrue = true;
                            //增加与删除的icon不显示
                            _this.sh = false;
                            //借方移除点击事件
                            _this.flag = false;
                            // 更新审核人和审核时间
                            let data = {
                                '_token': '{{ csrf_token()  }}',
                                'voucher_id':_this.vid
                            };
                            _this.$http.post('{{ route('voucher.api_get_simple_voucher') }}', data).then(function (response) {
                                response = response.body;
                                _this.review_by = response.data.review_by;
                                _this.review_at = response.data.review_at;
                            });
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
                        jfMoney.push(this.certificateTable[i].val);
                        dfMoney.push(this.certificateTable[i].sendVal);
                        if(this.certificateTable[i].zy != ''){
                            zhInfo.push(this.certificateTable[i].zy);
                        }
                        if(this.certificateTable[i].codeInput != ''){
                            kjkmInfo.push(this.certificateTable[i].codeInput)
                            kjkmId.push(this.certificateTable[i].account_id)
                        }
                        if(this.certificateTable[i].zy != '' && this.certificateTable[i].codeInput == ''){
                            layer.msg('请输入科目', {icon: 2, time: 1000});
                            return;
                        }
                        if(this.certificateTable[i].zy == '' && this.certificateTable[i].codeInput != ''){
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
                    if(this.certificateTable[0].val == '' && this.certificateTable[0].sendVal == ''){
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
                            _this.sh = true;
                            //添加借方贷方的点击事件
                            _this.flag = true;
                            // 更新审核人和审核时间
                            _this.review_by = '';
                            _this.review_at = '';
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
                                //console.log(response)
                                if(response.status == 1){
                                    layer.close(index);
                                    layer.msg(response.info, {icon: 1, time: 1000});
                                    window.location.reload()
                                }else{
                                    layer.msg(response.info, {icon: 1, time: 1000});
                                }
                            });

                        }
                    });
                },
                /*----新增 刷新-------*/
                addNew:function(){
                    window.location.reload();
                },
                /*----保存并新增-------*/
                keepAdd:function(){
                    var params = this.condition();
                    this.$http.post('{{route('voucher.make')}}',params).then(function(response){
                        response = response.body;
                        if(response.status == 1){
                            layer.msg(response.info, {icon: 1, time: 1000});
                            //window.location.reload();

                            setTimeout(function(){window.location.reload();}, 1200);
                        }else{
                            layer.msg(response.info, {icon: 1, time: 1000});
                        }
                    });
                },
                // 输入框的键盘事件
                keyup: function (item, evt) {
                    var _this = this;
                    var e = evt || window.event;
                    if (e.keyCode == 190 || e.keyCode == 110) {
                        if (_this.mount % 2 == 0) {
                            _this.time1 = new Date().getTime();
                            // console.log(_this.time1)
                            _this.mount++;
                            _this.doublePoint = !_this.doublePoint;
                            setTimeout(function () {
                                _this.mount = 0;
                                _this.time1 = 0;
                                _this.time2 = 0;
                            }, 500)
                        } else {
                            _this.time2 = new Date().getTime();
                            if (_this.time2 - _this.time1 < 500) {
                                _this.copyLastAbsrtact(item, evt);
                                _this.mount = 0;
                                _this.time1 = 0;
                                _this.time2 = 0;
                            }
                        }
                    } else if (e.keyCode == 191 || e.keyCode == 111) {
                        if (_this.mount % 2 == 0) {
                            _this.time1 = new Date().getTime();
                            // console.log(_this.time1)
                            _this.mount++;
                            _this.doublePoint = !_this.doublePoint;
                            setTimeout(function () {
                                _this.mount = 0;
                                _this.time1 = 0;
                                _this.time2 = 0;
                            }, 500)
                        } else {
                            _this.time2 = new Date().getTime();
                            if (_this.time2 - _this.time1 < 500) {
                                _this.copyFirstAbstract(item, evt);
                                _this.mount = 0;
                                _this.time1 = 0;
                                _this.time2 = 0;
                            }
                        }
                    } else if(e.keyCode == '13'){
                        var cur = $(e.target);
                        cur.parents('td').next().find('input.celInput').focus()
                    }
                },
                // 复制上一条摘要的内容 快捷键 ..
                copyLastAbsrtact: function (item, evt) {
                    var _this = this;
                    var e = evt || window.event;
                    // 要执行的函数
                    /*console.log(item)*/
                    // console.log($(e.target.parentNode.parentNode.previousElementSibling).find('.abstract').val())
                    if ($(e.target.parentNode.parentNode.previousElementSibling).find('.abstract').val() != 'undefined') {
                        e.target.value = $(e.target.parentNode.parentNode.previousElementSibling).find('.abstract').val();
                        item.zy = $(e.target.parentNode.parentNode.previousElementSibling).find('.abstract').val()
                        // console.log(item)
                    }
                },
                // 复制第一条摘要的内容 快捷键 //
                copyFirstAbstract: function (item, evt) {
                    var _this = this;
                    var e = evt || window.event;
                    // console.log($('.abstract:first').val())
                    if($('.abstract:first').val() != 'undefined') {
                        e.target.value = $('.abstract:first').val();
                        item.zy = $('.abstract:first').val();
                    }
                },
                // 快捷键空格 交换借贷双方的值
                exchangeDebitAndCredit: function () {
                    var _this = this;
                    var e = window.event;
                    var index = $('.contentItem').index($(':focus').parent().parent()[0]);
                    var middleEle;
                    var middleData;

                    if ($(':focus').hasClass('debit')) {
                        // console.log($('.df')[index])
                        // $($('.df')[index]).click();
                        $($('.credit')[index]).click();
                        middleData = _this.certificateTable[index].val;
                        _this.certificateTable[index].val = _this.certificateTable[index].sendVal;
                        _this.certificateTable[index].sendVal = middleData;
                        middleEle = _this.certificateTable[index].jfVal;
                        _this.certificateTable[index].jfVal = _this.certificateTable[index].dfVal;
                        _this.certificateTable[index].dfVal = middleEle;
                    } else if ($(':focus').hasClass('credit')) {
                        // $($('.jf')[index]).click();
                        $($('.debit')[index]).click();
                        middleData = _this.certificateTable[index].val;
                        _this.certificateTable[index].val = _this.certificateTable[index].sendVal;
                        _this.certificateTable[index].sendVal = middleData;
                        middleEle = _this.certificateTable[index].jfVal;
                        _this.certificateTable[index].jfVal = _this.certificateTable[index].dfVal;
                        _this.certificateTable[index].dfVal = middleEle;

                    }
                    // console.log(_this.certificateTable[index])
                },
                // 新增科目
                showAddNewKm: function (certificate) {
                    var _this = this;
                    layer.open({
                        type: 1,
                        title: '新增科目',
                        skin: 'components',
                        shadeClose: true,
                        maxmin: false, //开启最大化最小化按钮
                        area: ['420px', '309px'],
                        content: $('#addNewKm'),
                        btn: ['确认', '取消'],
                        yes: function (index, layero) {
                            var param = {
                                name: vm_addNewKm.kmName,
                                balance_direction: vm_addNewKm.yueFangxiang,
                                status: vm_addNewKm.status,
                                company_id: vm_addNewKm.companyid,
                                pid: vm_addNewKm.pid,
                                type: vm_addNewKm.type,
                                level: vm_addNewKm.level
                            }
                            // console.log(param)
                            _this.$http.post('/book/account_subject', param).then(function (response) {
                                if (response.status == 200) {
                                    vm_addNewKm.data1 = '';
                                    vm_addNewKm.kmName = '';
                                    vm_addNewKm.yueFangxiang = '';
                                    vm_addNewKm.status = '';
                                    vm_addNewKm.companyid = '';
                                    vm_addNewKm.pid = '';
                                    vm_addNewKm.type = '';
                                    vm_addNewKm.level = '';
                                    _this.getVoice();

                                    // certificate.balance = true;
                                    // certificate.codeInput = vm_addNewKm.data1;
                                    // certificate.account_id = vm_addNewKm.pid;
                                    // this.liIndex = 0;

                                }
                                // console.log(response)
                            })
                            layer.close(index)
                        }
                    })
                },
            },

            //通过计算属性过滤数据
            computed:{
                list: function(){
                    var arrByZM = [];
                    // console.log(this.newAdds);
                    for (var i=0;i<this.newAdds.length;i++){
                        if((this.newAdds[i].number + this.newAdds[i].name).search(this.certificateTable[this.curIndex].codeInput) != -1){
                            arrByZM.push(this.newAdds[i]);
                            // console.log(this.newAdds[i].number + this.newAdds[i].name)
                        }
                    }
                    // console.log(arrByZM)
                    return arrByZM;
                }
            }
        })
    </script>
    <div id="addNewKm" class="components" style="display: none;">
        <div class="content">
            <div class="inputKm clearfix">
                <span class="fl" style="color:red">*</span>
                <label class="fl">上级科目:</label>
                <div class="rzkm fl" @click="spreadOption()" ref="option1">
                    <input type="text" class="fl lastClass" @keyup.enter="enterValue()" v-model="data1" :value="data1">
                    <span class="fl">
                        <i class="iconfont icon-xialazhishijiantou"></i>
                    </span>
                    <ul class="items showTitle" v-show="flag1">
                        <li v-for="(item, index1) in list" :class="{'on':index1 == liIndex}" @click="selectRzkmItem(item)" @mouseover="addOnClass($event)" :key="item.id"
                            :data-id="item.id"
                            :data-direction="item.balance_direction"
                            :data-status="item.status"
                            :data-type="item.type"
                            :data-level="item.level">
                            @{{item.number+item.name}}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="inputKm clearfix">
                <span class="fl" style="color:red">*</span>
                <label class="fl">科目名称:</label>
                <input type="text" class="fl newKmName" v-model="kmName">
            </div>
        </div>
    </div>
    <script>
        var vm_addNewKm = new Vue({
            'el': '#addNewKm',
            data: {
                liIndex: 0,
                flag1: false,
                data1: '',
                kjkmOptions: [],
                kmName: '',
                yueFangxiang: '',
                status: '',
                companyid: '',
                pid: '',
                type: '',
                level: ''
            },
            mounted: function () {
                this.clickBlank();

                // document.onkeydown = function (evt) {
                //     var e = window.event || evt;
                //     var key = e.keyCode;
                //     if (e.keyCode == 38) {
                //         if (_this.liIndex > 0) {
                //             _this.liIndex--;
                //             if ($('.item1 li.on').offsetTop < $('.item1').scrollTop + 55) {
                //                 $('.item1').scrollTop = ($('.item1 li.on').offsetTop - 24)
                //             }
                //         } else if (_this.liIndex == 0) {
                //             _this.liIndex = _this.list.length - 1;
                //             $('.item1').scrollTop = ($('.item1 li:last-child').offsetTop - 24)
                //
                //         }
                //     } else if (e.keyCode == 40) {
                //         if (_this.liIndex < _this.list.length - 1) {
                //             _this.liIndex++;
                //             if ($('.item1 li.on').offsetTop > $('.item1').scrollTop + 72) {
                //                 $('.item1').scrollTop = ($('.item1 li.on').offsetTop - 84)
                //             }
                //         } else if (_this.liIndex == _this.list.length - 1) {
                //             _this.liIndex = 0;
                //             $('.item1').scrollTop = ($('.item1 li:first-child').offsetTop - 24)
                //         }
                //     }
                // }
            },
            created: function () {
                var _this = this;
                layui.use(['form', 'jquery', 'layer'], function () {
                    var form = layui.form;
                    var layer = layui.layer;
                })
                // this.render();

            },
            methods: {
                // 展开下拉框
                spreadOption: function () {
                    this.flag1 = !this.flag1;
                    if (flag1 = true) {
                        this.render()
                    }
                },
                render: function () {
                    var _this = this;
                    _this.$http.get('{{ route('account_subject.index') }}').then(function (response) {
                        if (response.status == 200) {
                            _this.kjkmOptions = response.body;
                            // console.log(_this.kjkmOptions)
                        }
                    })
                },
                // 鼠标悬停加类名
                addOnClass: function (e) {
                    $(e.target).siblings().removeClass('on')
                    e.target.className = "on";
                    this.liIndex = $('.item1').find('li').index(e.target)
                },
                selectRzkmItem: function (item) {
                    var _this = this;
                    _this.data1 = item.number+item.name;
                    _this.pid = item.id;
                    _this.yueFangxiang = item.balance_direction;
                    _this.status = item.status;
                    _this.companyid = '{{ \App\Entity\Company::sessionCompany()->id }}';
                    _this.type = item.type;
                    _this.level = Number(item.level) + 1;
                    // $(e.target).addClass('active').siblings().removeClass('active');
                    _this.liIndex = 0;
                    // $('.item1').scrollTop = 0;
                },
                // 点击空白处收起下拉框
                clickBlank: function () {
                    var _this = this;
                    document.addEventListener('click', function (e) {
                        if (!_this.$refs.option1.contains(e.target)) {
                            _this.flag1 = false;
                        }
                    })

                },
                // 敲回车直接上值
                enterValue: function () {
                    $('.items .on').click()
                }
            },
            //通过计算属性过滤数据
            computed:{
                list: function(){
                    var arrByZM = [];
                    //console.log(this.curIndex);
                    for (var i=0; i < this.kjkmOptions.length; i++){
                        if((this.kjkmOptions[i].number + this.kjkmOptions[i].name).search(this.data1) != -1){
                            arrByZM.push(this.kjkmOptions[i]);
                        }
                    }
                    return arrByZM;
                }
            }
        })
    </script>
@endsection