@extends('book.layout.base')

@section('title')新增薪酬@endsection

@section('css')
    @parent
    <!--薪酬表-->
    <link rel="stylesheet" href="{{asset("css/book/paid/payroll.css")}}">
@endsection

@section('content')
<div class="formWrapper">
    <form class="layui-form payrollForm">
        <div class="payrollForm-item">
            <label class="payrollLabel">新增薪酬类型:</label>
            <select id="salary_select" name="salary_select" lay-filter="moneyType" >
                {!! $salary_options !!}
            </select>
        </div>
        <div class="payrollForm-item moneyDisabled">
            <label class="payrollLabel">薪酬所属期:</label>
            <div class="itemRight">
                <input type="text" id="testDate">
                <i class="iconfont testDate-1">&#xe616;</i>
            </div>
        </div>
        <div class="payrollForm-item moneyDate">
            <label class="payrollLabel">薪酬所属期:</label>
            <div class="itemRight">
                <input type="text" disabled>
                <i class="iconfont ">&#xe616;</i>
            </div>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">薪酬所属期起:</label>
            <div class="itemRight">
                <input type="text" id="testDateSatrt">
                <i class="iconfont" id="testDateSatrt-1">&#xe616;</i>
            </div>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">薪酬所属期止:</label>
            <div class="itemRight">
                <input type="text" id="testDateEnd">
                <i class="iconfont" id="testDateEnd-1">&#xe616;</i>
            </div>
        </div>
        <div class="payrollForm-item payType">
            <label class="payrollLabel ">支付方式:</label>
            <select id="pay_select" name="pay_select" lay-filter="blankType">
                {!! $pay_options !!}
            </select>
        </div>
        <div class="payrollForm-item zcMoney_1">
            <label class="payrollLabel">银行账户:</label>
            <select id="bank_select" name="bank_select" >
                {!! $bank_options !!}
            </select>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">企业类型:</label>
            <select>
                <option value="">个体工商户</option>
                <option value="0">承包、承租经营单位</option>
                <option value="1">个人独资企业</option>
                <option value="2">合伙企业</option>
            </select>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">征收方式:</label>
            <select>
                <option value="">核定应税所得率征收</option>
                <option value="0">核定应纳税所得额征收</option>
            </select>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    layui.use(['layer','form','laydate','jquery'],function(){
        var laydate = layui.laydate;
        var layer = layui.layer;
        var form = layui.form;
        laydate.render({
            elem: '#testDate'
            ,eventElem: '.testDate-1'
            ,trigger: 'click'
            ,type:'month'
        });
        laydate.render({
            elem: '#testDateYear'
            ,eventElem: '#testDateYear-1'
            ,trigger: 'click'
            ,type:'year'
        });
        laydate.render({
            elem: '#testDateSatrt'
            ,eventElem: '#testDateSatrt-1'
            ,trigger: 'click'
            ,type:'month'
        });
        laydate.render({
            elem: '#testDateEnd'
            ,eventElem: '#testDateEnd-1'
            ,trigger: 'click'
            ,type:'month'
        });
        /*----监听新增薪酬类型----*/
        form.on('select(moneyType)', function(data){
            var upOption = data.value
            /*---moneyDisabled所属期可点，moneyDate所属期不可点，payType支付方式，productMenu所属期起所属期止企业类型征收方式---*/
            switch(data.value){
                 case '0':
                     $('.moneyDisabled').hide()
                     $('.moneyDate').show()
                     $('.payType').show()
                     $('.productMenu').hide()
                     break;
                 case '1':
                     $('.moneyDisabled').show()
                     $('.moneyDate').hide()
                     $('.payType').show()
                     $('.productMenu').hide()
                     break;
                 case '2':
                     $('.moneyDisabled').hide()
                     $('.moneyDate').show()
                     $('.payType').show()
                     $('.productMenu').hide()
                     break;
                 case '3':
                     $('.moneyDisabled').show()
                     $('.moneyDate').hide()
                     $('.payType').show()
                     $('.productMenu').hide()
                     break;
                 case '4':
                     $('.moneyDisabled').hide()
                     $('.moneyDate').show()
                     $('.payType').show()
                     $('.productMenu').hide()
                     break;
                 case '5':
                     $('.moneyDisabled').hide()
                     $('.moneyDate').show()
                     $('.payType').hide()
                     $('.productMenu').hide()
                     break;
                 case '6':
                     $('.moneyDisabled').hide()
                     $('.moneyDate').hide()
                     $('.payType').hide()
                     $('.productMenu').show()
                     break;
                 case '7':
                     $('.moneyDisabled').hide()
                     $('.moneyDate').hide()
                     $('.payType').hide()
                     $('.productMenu').show()
                     break;

             }
        });
        /*-----支付方式为银行---*/
        form.on('select(blankType)', function(data){
            if(data.value == 2){
                $('.zcMoney_1').show()
            }else{
                $('.zcMoney_1').hide()
            }
        });
    })
    var a =  new Vue({
        'el': '.formWrapper',
        data: {
            options: ''
        },
        created:function(){
            //this.getUpData();
        },
        methods: {
            getUpData:function(){
                //console.log(this.selected)
            }
        }
    })
</script>
@endsection