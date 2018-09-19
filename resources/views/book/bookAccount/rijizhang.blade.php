<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>凭证汇总</title>
    <!--公用-->
    <link rel="stylesheet" href="/common/css/reset.css">
    <link rel="stylesheet" href="/common/fonts/iconfont.css">
    <link rel="stylesheet" href="/common/layui/css/layui.css">
    <link rel="stylesheet" href="/common/css/table.css">
    <!--日记账-->
    <link rel="stylesheet" href="/css/book/cwcl/rijizhang.css">
    <!--[if lt IE 9]>
    <script src="/common/js/html5shiv.min.js"></script>
    <script src="/common/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="rijizhang">
    <div class="tab">
        <ul>
            <li v-for="(item, index) in tabs" :key="index" :class="{active:index == num}" @click="tab(item, index)">
                @{{item}}
            </li>
        </ul>
    </div>
    <div class="tableContent" v-show="tabContents[0] == num" id="0">
        <div class="banner">
            <div class="left fl">
                <span class="fl">现金日记账：</span>
                <div class="rq fl">
                    <div class="dateDuration" @click="flag1 = !flag1">
                        <span id="date1">2017年第12期</span> ~ <span id="date2">2018年第3期</span>
                    </div>
                    <!--选择区间-->
                    <div class="dataOptions" v-show="flag1">
                        <div class="item">
                            <span>会计期间:</span>
                            <div class="duration fr">
                                <!--开始-->
                                <div class="start fl">
                                    <div class="show fl" @click="flag2 = !flag2">
                                        <input type="text" readonly class="fl" :value="date1">
                                        <span class="fl"></span>
                                        <ul class="shijian" v-show="flag2" @click="selectDate1($event)">
                                            <li>2017年1期</li>
                                            <li>2017年2期</li>
                                            <li>2017年3期</li>
                                            <li>2017年4期</li>
                                            <li>2017年5期</li>
                                            <li>2017年6期</li>
                                            <li>2017年7期</li>
                                            <li>2017年8期</li>
                                            <li>2017年9期</li>
                                            <li>2017年10期</li>
                                            <li>2017年11期</li>
                                            <li>2017年12期</li>
                                            <li>2018年1期</li>
                                            <li>2018年2期</li>
                                            <li>2018年3期</li>
                                            <li>2018年4期</li>
                                        </ul>
                                    </div>
                                    <div class="date"></div>
                                </div>
                                <span class="fl" style="font-size: 12px; margin: 0 2px;">至</span>
                                <!--结束-->
                                <div class="end fl">
                                    <div class="show fl" @click="flag3 = !flag3">
                                        <input type="text" readonly class="fl" :value="date2">
                                        <span class="fl"></span>
                                        <ul class="shijian" v-show="flag3" @click="selectDate2($event)">
                                            <li>2017年1期</li>
                                            <li>2017年2期</li>
                                            <li>2017年3期</li>
                                            <li>2017年4期</li>
                                            <li>2017年5期</li>
                                            <li>2017年6期</li>
                                            <li>2017年7期</li>
                                            <li>2017年8期</li>
                                            <li>2017年9期</li>
                                            <li>2017年10期</li>
                                            <li>2017年11期</li>
                                            <li>2017年12期</li>
                                            <li>2018年1期</li>
                                            <li>2018年2期</li>
                                            <li>2018年3期</li>
                                            <li>2018年4期</li>
                                        </ul>
                                    </div>
                                    <div class="date"></div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <span>科目级次:</span>
                            <div class="kmjc fr">
                                <div class="jici fl">
                                    <input class="fl" type="text" readonly :value="num1">
                                    <div class="updown fr">
                                        <span class="up zzbutton fl" @click="increase1"></span>
                                        <span class="down zzbutton fl" @click="decrease1"></span>
                                    </div>
                                </div>
                                <span class="fl" style="font-size: 12px; margin: 0 2px;">至</span>
                                <div class="jici fl">
                                    <input class="fl" type="text" readonly :value="num2">
                                    <div class="updown fr">
                                        <span class="up zzbutton fl" @click="increase2"></span>
                                        <span class="down zzbutton fl" @click="decrease2"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <a href="javascript:;" class="confirm fr" @click="clickConfirm1">确定</a>
                            <a href="javascript:;" class="reset fr">重置</a>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="refresh">
                    <i class="iconfont icon-shuaxin"></i>
                </a>
            </div>
            <div class="right fr">
                <span class="excel">导出EXCEL</span>
                <a class="print">打印</a>
            </div>
        </div>
        <div class="tableBox">
            <div class="tableheader">
                <table cellspacing="0" cellpadding="0" style="height: 35px;">
                    <tr>
                        <th>日期</th>
                        <th>凭证字号</th>
                        <th>对方科目</th>
                        <th>摘要</th>
                        <th>借方</th>
                        <th>贷方</th>
                        <th>余额方向</th>
                        <th>余额</th>
                    </tr>
                </table>
            </div>
            <div class="tablecontent">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr style="visibility :hidden;">
                        <th>日期</th>
                        <th>凭证字号</th>
                        <th>对方科目</th>
                        <th>摘要</th>
                        <th>借方</th>
                        <th>贷方</th>
                        <th>余额方向</th>
                        <th>余额</th>
                    </tr>
                    <tr>
                        <td class="tl">2018-03-311</td>
                        <td class="tl">
                            <a href="javascript:;">
                                记-1
                            </a>
                        </td>
                        <td class="tl">累计折旧</td>
                        <td class="tl">计提3月资产折旧</td>
                        <td class="tr">0.04</td>
                        <td></td>
                        <td class="tc">借</td>
                        <td class="tr">2,489.20</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="tableContent" v-show="tabContents[1] == num" id="1">
        <div class="banner">
            <div class="left fl">
                <span class="fl">现金日记账：</span>
                <div class="rq fl">
                    <div class="dateDuration" @click="flag4 = !flag4">
                        <span id="date3">2017年第12期</span> ~ <span id="date4">2018年第3期</span>
                    </div>
                    <!--选择区间-->
                    <div class="dataOptions" v-show="flag4">
                        <div class="item">
                            <span>会计期间:</span>
                            <div class="duration fr">
                                <!--开始-->
                                <div class="start fl">
                                    <div class="show fl" @click="flag5 = !flag5">
                                        <input type="text" readonly class="fl" :value="date3">
                                        <span class="fl"></span>
                                        <ul class="shijian" v-show="flag5" @click="selectDate3($event)">
                                            <li>2017年1期</li>
                                            <li>2017年2期</li>
                                            <li>2017年3期</li>
                                            <li>2017年4期</li>
                                            <li>2017年5期</li>
                                            <li>2017年6期</li>
                                            <li>2017年7期</li>
                                            <li>2017年8期</li>
                                            <li>2017年9期</li>
                                            <li>2017年10期</li>
                                            <li>2017年11期</li>
                                            <li>2017年12期</li>
                                            <li>2018年1期</li>
                                            <li>2018年2期</li>
                                            <li>2018年3期</li>
                                            <li>2018年4期</li>
                                        </ul>
                                    </div>
                                    <div class="date"></div>
                                </div>
                                <span class="fl" style="font-size: 12px; margin: 0 2px;">至</span>
                                <!--结束-->
                                <div class="end fl">
                                    <div class="show fl" @click="flag6 = !flag6">
                                        <input type="text" readonly class="fl" :value="date4">
                                        <span class="fl"></span>
                                        <ul class="shijian" v-show="flag6" @click="selectDate4($event)">
                                            <li>2017年1期</li>
                                            <li>2017年2期</li>
                                            <li>2017年3期</li>
                                            <li>2017年4期</li>
                                            <li>2017年5期</li>
                                            <li>2017年6期</li>
                                            <li>2017年7期</li>
                                            <li>2017年8期</li>
                                            <li>2017年9期</li>
                                            <li>2017年10期</li>
                                            <li>2017年11期</li>
                                            <li>2017年12期</li>
                                            <li>2018年1期</li>
                                            <li>2018年2期</li>
                                            <li>2018年3期</li>
                                            <li>2018年4期</li>
                                        </ul>
                                    </div>
                                    <div class="date"></div>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <span class="fl">币种:</span>
                            <div class="bz fl">
                                <div class="show fl" @click="flag7 = !flag7">
                                    <input type="text" readonly class="fl" :value="currency">
                                    <span class="fl"></span>
                                    <ul class="shijian" v-show="flag7" @click="selectCurrency($event)">
                                        <li>人民币</li>
                                        <li>美元</li>
                                        <li>英镑</li>
                                        <li>日元</li>
                                        <li>法郎</li>
                                    </ul>
                                </div>
                                <div class="date"></div>
                            </div>
                        </div>
                        <div class="btns">
                            <a href="javascript:;" class="confirm fr" @click="clickConfirm2">确定</a>
                        </div>
                    </div>
                </div>
                <span class="fl yhzhanghu">银行账户：</span>
                <div class="zhanghu fl">
                    <div class="show fl" @click="flag8 = !flag8">
                        <input type="text" readonly class="fl" :value="zhanghu">
                        <span class="fl"></span>
                        <ul class="shijian" v-show="flag8" @click="selectZhanghu($event)">
                            <li>111111</li>
                            <li>222222</li>
                            <li>333333</li>
                            <li>444444</li>
                        </ul>
                    </div>
                </div>
                <a href="javascript:;" class="refresh">
                    <i class="iconfont icon-shuaxin"></i>
                </a>
            </div>
            <div class="right fr">
                <span class="excel">导出EXCEL</span>
                <a class="print">打印</a>
            </div>
        </div>
        <div class="tableBox">
            <div class="tableheader">
                <table cellspacing="0" cellpadding="0" style="height: 35px;">
                    <tr>
                        <th>日期</th>
                        <th>凭证字号</th>
                        <th>对方科目</th>
                        <th>摘要</th>
                        <th>借方</th>
                        <th>贷方</th>
                        <th>余额方向</th>
                        <th>余额</th>
                    </tr>
                </table>
            </div>
            <div class="tablecontent">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr style="visibility :hidden;">
                        <th>日期</th>
                        <th>凭证字号</th>
                        <th>对方科目</th>
                        <th>摘要</th>
                        <th>借方</th>
                        <th>贷方</th>
                        <th>余额方向</th>
                        <th>余额</th>
                    </tr>
                    <tr>
                        <td class="tl">2018-03-311</td>
                        <td class="tl">
                            <a href="javascript:;">
                                记-1
                            </a>
                        </td>
                        <td class="tl">累计折旧</td>
                        <td class="tl">计提3月资产折旧</td>
                        <td class="tr">0.04</td>
                        <td></td>
                        <td class="tc">借</td>
                        <td class="tr">2,489.20</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!--公用-->
{{--<script src="/common/js/vue.min.js"></script>--}}
{{--<script src="/common/js/jquery-2.2.4.js"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="/common/layui/layui.js" charset="utf-8"></script>
<script src="/common/vue-resource/dist/vue-resource.js"></script>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
    new Vue({
        'el': '.rijizhang',
        data: {
            // 标签页1
            flag1: false,
            flag2: false,
            flag3: false,
            // 科目级次
            num1: 1,
            num2: 1,
            // 会计期间
            date1: '2017年1期',
            date2: '2017年2期',
            // 标签页2
            flag4: false,
            flag5: false,
            flag6: false,
            flag7: false,
            flag8: false,
            // 会计期间
            date3: '2017年1期',
            date4: '2017年2期',
            // 币种
            currency: '人民币',
            // 账户
            zhanghu: '111111',
            // 标签页切换
            num: 0,
            tabs: {
                0: '现金日记账',
                1: '银行日记账'
            },
            tabContents: [0, 1],
        },
        created: function () {

        },
        methods: {
            // 渲染函数
            render: function () {
                console.log(this.tableData.data)
            },
            tab: function (item, index) {
                // 标签页依据 num 切换
                this.num = index;
            },
            // 数值增加减少
            increase1: function () {
                if (this.num1 <= 3) {
                    this.num1++;
                } else {
                    return false;
                }
            },
            decrease1: function () {
                if (this.num1 > 1) {
                    this.num1--;
                } else {
                    return false;
                }
            },
            increase2: function () {
                if (this.num2 <= 3) {
                    this.num2++;
                } else {
                    return false;
                }
            },
            decrease2: function () {
                if (this.num2 > 1) {
                    this.num2--;
                } else {
                    return false;
                }
            },
            // 选择时间
            selectDate1: function (e) {
                this.date1 = e.target.innerText;
            },
            selectDate2: function (e) {
                this.date2 = e.target.innerText;
            },
            selectDate3: function (e) {
                this.date3 = e.target.innerText;
            },
            selectDate4: function (e) {
                this.date4 = e.target.innerText;
            },
            // 选择币种
            selectCurrency: function (e) {
                this.currency = e.target.innerText;
            },
            // 选择账户
            selectZhanghu: function (e) {
                this.zhanghu = e.target.innerText;
            },
            // 点击确定后的方法
            clickConfirm1: function () {
                $('#date1').text(this.date1);
                $('#date2').text(this.date2);
                this.flag1 = false;
            },
            clickConfirm2: function () {
                $('#date3').text(this.date3);
                $('#date4').text(this.date4);
                this.flag4 = false;
            },
        }
    })
</script>
</body>
</html>