@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/certificate.css">
@endsection
@section('content')
<div class="recoardWrapper">
    <div class="recoardContain">
        <div class="recoardMenu">
            <div class="recoardHead">
                <h2>记账凭证</h2>
            </div>
            <div class="recoardBar">
                <div class="barMark">
                    <span class="text">记-</span>
                    <input v-model="num8" @change="handleChange" :min="1" :max="10">
                    <span class="text" style="margin-left:6px">号</span>
                </div>
                <div class="barDate">
                    <label>日期:</label>
                    <input type="text">
                </div>
                <div class="barYear">
                    <div>
                        <span>2018</span>
                        年第
                        <span>2</span>
                        期
                    </div>
                </div>
                <div class="barZ">
                    附单据
                    <input type="text" class="barZin">
                    张
                </div>
            </div>
        </div>
        <div class="recoardTable">
            <table cellspacing="0" border="0"  style="border-collapse:collapse;">
                <thead>
                <tr class="recoardTable-head">
                    <th width="28" class="border-Leftbottom"></th>
                    <th width="200" class="border-top">摘要</th>
                    <th class="border-top">会计科目</th>
                    <th width="224" class="border-top">
                        <span class="moneySend">借方金额</span>
                        <div class="borderTop">
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
                    <th width="224" class="border-top">
                        <span class="moneySend">贷方金额</span>
                        <div class="borderTop">
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
                            <a href="javascript:;" class="icon iconfont" title="增加分录1" @click="addUp(index)">&#xe6e8;</a>
                            <a href="javascript:;" class="icon iconfont" title="增加分录2" @click="addDown(index)">&#xe6e8;</a>
                        </div>
                    </td>
                    <td ref="tdContent">
                        <input type="text" class="celInput" ref="celInput">
                    </td>
                    <td>
                        <div @click="getAdd(certificate)">
                            <input type="text" class="celInput hiddenInput" :value="certificate.codeInput" v-model="certificate.codeInput">
                            <div class="getAdd" v-show="certificate.balance">
                                <span class="text">余额:</span>
                                <span ref="nums" class="nums">@{{certificate.money}}</span>
                            </div>
                        </div>
                        <div v-show="certificate.newAdd">
                            <ul class="showTitle">
                                <li v-for="item in newAdds" :key="item.index" @click="getNewAdds(item,certificate)">
                                    <span>@{{item.num}}</span>
                                    @{{item.name}}
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td class="colSend" @click="total(certificate)">
                        <input type="text" class="celInput bigWord entry" maxlength="11" :value="certificate.val" v-model="certificate.val" @blur="moneyVal(certificate,index)">
                    </td>
                    <td class="colSend">
                        <input type="text" class="celInput bigWord" maxlength="11" :value="certificate.sendVal" v-model="certificate.sendVal" @blur="sendVal(certificate,index)">
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
                        <span>12234556</span>
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
                <span>xxxxxx</span>
            </div>
            <div class="makeDate">
                <i> 制作时间:</i>
                <span>2018-05-24 10:33:32</span>
            </div>
            <div class="auth">
                <i>审核人:</i>
                <span>财税狮</span>
            </div>
            <div class="makeDate">
                <i>审核时间:</i>
                <span>2018-05-24 10:33:32</span>
            </div>
        </div>
    </div>
    <div class="footer">
        <a href="javascript:;" class="recoardItem">保存并新增(F12)</a>
        <a href="javascript:;" class="recoardItem">保存(Ctrl+S)</a>
    </div>
</div>

@endsection

@section('script')
    @parent
    <script>
        new Vue({
            'el': ".recoardWrapper",
            data:{
                num8: 1,
                value1: '',
                colVal: true,
                celInput: false,
                focusState: false,
                value: '',
                newAdd: false,
                newAdds: [
                    {
                        name: '10001库存现金',
                        num: 170.43
                    },
                    {
                        name: '10001银行存款银行存款_江苏紫金农村商业银行股',
                        num: 130.43
                    },
                    {
                        name: '10001库存现金',
                        num: 170.43
                    },
                ],
                balance: false,
                entryV: '',
                valTotal: '',
                sendTotal: '',
                certificateTable:[
                    {
                        codeInput: '',
                        money:'20.28',
                        val: '',
                        sendVal:'',
                        balance: false,
                        newAdd:false,
                        hiddenInput:false,
                        hiddenText: false,
                    },
                    {
                        codeInput: '',
                        money:'20.23',
                        val: '',
                        sendVal:'',
                        balance: false,
                        newAdd:false,
                        hiddenInput:false,
                        hiddenText: false,
                    },
                    {
                        codeInput: '',
                        money:'20.21',
                        val: '',
                        sendVal:'',
                        balance: false,
                        newAdd:false,
                        hiddenInput:false,
                        hiddenText: false,
                    }
                ]
            },
            mounted() {
            },
            methods: {
                handleChange(value) {
                },
                /*----增加上行----*/
                addUp:function(index) {
                    this.certificateTable.splice(index, 0,  {
                        codeInput: '',
                        money:'20.23',
                        val: '',
                        sendVal:'',
                        balance: false,
                        newAdd:false,
                        hiddenInput:false,
                        hiddenText: false,
                    });
                },
                /*----增加下行-------*/
                addDown:function(index){
                    this.certificateTable.splice((index+1), 0,  {
                        codeInput: '',
                        money:'20.23',
                        val: '',
                        sendVal:'',
                        balance: false,
                        newAdd:false,
                        hiddenInput:false,
                        hiddenText: false,
                    });
                },
                delTableLine:function(index){
                    /*--删除---最后一条的提示*/
                    let num = this.$refs.recoardTable.rows.length
                    if(num>1){
                        this.certificateTable.splice(index, 1)
                    }else{
                        alert("至少保留一条明细")
                    }
                    /*---当列和--*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for(var i in this.certificateTable){
                        valTotal += Number(this.certificateTable[i].val)
                        sendTotal += Number(this.certificateTable[i].sendVal)
                    }
                    this.valTotal = valTotal
                    this.sendTotal = sendTotal
                },
                getNewAdds(item,certificate) {
                    certificate.newAdd = false
                    certificate.balance = true
                    certificate.codeInput = item.num + item.name

                },
                getAdd(certificate) {
                    if (certificate.codeInput === '') {
                        certificate.newAdd = true
                    }
                },
                total(certificate) {
                    certificate.hiddenInput  = true
                    certificate.hiddenText = false
                },
                /*----借方与贷方input失去焦点的val*/
                moneyVal:function(certificate,index){
                    if(certificate.sendVal){
                        if(certificate.val != ''){
                            certificate.sendVal = ''
                        }

                    }
                    if (isNaN(certificate.val)) {
                        certificate.val = ''
                    }else{
                        certificate.val=Math.abs(certificate.val)
                        if(certificate.val>=1){
                            certificate.val =  certificate.val+ '00'
                        }else{
                            certificate.val = certificate.val.toFixed(2) * 100
                        }

                    }
                    /*---当列和--*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for(var i in this.certificateTable){
                        valTotal += Number(this.certificateTable[i].val)
                        sendTotal += Number(this.certificateTable[i].sendVal)
                    }
                    this.valTotal = valTotal
                    this.sendTotal = sendTotal
                },
                sendVal:function(certificate,index){
                    if(certificate.val){
                        if(certificate.sendVal != ''){
                            certificate.val = ''
                        }

                    }
                    if (isNaN(certificate.sendVal)) {
                        certificate.sendVal = ''
                    }else{
                        certificate.sendVal=Math.abs(certificate.sendVal)
                        if(certificate.sendVal>=1){
                            certificate.sendVal =  certificate.sendVal+ '00'
                        }else{
                            certificate.sendVal = certificate.sendVal.toFixed(2) * 100
                        }

                    }
                    /*---当列和--*/
                    var valTotal = 0;
                    var sendTotal = 0;
                    for(var i in this.certificateTable){
                        valTotal += Number(this.certificateTable[i].val)
                        sendTotal += Number(this.certificateTable[i].sendVal)
                    }
                    this.valTotal = valTotal
                    this.sendTotal = sendTotal
                }
            }
        })
    </script>
@endsection

