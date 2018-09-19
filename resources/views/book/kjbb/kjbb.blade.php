<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>会计报表</title>
    <!--公用-->
    <link rel="stylesheet" href="/common/css/reset.css">
    <link rel="stylesheet" href="/common/fonts/iconfont.css">
    <link rel="stylesheet" href="/common/layui/css/layui.css">
    <link rel="stylesheet" href="/common/css/table.css">
    <!--会计报表-->
    <link rel="stylesheet" href="/css/book/kjbb/kjbb.css">
    <!--[if lt IE 9]-->
    <script src="/common/js/html5shiv.min.js"></script>
    <script src="/common/js/respond.min.js"></script>
    <!--[endif]-->
</head>

<body>
<div class="box" v-cloak>
    <div class="header">
        <ul>
            {{--<li><a href="javascript:;">excel导出</a></li>--}}
            {{--<li><span>|</span></li>--}}
            {{--<li><a href="javascript:;">PDF导出</a></li>--}}
            {{--<li><span>|</span></li>--}}
            {{--<li><a href="javascript:;">打印</a></li>--}}
            {{--<li><span>|</span></li>--}}
            <li>
                <i class="iconfont">&#xe627;</i>
                <a href="javascript:;" @click="location.reload()">刷新</a>
            </li>
            <li><span>|</span></li>
        </ul>
        <div class="selectedText">
            <span>@{{clickShow}}</span>
        </div>
    </div>
    <div class="tableContent" v-if="tabContents[0] == num" id="0">
        <table style="table-layout: fixed;width:1264px;" cellspacing="0" cellpadding="0" @click="clickShow1($event)">
            <colgroup>
                <col style="width: 222px" col="0">
                <col style="width: 43px" col="1">
                <col style="width: 169px" col="2">
                <col style="width: 173px" col="3">
                <col style="width: 223px" col="4">
                <col style="width: 56px" col="5">
                <col style="width: 189px" col="6">
                <col style="width: 189px" col="7">
            </colgroup>
            <tbody>
            <tr class="btd" style="height: 34px;border: none;">
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"><h3>资产负债表</h3></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="btd" style="height: 28px;border: none;">
                <td class="textL" colspan="3">单位名称：@{{ tableData1ComName }}</td>
                <td colspan="2">@{{ tableData1Period }}</td>
                <td></td>
                <td></td>
                <td class="textR">单位：元</td>
            </tr>
            <tr v-for="(item, index) in tableData1.slice(0, 1)">
               <td>@{{ item[0] }}</td>
               <td>@{{ item[1] }}</td>
               <td>@{{ item[2] }}</td>
               <td>@{{ item[3] }}</td>
               <td>@{{ item[4] }}</td>
               <td>@{{ item[5] }}</td>
               <td>@{{ item[6] }}</td>
               <td>@{{ item[7] }}</td>
            </tr>
            <tr v-for="(item, index) in tableData1.slice(1)">
                <td class="textL">@{{ item[0] }}</td>
                <td class="textC">@{{ item[1] }}</td>
                <td class="textR">@{{ Number(item[2]) == 0 ? '' : item[2] }}</td>
                <td class="textR">@{{ Number(item[3]) == 0 ? '' : item[3] }}</td>
                <td class="textL">@{{ item[4] }}</td>
                <td class="textC">@{{ item[5] }}</td>
                <td class="textR">@{{ Number(item[6]) == 0 ? '' : item[6] }}</td>
                <td class="textR">@{{ Number(item[7]) == 0 ? '' : item[7] }}</td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="tableContent" v-if="tabContents[1] == num" id="1">
        <table style="table-layout: fixed;width:1217px;" cellspacing="0" cellpadding="0" @click="clickShow1($event)">
            <colgroup>
                <col style="width: 330px" col="0">
                <col style="width: 45px" col="1">
                <col style="width: 306px" col="2">
                <col style="width: 262px" col="3">
                <col style="width: 58px" col="4">
                <col style="width: 72px" col="5">
                <col style="width: 72px" col="6">
                <col style="width: 72px" col="7">
            </colgroup>
            <tbody>
            <tr class="btd" style="height: 34px;border: none;">
                <td></td>
                <td></td>
                <td><h3>利润表</h3></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="btd" style="height: 28px;border: none;">
                <td class="textL" colspan="2">单位名称：@{{ tableData2ComName }}</td>
                <td>@{{ tableData2Period }}</td>
                <td class="textR">单位：元</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr v-for="(item, index) in tableData2.slice(0, 1)">
                <td>@{{ item[0] }}</td>
                <td>@{{ item[1] }}</td>
                <td>@{{ item[2] }}</td>
                <td>@{{ item[3] }}</td>
            </tr>
            <tr v-for="(item, index) in tableData2.slice(1)">
                <td class="textL">@{{ item[0] }}</td>
                <td class="textC">@{{ item[1] }}</td>
                <td class="textR">@{{ Number(item[2]) == 0 ? '' : item[2] }}</td>
                <td class="textR">@{{ Number(item[3]) == 0 ? '' : item[3] }}</td>
            </tr>

            </tbody>
        </table>
    </div>
    <!--
    <div class="tableContent" v-if="tabContents[2] == num" id="2">
        <table style="table-layout: fixed;width:1174px;" cellspacing="0" cellpadding="0" @click="clickShow1($event)">
            <colgroup>
                <col style="width: 352px" col="0">
                <col style="width: 56px" col="1">
                <col style="width: 246px" col="2">
                <col style="width: 246px" col="3">
                <col style="width: 58px" col="4">
                <col style="width: 72px" col="5">
                <col style="width: 72px" col="6">
                <col style="width: 72px" col="7">
            </colgroup>
            <tbody>
            <tr class="btd" style="height: 34px;border: none;">
                <td></td>
                <td></td>
                <td><h3>现金流量表</h3></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="btd" style="height: 28px;border: none;">
                <td class="textL" colspan="2">单位名称：南京弘林信息技术有限公司</td>
                <td>2018-06-30</td>
                <td class="textR">单位：元</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>项   目</td>
                <td>行次</td>
                <td>本年累计金额</td>
                <td>本月金额	</td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
            </tr>
            <tr v-for="(item, index) in tableData3" :key="index">
                <td class="textL">@{{item.xm}}</td>
                <td>@{{item.row1}}</td>
                <td class="textR">@{{item.bnljje}}</td>
                <td class="textR">@{{item.byje}}</td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
            </tr>

            </tbody>
        </table>
    </div>
    <div class="tableContent" v-if="tabContents[3] == num" id="3">
        <table style="table-layout: fixed;width:1343px;" cellspacing="0" cellpadding="0" @click="clickShow1($event)">
            <colgroup>
                <col style="width: 325px" col="0">
                <col style="width: 53px" col="1">
                <col style="width: 170px" col="2">
                <col style="width: 170px" col="3">
                <col style="width: 170px" col="4">
                <col style="width: 170px" col="5">
                <col style="width: 170px" col="6">
                <col style="width: 72px" col="7">
                <col style="width: 72px" col="7">
                <col style="width: 72px" col="7">
                <col style="width: 72px" col="7">
            </colgroup>
            <tbody>
            <tr class="btd" style="height: 34px;border: none;">
                <td></td>
                <td></td>
                <td></td>
                <td><h3>利润表</h3></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr class="btd" style="height: 28px;border: none;">
                <td class="textL" colspan="5">单位名称：南京弘林信息技术有限公司</td>
                <td></td>
                <td class="textR">单位：元</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>项   目</td>
                <td>行次</td>
                <td>本年累计</td>
                <td>第一季度</td>
                <td>第二季度</td>
                <td>第三季度</td>
                <td>第四季度</td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
            </tr>
            <tr v-for="(item, index) in tableData4" :key="index">
                <td class="textL">@{{item.xm}}</td>
                <td>@{{item.row1}}</td>
                <td class="textR">@{{item.d1jd}}</td>
                <td class="textR">@{{item.d2jd}}</td>
                <td class="textL">@{{item.d3jd}}</td>
                <td class="textL">@{{item.d4jd}}</td>
                <td class="textL"></td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
                <td class="b0"></td>
            </tr>
            </tbody>
        </table>
    </div>
    -->
    <div class="footer">
        <ul>
            <li v-for="(item, index) in tabs" :key="index" :class="{active:index == num}" @click="tab(item, index)">
                @{{item}}
            </li>
        </ul>
    </div>
</div>
<!--公用-->
{{--<script src="/common/js/vue.min.js"></script>--}}
{{--<script src="/common/js/jquery-2.2.4.js"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="/common/layui/layui.js" charset="utf-8"></script>
<script src="/common/vue-resource/dist/vue-resource.js"></script>
<script>
    new Vue({
        'el': '.box',
        data: {
            // 头部点击显示
            clickShow: '',
            // 标签页切换相关
            tabs: {
                0: '资产负债表',
                1: '利润表',
                // 2: '现金流量表',
                // 3: '利润表季报'
            },
            tabContents: [0, 1, 2, 3],
            num: 0,
            // 表格数据
            tableData1: '',
            //     {
            //     "info": "操作成功",
            //     "status": 1,
            //     "data": {
            //         "zc": "应收账款",
            //         "row1": "4",
            //         "qmye1": "59,893.00",
            //         "ncye1": "26,560.00",
            //         "fzProfit": "预收帐款",
            //         "row2": "34",
            //         "qmye2": "147,147.00",
            //         "ncye2": "146,287.00"
            //     }
            // },
            tableData1ComName: '',
            tableData1Period: '',
            tableData2: '',
            tableData2ComName: '',
            tableData2Period: '',
            tableData3: {
                "info": "操作成功",
                "status": 1,
                "data": {
                    "xm": "销售产成品、商品、提供劳务收到的现金",
                    "row1": "1",
                    "bnljje": "-30,611.00",
                    "byje": "-30,611.00"
                }
            },
            tableData4: {
                "info": "操作成功",
                "status": 1,
                "data": {
                    "xm": "营业税金及附加",
                    "row1": "4",
                    "bnlj": "59,893.00",
                    "d1jd": "26,560.00",
                    "d2jd": "预收帐款",
                    "d3jd": "34",
                    "d4jd": "147,147.00"
                }
            }
        },
        created: function () {
            this.render1()
        },
        methods: {
            // 渲染函数
            render: function () {
                // console.log(this.tableData.data)
            },
            tab: function (item, index) {
                // 标签页依据 num 切换
                this.num = index;
                if (index == 0) {
                    this.render1()
                } else if (index == 1) {
                    this.render2()
                } else if (index == 2) {
                    this.render3()
                } else if (index == 3) {
                    this.render4()
                }
            },
            clickShow1 : function (e) {
                var _this = this;
                var ele = e || window.event;
                var target = ele.target;
                // console.log(target)
                if(target.nodeName.toLowerCase() == 'td'){
                    // console.log(target.innerText);
                    _this.clickShow = target.innerText;
                }
            },
            // 资产负债表
            render1: function () {
                var _this = this;
                _this.$http.get('/book/balanceSheet/index', {params: {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}'
                }}).then(function (response) {
                    // console.log(response.data.data.list);
                    if (response.data.status == 1) {
                        var data = response.data.data.list;
                        // // console.log(data);
                        // var str = '<colgroup><col style="width: 222px; text-align: left;" col="0"><col style="width: 43px" col="1"><col style="width: 169px" col="2"><col style="width: 173px" col="3"><col style="width: 223px" col="4"><col style="width: 56px" col="5"><col style="width: 189px" col="6"><col style="width: 189px" col="7"></colgroup>';
                        // _this.tableData1 = _this.setStrMarke(data, str, index);
                        // console.log(_this.tableData1);


                        _this.tableData1 = data;
                        _this.tableData1ComName = response.data.data.companyName;
                        _this.tableData1Period = response.data.data.period;
                    }
                })
            },
            // 利润表
            render2: function () {
                var _this = this;
                _this.$http.get('/book/profitSheet/index', {params: {
                        company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                        fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}'
                    }}).then(function (response) {
                    // console.log(response.data.data.list);
                    if (response.data.status == 1) {
                        var data = response.data.data.list;
                        _this.tableData2 = data;
                        _this.tableData2ComName = response.data.data.companyName;
                        _this.tableData2Period = response.data.data.period;
                    }
                })
            },
            // 现金流量表
            render3: function () {

            },
            // 利润表季报
            render4: function () {

            },
            // 资产负债表的字符串表格处理
            setStrMarke: function (str, subStr, indexs) {
                // str: 原字符串; subStr: 需要插入的字符串; indexs: 要插入的所有位置的索引;
                var string = str;
                for (var i=0; i < indexs.length; i++){
                    string = string.substr(0, indexs[i]) + subStr + string.substr(indexs[i], string.length);
                }
                return string;
            }
        }
    })
</script>
</body>
</html>