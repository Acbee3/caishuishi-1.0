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
    <link rel="stylesheet" href="/css/book/cwcl/pzhz.css">
    <!--[if lt IE 9]>
    <script src="/common/js/html5shiv.min.js"></script>
    <script src="/common/js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="pzhz">
    <!--头部-->
    <div class="header">
        <div class="left fl">
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
                        <a href="javascript:;" class="confirm fr" @click="clickConfirm">确定</a>
                        <a href="javascript:;" class="reset fr">重置</a>
                    </div>
                </div>
            </div>
            <a href="javascript:;" class="refresh">
                <i class="iconfont icon-shuaxin"></i>
            </a>
            <span class="pzsAndFjs">凭证总张数：<span>49</span>张  附件总张数：<span>52</span>张</span>
        </div>
        <div class="right fr">
            <span class="excel">导出EXCEL</span>
            <a class="print">打印</a>
        </div>
    </div>
    <!--表格-->
    <div class="tabledata">
        <div class="tableheader">
            <table style="table-layout: fixed;" cellspacing="0" cellpadding="0">
                <colgroup>
                    <col style="min-width: 240px; width: 396px;" col="0">
                    <col style="min-width: 364px; width: 620px;" col="1">
                    <col style="min-width: 190px; width: 314px;" col="2">
                    <col style="min-width: 190px; width: 314px;" col="3">
                </colgroup>
                <tr>
                    <th>科目编码</th>
                    <th>科目名称</th>
                    <th>借方金额</th>
                    <th>贷方金额</th>
                </tr>
            </table>
        </div>
        <div class="tablebody">
            <table style="table-layout: fixed;" cellspacing="0" cellpadding="0">
                <colgroup>
                    <col style="min-width: 240px; width: 396px;" col="0">
                    <col style="min-width: 364px; width: 620px;" col="1">
                    <col style="min-width: 190px; width: 314px;" col="2">
                    <col style="min-width: 190px; width: 314px;" col="3">
                </colgroup>
                <tr style="visibility: hidden">
                    <th>科目编码</th>
                    <th>科目名称</th>
                    <th>借方金额</th>
                    <th>贷方金额</th>
                </tr>
                <tr v-for="item in formData" :key="item">
                    <td class="tl">
                        {{--<a href="javascript:;">@{{item.kmbm}}</a>--}}
                        <a data-href="{{ route('sub_ledger.list') }}" data-title="明细账" href="javascript:void(0);" @click="goMxz(item)" ref="curDom">@{{item.kmbm}}</a>
                    </td>
                    <td class="tl">@{{item.kmmc}}</td>
                    <td class="tr">@{{item.jfje}}</td>
                    <td class="tr">@{{item.dfje}}</td>
                </tr>
            </table>
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
    new Vue ({
        'el': '.pzhz',
        data: {
            flag1: false,
            flag2: false,
            flag3: false,
            // 科目级次
            num1: 1,
            num2: 1,
            // 会计期间
            date1: '2017年1期',
            date2: '2017年2期',
            formData: [
                {
                    'kmbm': '1122',
                    'kmmc': '应收账款',
                    'jfje': '33333.00',
                    'dfje': '100.00'
                }
            ]
        },
        created: function () {

        },
        methods: {
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
            // 点击确定后的方法
            clickConfirm: function () {
                $('#date1').text(this.date1);
                $('#date2').text(this.date2);
                this.flag1 = false;
            },
            // 选择时间
            selectDate1: function (e) {
                this.date1 = e.target.innerText;
            },
            selectDate2: function (e) {
                this.date2 = e.target.innerText;
            },
            // 跳转到明细账
            goMxz: function (item) {
                localStorage.setItem('km_code',item.account_subject_number);
                localStorage.setItem('start',this.getDate);
                localStorage.setItem('end',this.getDate1);
                var curDom = this.$refs.curDom;
                top.Hui_admin_tab(curDom)
            }
        }
    })
</script>
</body>
</html>