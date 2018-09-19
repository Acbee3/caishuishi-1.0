<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>折旧摊销</title>
    <!--公用-->
    <link rel="stylesheet" href="/common/css/reset.css">
    <link rel="stylesheet" href="/common/fonts/iconfont.css">
    <link rel="stylesheet" href="/common/layui/css/layui.css">
    <link rel="stylesheet" href="/common/css/table.css">
    <!--发票-->
    <link rel="stylesheet" href="/css/book/zc/zjtx.css?v=20180820">
    <!--[if lt IE 9]>
    <script src="/common/js/html5shiv.min.js"></script>
    <script src="/common/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="zjtx" v-cloak>
    <div class="tab">
        <ul class="tabTitle">
            <li v-for="(item, index) in tabs" :key="index"
                :class="{active:index == num}"
                @click="tab(item, index)">@{{item}}
            </li>
        </ul>
    </div>
    <div class="tabContent">
        <!-- 固定资产 -->
        <div class="tabItem" v-show="tabContent[0] == num">
            <!-- 凭证号 -->
        {{--<div class="pZnumber">凭证号：--}}
        {{--<a>data</a>--}}
        {{--</div>--}}
        <!--头部的类别和按钮-->
            <div class="listTop">
                <div class="zclb">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <span>资产类别:</span>
                            <select name="zclb" lay-filter="option1">
                                <option :value="item" v-for="item in options" :key="item">@{{item}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="wxtip">
                    <span>温馨提示：</span>
                    <span>非期初的资产通过【发票】-【进项】增加</span>
                </div>
                <div class="right-buttons">
                    <button class="layui-btn layui-btn-sm" @click="showAddNew">新增</button>
                {{--暂时取消--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showDaoru">导入</button>--}}
                {{--<button class="layui-btn layui-btn-sm fzczc" @click="abnormalZc">显示非正常资产</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCreatePz">生成凭证</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCancelOff">取消折扣</button>--}}
                <!--下拉-->
                    <div class="moreOperations" ref="more0">
                        <div name="more" class="more" @click="moreFlag0=!moreFlag0">更多</div>
                        <ul v-show="moreFlag0">
                            <li value="0" @click="deleteItems()">删除</li>
                            {{--<li value="1" @click="showStopZj()">暂停折旧</li>--}}
                            {{--<li value="2">打印</li>--}}
                            {{--<li value="3">导出excel</li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <!--表格-->
            <div class="box">
                <table class="dataTable">
                    <colgroup>
                        <col style="width: 26px;">
                        <col style="width: 60px;">
                        <col>
                        <col style="width: 170px;">
                        <col style="width: 200px;">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col style="width: 154px;">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="width4" rowspan="2">
                            <input type="checkbox" class="select-checkbox-all"
                                   :checked="selectedItems.length === formData.length" @click="selectAll()">
                        </th>
                        <th rowspan="2">序号</th>
                        <th rowspan="2">凭证号</th>
                        <th rowspan="2">资产名称</th>
                        <th rowspan="2">资产类别</th>
                        <th rowspan="2">数量</th>
                        <th rowspan="2">入账日期</th>
                        <th rowspan="2">原值（元）</th>
                        <th rowspan="2">预计使用月份</th>
                        <th rowspan="2">残值率（%）</th>
                        <th rowspan="2">残值（元）</th>
                        <th rowspan="2">本月折旧（元）</th>
                        <th colspan="2">期末</th>
                        <th rowspan="2">状态</th>
                        <th rowspan="2">操作</th>
                    </tr>
                    <tr>
                        <th>累计折旧（元）</th>
                        <th>净值（元）</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in formData" :key="item.id" class="dataRow">
                        <td class="width4">
                            <input type="checkbox" class="select-checkbox" @change="selectItem(item.id, $event)">
                        </td>
                        <td>@{{index + 1}}</td>
                        <td>
                            <a style="cursor: pointer; color: #f97d3c; font-weight: bold;"
                               v-show="item.voucher_id" @click="addInvoice(item)">记-@{{ item.voucher_id == 0 ? '**' :
                                item.voucher.voucher_num }}
                            </a>
                        </td>
                        <td>@{{item.zcmc}}</td>
                        <td>@{{item.zclb}}</td>
                        <td>@{{item.num}}</td>
                        <td>@{{item.rzrq}}</td>
                        <td>@{{item.yz}}</td>
                        <td>@{{item.zjqx}}</td>
                        <td>@{{item.czl}}</td>
                        <td>@{{item.cz}}</td>
                        <td>@{{item.zjje}}</td>
                        <td>@{{item.ljzj}}</td>
                        <td>@{{(item.yz - item.ljzj).toFixed(2)}}</td>
                        <td>@{{item.status ? "折旧完毕" : "未折旧"}}</td>
                        <td>
                            <span class="icon iconfont tableEditor" @click="show_add_voucher(item)">&#xe602;</span>
                            <span class="iconfont icon-bianji" @click="showEdit(index)"></span>
                            {{--<span class="iconfont icon-weibiaoti-" @click="showClean"></span>--}}
                            <span class="iconfont icon-shanchu" @click="deleteItems(item.id, item.voucher_id)"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <table class="total">
                <!-- 合计-->
                <!-- 占位表头 -->
                <colgroup>
                    <col style="width: 26px;">
                    <col style="width: 60px;">
                    <col>
                    <col style="width: 170px;">
                    <col style="width: 200px;">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col style="width: 154px;">
                </colgroup>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>合计</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>@{{yzTotal.toFixed(2)}}</td>
                    <td></td>
                    <td></td>
                    <td>@{{czTotal.toFixed(2)}}</td>
                    <td>@{{zjjeTotal.toFixed(2)}}</td>
                    <td>@{{ljzjTotal.toFixed(2)}}</td>
                    <td>@{{jzTotal.toFixed(2)}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <!-- 无形资产 -->
        <div class="tabItem" v-show="tabContent[1] == num">
            <!-- 凭证号 -->
        {{--<div class="pZnumber">凭证号：--}}
        {{--<a>data</a>--}}
        {{--</div>--}}
        <!--头部的类别和按钮-->
            <div class="listTop">
                <div class="zclb">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <span>资产类别:</span>
                            <select name="zclb" lay-filter="option1">
                                <option :value="item" v-for="item in options" :key="item">@{{ item }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="wxtip">
                    <span>温馨提示：</span>
                    <span>非期初的资产通过【发票】-【进项】增加</span>
                </div>
                <div class="right-buttons">
                    <button class="layui-btn layui-btn-sm" @click="showAddNew">新增</button>
                {{--<button class="layui-btn layui-btn-sm" @click="showDaoru">导入</button>--}}
                {{--<button class="layui-btn layui-btn-sm fzczc" @click="abnormalZc">显示非正常资产</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCreatePz">生成凭证</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCancelOff">取消折扣</button>--}}
                <!--下拉-->
                    <div class="moreOperations" ref="more1">
                        <div name="more" class="more" @click="moreFlag1=!moreFlag1">更多</div>
                        <ul v-show="moreFlag1">
                            <li value="0" @click="deleteItems()">删除</li>
                            {{--<li value="1">暂停折旧</li>--}}
                            {{--<li value="2">打印</li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <!--表格-->
            <div class="box">
                <table class="dataTable">
                    <colgroup>
                        <col style="width: 27px;">
                        <col style="width: 70px;">
                        <col>
                        <col style="width: 190px;">
                        <col style="width: 254px;">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="width4" rowspan="2">
                            <input type="checkbox" class="select-checkbox-all"
                                   :checked="selectedItems.length === formData.length" @click="selectAll()">
                        </th>
                        <th rowspan="2">序号</th>
                        <th rowspan="2">凭证号</th>
                        <th rowspan="2">资产名称</th>
                        <th rowspan="2">资产类别</th>
                        <th rowspan="2">入账日期</th>
                        <th rowspan="2">原值（元）</th>
                        <th rowspan="2">预计使用月份</th>
                        <th rowspan="2">本月摊销（元）</th>
                        <th colspan="2">期末</th>
                        <th rowspan="2">状态</th>
                        <th rowspan="2" class="width10">操作</th>
                    </tr>
                    <tr>
                        <th>累计折旧（元）</th>
                        <th>净值（元）</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in formData" :key="item.id">
                        <td class="width4">
                            <input type="checkbox" class="select-checkbox" @change="selectItem(item.id, $event)">
                        </td>
                        <td>@{{ index + 1 }}</td>
                        <td>
                            <a style="cursor: pointer; color: #f97d3c; font-weight: bold;"
                               v-show="item.voucher_id" @click="addInvoice(item)">记-@{{ item.voucher_id == 0 ? '**' :
                                item.voucher.voucher_num }}
                            </a>
                        </td>
                        <td>@{{ item.zcmc }}</td>
                        <td>@{{ item.zclb }}</td>
                        <td>@{{ item.rzrq }}</td>
                        <td>@{{ item.yz }}</td>
                        <td>@{{ item.zjqx }}</td>
                        <td>@{{ item.zjje }}</td>
                        <td>@{{ item.ljzj }}</td>
                        <td>@{{ (item.yz - item.ljzj).toFixed(2) }}</td>
                        <td>@{{ item.status ? "折旧完毕" : "未折旧" }}</td>
                        <td>
                            <span class="icon iconfont tableEditor" @click="show_add_voucher(item)">&#xe602;</span>
                            <span class="iconfont icon-bianji" @click="showEdit(index)"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <table class="total">
                <!-- 合计-->
                <colgroup>
                    <col style="width: 27px;">
                    <col style="width: 70px;">
                    <col>
                    <col style="width: 190px;">
                    <col style="width: 254px;">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>合计</td>
                    <td></td>
                    <td></td>
                    <td>@{{yzTotal.toFixed(2)}}</td>
                    <td></td>
                    <td>@{{zjjeTotal.toFixed(2)}}</td>
                    <td>@{{ljzjTotal.toFixed(2)}}</td>
                    <td>@{{jzTotal.toFixed(2)}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <!-- 待摊费用 -->
        <div class="tabItem" v-show="tabContent[2] == num">
            <!-- 凭证号 -->
        {{--<div class="pZnumber">凭证号：--}}
        {{--<a>data</a>--}}
        {{--</div>--}}
        <!--头部的类别和按钮-->
            <div class="listTop">
                <div class="zclb">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <span>资产类别:</span>
                            <select name="zclb" lay-filter="option1">
                                <option :value="item" v-for="item in options" :key="item">@{{item}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="wxtip">
                    <span>温馨提示：</span>
                    <span>非期初的资产通过【发票】-【进项】增加</span>
                </div>
                <div class="right-buttons">
                    <button class="layui-btn layui-btn-sm" @click="showAddNew">新增</button>
                {{--<button class="layui-btn layui-btn-sm" @click="showDaoru">导入</button>--}}
                {{--<button class="layui-btn layui-btn-sm fzczc" @click="abnormalZc">显示非正常资产</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCreatePz">生成凭证</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCancelOff">取消折扣</button>--}}
                <!--下拉-->
                    <div class="moreOperations" ref="more2">
                        <div name="more" class="more" @click="moreFlag2=!moreFlag2">更多</div>
                        <ul v-show="moreFlag2">
                            <li value="0" @click="deleteItems()">删除</li>
                            {{--<li value="1">打印</li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <!--表格-->
            <div class="box">
                <table class="dataTable">
                    <colgroup>
                        <col style="width: 27px;">
                        <col style="width: 72px;">
                        <col>
                        <col style="width: 198px;">
                        <col style="width: 134px;">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="width4" rowspan="2">
                            <input type="checkbox" class="select-checkbox-all"
                                   :checked="selectedItems.length === formData.length" @click="selectAll()">
                        </th>
                        <th>序号</th>
                        <th>凭证号</th>
                        <th>费用名称</th>
                        <th>摊销方法</th>
                        <th>入账日期</th>
                        <th>摊销开始日期</th>
                        <th>摊销期限（月）</th>
                        <th>原值（元）</th>
                        <th>本月摊销（元）</th>
                        <th>累计摊销（元）</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in formData" :key="item.id">
                        <td class="width4">
                            <input type="checkbox" class="select-checkbox" @change="selectItem(item.id, $event)">
                        </td>
                        <td>@{{ index + 1 }}</td>
                        <td>
                            <a style="cursor: pointer; color: #f97d3c; font-weight: bold;"
                               v-show="item.voucher_id" @click="addInvoice(item)">记-@{{ item.voucher_id == 0 ? '**' :
                                item.voucher.voucher_num }}
                            </a>
                        </td>
                        <td>@{{ item.zcmc }}</td>
                        <td>@{{ item.zjff }}</td>
                        <td>@{{ item.rzrq }}</td>
                        <td>@{{ item.txks }}</td>
                        <td>@{{ item.zjqx }}</td>
                        <td>@{{ item.yz}}</td>
                        <td>@{{ item.zjje }}</td>
                        <td>@{{ item.ljzj }}</td>
                        <td>@{{ item.status ? "折旧完毕" : "未折旧" }}</td>
                        <td>
                            <span class="icon iconfont tableEditor" @click="show_add_voucher(item)">&#xe602;</span>
                            <span class="iconfont icon-bianji" @click="showEdit(index)"></span>
                        </td>
                    </tr>
                    <!-- 合计-->
                    </tbody>
                </table>
            </div>
            <table class="total">
                <colgroup>
                    <col style="width: 27px;">
                    <col style="width: 72px;">
                    <col>
                    <col style="width: 198px;">
                    <col style="width: 134px;">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>合计</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>@{{yzTotal.toFixed(2)}}</td>
                    <td>@{{zjjeTotal.toFixed(2)}}</td>
                    <td>@{{ljzjTotal.toFixed(2)}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <!-- 长摊费用 -->
        <div class="tabItem" v-show="tabContent[3] == num">
            <!-- 凭证号 -->
        {{--<div class="pZnumber">凭证号：--}}
        {{--<a>data</a>--}}
        {{--</div>--}}
        <!--头部的类别和按钮-->
            <div class="listTop">
                <div class="zclb">
                    <div class="layui-form">
                        <div class="layui-form-item">
                            <span>资产类别:</span>
                            <select name="zclb" lay-filter="option1">
                                <option :value="item" v-for="item in options" :key="item">@{{item}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="wxtip">
                    <span>温馨提示：</span>
                    <span>非期初的资产通过【发票】-【进项】增加</span>
                </div>
                <div class="right-buttons">
                    <button class="layui-btn layui-btn-sm" @click="showAddNew">新增</button>
                {{--<button class="layui-btn layui-btn-sm" @click="showDaoru">导入</button>--}}
                {{--<button class="layui-btn layui-btn-sm fzczc" @click="abnormalZc">显示非正常资产</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCreatePz">生成凭证</button>--}}
                {{--<button class="layui-btn layui-btn-sm" @click="showCancelOff">取消折扣</button>--}}
                <!--下拉-->
                    <div class="moreOperations" ref="more3">
                        <div name="more" class="more" @click="moreFlag3=!moreFlag3">更多</div>
                        <ul v-show="moreFlag3">
                            <li value="0" @click="deleteItems()">删除</li>
                            {{--<li value="1">打印</li>--}}
                        </ul>
                    </div>

                </div>
            </div>
            <!--表格-->
            <div class="box">
                <table class="dataTable">
                    <colgroup>
                        <col style="width: 27px;">
                        <col style="width: 70px;">
                        <col>
                        <col style="width: 190px;">
                        <col style="width: 250px;">
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="width4" rowspan="2">
                            <input type="checkbox" class="select-checkbox-all"
                                   :checked="selectedItems.length === formData.length" @click="selectAll()">
                        </th>
                        <th>序号</th>
                        <th>凭证号</th>
                        <th>费用名称</th>
                        <th>类别</th>
                        <th>摊销方法</th>
                        <th>入账日期</th>
                        <th>摊销期限（月）</th>
                        <th>原值（元）</th>
                        <th>本月摊销（元）</th>
                        <th>累计摊销（元）</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in formData" :key="item.id">
                        <td class="width4">
                            <input type="checkbox" class="select-checkbox" @change="selectItem(item.id, $event)">
                        </td>
                        <td>@{{ index + 1 }}</td>
                        <td>
                            <a style="cursor: pointer; color: #f97d3c; font-weight: bold;"
                               v-show="item.voucher_id" @click="addInvoice(item)">记-@{{ item.voucher_id == 0 ? '**' :
                                item.voucher.voucher_num }}
                            </a>
                        </td>
                        <td>@{{item.zcmc}}</td>
                        <td>@{{item.zclb}}</td>
                        <td>@{{item.zjff}}</td>
                        <td>@{{item.rzrq}}</td>
                        <td>@{{item.zjqx}}</td>
                        <td>@{{item.yz}}</td>
                        <td>@{{ item.zjje }}</td>
                        <td>@{{ item.ljzj }}</td>
                        <td>@{{ item.status ? "折旧完毕" : "未折旧" }}</td>
                        <td>
                            <span class="icon iconfont tableEditor" @click="show_add_voucher(item)">&#xe602;</span>
                            <span class="iconfont icon-bianji" @click="showEdit(index)"></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- 合计-->
            <table class="total">
                <colgroup>
                    <col style="width: 27px;">
                    <col style="width: 70px;">
                    <col>
                    <col style="width: 190px;">
                    <col style="width: 250px;">
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>合计</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>@{{yzTotal.toFixed(2)}}</td>
                    <td>@{{zjjeTotal.toFixed(2)}}</td>
                    <td>@{{ljzjTotal.toFixed(2)}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="componentsBox">
        <!-- 组件 -->
        <!-- 导入组件 -->
        <div class="daoruzj components" style="display: none">
            <form action="#" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" value="">
            </form>
            <div class="daorumuban">
                <span>固定资产初始化导入模板:</span>
                <a href="#">点此下载</a>
            </div>
        </div>
        <!-- 取消折扣组件 -->
        <div class="cancelOff components" style="display: none">
            <span>
                您确认要取消折旧吗？
            </span>
        </div>
        <!-- 新增组件 -->
        <!--固定资产-->
        <div class="addNew addNew1 components" style="display: none">
            <form action="post" class="addLayerData1">
                {!! csrf_field() !!}
                <input type="hidden" name="fiscal_period" value="{{ \App\Entity\Period::currentPeriod() }}">
                <input type="hidden" name="zclx" value="固定资产">
                <div class="first">
                    <table class="formdata">
                        <tr class="top">
                            <td>
                                <label for="zcName">资产名称:</label>
                                <input type="text" name="zcmc" class="formItem1 zcName" required>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="zcType">资产类别:</label>
                                        <select name="zclb" lay-filter="option2" class="zcType1 formItem1">
                                            <option v-for="item in options" :key="item">@{{item}}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="zcNum">数量:</label>
                                <input type="text" name="num"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" required>
                            </td>
                            <td>
                                <label for="ruzhangDate">入账日期:</label>
                                <input name="rzrq"
                                       type="text"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">折旧方法:</label>
                                        <select name="zjff" lay-filter="option2" class="formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for="fdDuration">折旧期限（月）:</label>
                                <input name="zjqx" type="text" id="fdDuration"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" required>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="yzkm_id" class="yzkm_id">
                                <input type="hidden" name="yzkm" class="yzkm_name">
                                <div class="rzkm fl" @click="flag1 = !flag1">
                                    <input type="text" v-model="dataSearch" v-focus v-if="flag1" class="fl">
                                    <input type="text" readonly class="fl" v-show="!flag1" :value="data1">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag1" @mouseup="selectRzkmItem1($event)">
                                        <li v-for="item in list" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="ljzjkm" class="fl">累计折旧科目:</label>
                                <input type="hidden" name="ljzjkm_id" class="ljzjkm_id">
                                <input type="hidden" name="ljzjkm" class="ljzjkm_name">
                                <div class="rzkm fl" @click="flag2 = !flag2">
                                    <input type="text" v-model="dataSearch" v-focus v-if="flag2" class="fl">
                                    <input type="text" readonly class="fl" v-show="!flag2" :value="data2">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag2" @mouseup="selectRzkmItem2($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id">
                                <input type="hidden" name="cbfykm" class="cbfykm_name">
                                <div class="rzkm fl" @click="foldOther(), flag3 = !flag3">
                                    <input type="text" readonly class="fl" :value="data3">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag3" @mouseup="selectRzkmItem3($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz" class="formItem1 yzValue"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" v-model="editData.yz" required>元
                            </td>
                            <td>
                                <label for="canzhilv">残值率:</label>
                                <input name="czl" type="text" class="formItem1 czlValue"
                                       @blur="czl = testNum(czl) ? czl : ''"
                                       placeholder="请输入数字（不可大于100）" v-model="czl">%
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="canzhi">残值:</label>
                                <input name="cz" type="text" class="formItem1" readonly
                                       v-model="editData.yz == NaN ? '0' : ((czl / 100) * editData.yz).toFixed(2)">元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月折旧:</label>
                                <input name="zjje" type="text"
                                       onblur="value=(/^([1-9]*[0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" class="beizhutextarea" cols="63" rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!--无形资产-->
        <div class="addNew addNew2 components" style="display: none">
            <form action="post" class="addLayerData2">
                {!! csrf_field() !!}
                <input type="hidden" name="fiscal_period" value="{{ \App\Entity\Period::currentPeriod() }}">
                <input type="hidden" name="zclx" value="无形资产">
                <div class="first">
                    <table class="formdata">
                        <tr class="top">
                            <td>
                                <label for="zcName">资产名称:</label>
                                <input type="text" name="zcmc" class="formItem1 zcName" required>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="zcType">资产类别:</label>
                                        <select name="zclb" lay-filter="option2" class="zcType1 formItem1">
                                            <option v-for="item in options" :key="item">@{{item}}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="ruzhangDate">增加日期:</label>
                                <input name="rzrq"
                                       type="text"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">摊销方法:</label>
                                        <select name="zjff" lay-filter="option2" class="formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="fdDuration">预计摊销月份:</label>
                                <input name="zjqx" type="text" id="fdDuration"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" required>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="yzkm_id" class="yzkm_id">
                                <input type="hidden" name="yzkm" class="yzkm_name">
                                <div class="rzkm fl" @click="flag1 = !flag1">
                                    <input type="text" readonly class="fl" :value="data1">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag1" @mouseup="selectRzkmItem1($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="ljzjkm" class="fl">累计折旧科目:</label>
                                <input type="hidden" name="ljzjkm_id" class="ljzjkm_id">
                                <input type="hidden" name="ljzjkm" class="ljzjkm_name">
                                <div class="rzkm fl" @click="flag2 = !flag2">
                                    <input type="text" readonly class="fl" :value="data2">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag2" @mouseup="selectRzkmItem2($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id">
                                <input type="hidden" name="cbfykm" class="cbfykm_name">
                                <div class="rzkm fl" @click="flag3 = !flag3">
                                    <input type="text" readonly class="fl" :value="data3">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag3" @click="selectRzkmItem3($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz" class="formItem1 yzValue"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" v-model="editData.yz" required>元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月摊销:</label>
                                <input name="zjje" type="text" class="formItem1"
                                       onblur="value=(/^([1-9]*[0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" class="beizhutextarea" cols="63" rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!--待摊费用-->
        <div class="addNew addNew3 components" style="display: none">
            <form action="post" class="addLayerData3">
                {!! csrf_field() !!}
                <input type="hidden" name="fiscal_period" value="{{ \App\Entity\Period::currentPeriod() }}">
                <input type="hidden" name="zclx" value="待摊费用">
                <div class="first">
                    <table class="formdata">
                        <tr class="top">
                            <td>
                                <label for="zcName">费用名称:</label>
                                <input type="text" name="zcmc" class="formItem1 zcName" required>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="ruzhangDate">入账时间:</label>
                                <input name="rzrq"
                                       type="text"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                            <td>
                                <label for="ruzhangDate">开始时间:</label>
                                <input name="txks"
                                       type="text"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">摊销方法:</label>
                                        <select name="zjff" lay-filter="option2" class="formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for="fdDuration">摊销期限（月）:</label>
                                <input name="zjqx" type="text"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" id="fdDuration" class="formItem1" required>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="yzkm_id" class="yzkm_id">
                                <input type="hidden" name="yzkm" class="yzkm_name">
                                <div class="rzkm fl" @click="flag1 = !flag1">
                                    <input type="text" readonly class="fl" :value="data1">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag1" @click="selectRzkmItem1($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id">
                                <input type="hidden" name="cbfykm" class="cbfykm_name">
                                <div class="rzkm fl" @click="flag3 = !flag3">
                                    <input type="text" readonly class="fl" :value="data3">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag3" @click="selectRzkmItem3($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1 yzValue" v-model="editData.yz" required>元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月摊销:</label>
                                <input name="zjje" type="text"
                                       onblur="value=(/^([1-9]*[0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" class="beizhutextarea" cols="63" rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!--长摊费用-->
        <div class="addNew addNew4 components" style="display: none">
            <form action="post" class="addLayerData4">
                {!! csrf_field() !!}
                <input type="hidden" name="fiscal_period" value="{{ \App\Entity\Period::currentPeriod() }}">
                <input type="hidden" name="zclx" value="长摊费用">
                <div class="first">
                    <table class="formdata">
                        <tr class="top">
                            <td>
                                <label for="zcName">费用名称:</label>
                                <input type="text" name="zcmc" class="formItem1 zcName" required>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="zcType">类别:</label>
                                        <select name="zclb" lay-filter="option2" class="zcType1 formItem1">
                                            <option v-for="item in options" :key="item">@{{item}}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="ruzhangDate">入账时间:</label>
                                <input name="rzrq"
                                       type="text"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="top">
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">摊销方法:</label>
                                        <select name="zjff" lay-filter="option2" class="formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for="fdDuration">摊销期限（月）:</label>
                                <input name="zjqx" type="text"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" id="fdDuration" class="formItem1" required>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="yzkm_id" class="yzkm_id">
                                <input type="hidden" name="yzkm" class="yzkm_name">

                                <div class="rzkm fl" @click="flag1 = !flag1">
                                    <input type="text" readonly class="fl" :value="data1">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag1" @click="selectRzkmItem1($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id">
                                <input type="hidden" name="cbfykm" class="cbfykm_name">

                                <div class="rzkm fl" @click="flag3 = !flag3">
                                    <input type="text" readonly class="fl" :value="data3">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flag3" @click="selectRzkmItem3($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" class="tl"
                                            :data-number="item.number" :data-name="item.name">
                                            @{{item.number+item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1 yzValue" v-model="editData.yz" required>元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月摊销:</label>
                                <input name="zjje" type="text"
                                       onblur="value=(/^([1-9]*[0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" class="beizhutextarea" cols="63" rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!-- 编辑组件 -->
        <!--固定资产-->
        <div class="edit edit1 components" style="display: none">
            <form action="post" class="editLayerData1">
                {!! csrf_field() !!}
                <div class="first">
                    <table class="formdata">
                        <input type="hidden" name="zclx" value="固定资产">
                        <tr class="top">
                            <td>
                                <input type="hidden" name="id" class="idEdit idEdit1">
                                <label for="zcName">资产名称:</label>
                                <input type="text" name="zcmc" :value="editData.zcmc" class="formItem1 zcName" required>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="zcType">资产类别:</label>
                                        <select name="zclb" lay-filter="option2" class="zcType zcType1 formItem1">
                                            <option v-for="item in options" :key="item">@{{item}}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="zcNum">数量:</label>
                                <input type="text" id="zcNum"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" name="num" :value="editData.num" class="formItem1" required>
                            </td>
                            <td>
                                <label for="ruzhangDate">入账日期:</label>
                                <input id="ruzhangDate"
                                       name="rzrq"
                                       type="text"
                                       :value="editData.rzrq"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">折旧方法:</label>
                                        <select name="zjff" lay-filter="option2" class="fdMethod fdMethod1 formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for="fdDuration">折旧期限（月）:</label>
                                <input name="zjqx" type="text"
                                       onblur="value=(/^([1-9][0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" id="fdDuration" class="formItem1" :value="editData.zjqx"
                                       required>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="yzkm_id" class="yzkm_id1">
                                <input type="hidden" name="yzkm" class="yzkm_name1">
                                <div class="rzkm fl" @click="flagA = !flagA">
                                    <input type="text" readonly class="fl" :value="dataA">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagA" @click="selectRzkmItemA($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="ljzjkm" class="fl">累计折旧科目:</label>
                                <input type="hidden" name="ljzjkm_id" class="ljzjkm_id1">
                                <input type="hidden" name="ljzjkm" class="ljzjkm_name1">
                                <div class="rzkm fl" @click="flagB = !flagB">
                                    <input type="text" readonly class="fl" :value="dataB">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagB" @click="selectRzkmItemB($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id1">
                                <input type="hidden" name="cbfykm" class="cbfykm_name1">
                                <div class="rzkm fl" @click="flagC = !flagC">
                                    <input type="text" readonly class="fl" :value="dataC">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagC" @click="selectRzkmItemC($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz" v-model="editData.yz" class="formItem1" disabled required>元
                            </td>
                            <td>
                                <label for="canzhilv">残值率:</label>
                                <input name="czl" type="text"
                                       @blur="editData.czl = testNum(editData.czl) ? editData.czl : ''"
                                       placeholder="请输入数字（不可大于100）" :value="editData.czl"
                                       class="formItem1"
                                       v-model="editData.czl">%
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="canzhi">残值:</label>
                                <input name="cz" type="text" :value="editData.cz" id="canzhi" class="formItem1" disabled
                                       v-model="((editData.czl / 100) * editData.yz).toFixed(2)">元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月折旧:</label>
                                <input name="zjje" type="text"
                                       onblur="value=(/^([1-9]*[0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" :value="editData.zjje" class="formItem1"
                                       id="benyuezhejiu" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" :value="editData.remark" class="beizhutextarea" cols="63"
                                          rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!--无形资产-->
        <div class="edit edit2 components" style="display: none">
            <form action="post" class="editLayerData2">
                {!! csrf_field() !!}
                <div class="first">
                    <table class="formdata">
                        <input type="hidden" name="zclx" value="无形资产">
                        <tr class="top">
                            <td>
                                <input type="hidden" name="id" class="idEdit idEdit2">
                                <label for="zcName">资产名称:</label>
                                <input type="text" name="zcmc" :value="editData.zcmc" class="formItem1 zcName" required>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="zcType">资产类别:</label>
                                        <select name="zclb" lay-filter="option2" class="zcType zcType2 formItem1">
                                            <option v-for="item in options" :key="item">@{{item}}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="ruzhangDate">增加日期:</label>
                                <input name="rzrq"
                                       type="text"
                                       :value="editData.rzrq"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">摊销方法:</label>
                                        <select name="zjff" lay-filter="option2" class="fdMethod fdMethod2 formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="fdDuration">预计摊销月份:</label>
                                <input name="zjqx" type="text" id="fdDuration" :value="editData.zjqx" class="formItem1"
                                       required>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                {{--<input type="hidden" name="id" value="1">--}}
                                <input type="hidden" name="yzkm_id" class="yzkm_id2">
                                <input type="hidden" name="yzkm" class="yzkm_name2">
                                <div class="rzkm fl" @click="flagA = !flagA">
                                    <input type="text" readonly class="fl" :value="dataA">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagA" @click="selectRzkmItemA($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="ljzjkm" class="fl">累计折旧科目:</label>
                                <input type="hidden" name="ljzjkm_id" class="ljzjkm_id2">
                                <input type="hidden" name="ljzjkm" class="ljzjkm_name2">
                                <div class="rzkm fl" @click="flagB = !flagB">
                                    <input type="text" readonly class="fl" :value="dataB">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagB" @click="selectRzkmItemB($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id2">
                                <input type="hidden" name="cbfykm" class="cbfykm_name2">
                                <div class="rzkm fl" @click="flagC = !flagC">
                                    <input type="text" readonly class="fl" :value="dataC">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagC" @click="selectRzkmItemC($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz" class="formItem1" v-model="editData.yz" disabled required>元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月摊销:</label>
                                <input name="zjje" type="text"
                                       onblur="value=(/^([1-9]*[0-9]*)+(.[0-9]{1,2})?$/).test(value + '') ? value : ''"
                                       placeholder="请输入数字" class="formItem1" :value="editData.zjje" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea :value="editData.remark" name="remark" class="beizhutextarea" cols="63"
                                          rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!--待摊费用-->
        <div class="edit edit3 components" style="display: none">
            <form action="post" class="editLayerData3">
                {!! csrf_field() !!}
                <div class="first">
                    <table class="formdata">
                        <tr class="top">
                            <input type="hidden" name="zclx" value="待摊费用">
                            <td>
                                <input type="hidden" name="id" class="idEdit idEdit3">
                                <label for="zcName">费用名称:</label>
                                <input type="text" name="zcmc" :value="editData.zcmc" class="formItem1 zcName" required>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="ruzhangDate">入账时间:</label>
                                <input name="rzrq"
                                       type="text"
                                       :value="editData.rzrq"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                            <td>
                                <label for="ruzhangDate">开始时间:</label>
                                <input name="txks"
                                       type="text"
                                       :value="editData.txks"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">摊销方法:</label>
                                        <select name="zjff" lay-filter="option2" class="fdMethod fdMethod3 formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for="fdDuration">摊销期限（月）:</label>
                                <input name="zjqx" type="text" id="fdDuration" class="formItem1" :value="editData.zjqx"
                                       required>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="id" class="idEdit idEdit3">
                                <input type="hidden" name="yzkm_id" class="yzkm_id3">
                                <input type="hidden" name="yzkm" class="yzkm_name3">
                                <div class="rzkm fl" @click="flagA = !flagA">
                                    <input type="text" readonly class="fl" :value="dataA">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagA" @click="selectRzkmItemA($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id3">
                                <input type="hidden" name="cbfykm" class="cbfykm_name3">
                                <div class="rzkm fl" @click="flagC = !flagC">
                                    <input type="text" readonly class="fl" :value="dataC">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagC" @click="selectRzkmItemC($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz" class="formItem1" v-model="editData.yz" disabled required>元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月摊销:</label>
                                <input name="zjje" type="text" :value="editData.zjje" class="formItem1" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" class="beizhutextarea" :value="editData.remark" cols="63"
                                          rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!--长摊费用-->
        <div class="edit edit4 components" style="display: none">
            <form action="post" class="editLayerData4">
                {!! csrf_field() !!}
                <div class="first">
                    <table class="formdata">
                        <input type="hidden" name="zclx" value="长摊费用">
                        <tr class="top">
                            <td>
                                <input type="hidden" name="id" class="idEdit idEdit4">
                                <label for="zcName">费用名称:</label>
                                <input type="text" name="zcmc" :value="editData.zcmc" class="formItem1 zcName" required>
                            </td>
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="zcType">类别:</label>
                                        <select name="zclb" lay-filter="option2" class="zcType zcType4 formItem1">
                                            <option v-for="item in options" :key="item">@{{item}}</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="top">
                            <td>
                                <label for="ruzhangDate">入账时间:</label>
                                <input name="rzrq"
                                       type="text"
                                       :value="editData.rzrq"
                                       onclick="WdatePicker({dateFmt:'yyyy-MM-dd',readOnly:true})" class="formItem1"
                                       required/>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="top">
                            <td>
                                <div class="layui-form">
                                    <div class="layui-form-item">
                                        <label for="fdMethod">摊销方法:</label>
                                        <select name="zjff" lay-filter="option2" class="fdMethod fdMethod4 formItem1">
                                            <option>平均年限法</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <label for="fdDuration">摊销期限（月）:</label>
                                <input name="zjqx" type="text" id="fdDuration" :value="editData.zjqx" class="formItem1"
                                       required>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="second">
                    <div class="title">
                        <h3>入帐科目</h3>
                    </div>
                    <table class="formdata">
                        <tr class="middle">
                            <td>
                                <label for="yzkm" class="fl">原值科目:</label>
                                <input type="hidden" name="yzkm_id" class="yzkm_id4">
                                <input type="hidden" name="yzkm" class="yzkm_name4">
                                <div class="rzkm fl" @click="flagA = !flagA">
                                    <input type="text" readonly class="fl" :value="dataA">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagA" @click="selectRzkmItemA($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr class="middle">
                            <td>
                                <label for="cbfykm" class="fl">成本费用科目:</label>
                                <input type="hidden" name="cbfykm_id" class="cbfykm_id4">
                                <input type="hidden" name="cbfykm" class="cbfykm_name4">
                                <div class="rzkm fl" @click="flagC = !flagC">
                                    <input type="text" readonly class="fl" :value="dataC">
                                    <span class="fl">
                                        <i class="iconfont icon-xialazhishijiantou"></i>
                                    </span>
                                    <ul class="items" v-show="flagC" @click="selectRzkmItemC($event)">
                                        <li v-for="item in yzkmOptions" :key="item.id" :data-number="item.number"
                                            :data-name="item.name" class="tl">@{{item.number}}--@{{item.name}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="third">
                    <div class="title">
                        <h3>原值、净值、累计折旧</h3>
                    </div>
                    <table class="formdata">
                        <tr>
                            <td>
                                <label for="yuanzhi">原值:</label>
                                <input type="text" name="yz" class="formItem1" v-model="editData.yz" disabled required>元
                            </td>
                            <td>
                                <label for="benyuezhejiu">本月摊销:</label>
                                <input name="zjje" type="text" :value="editData.zjje" class="formItem1" required>元
                            </td>
                        </tr>
                        <tr class="last">
                            <td colspan="2">
                                <label for="beizhu" class="beizhu">备注:</label>
                                <textarea name="remark" :value="editData.remark" class="beizhutextarea" cols="63"
                                          rows="5"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
        <!-- 清理组件 -->
        <div class="clean components" style="display: none">
            <div class="cleanContent">
                <div class="box">
                    <p>温馨提示：出售的资产请在销项发票页面录入发票清单！</p>
                    <div class="rows">
                        <div class="layui-form">
                            <div class="layui-form-item">
                                <label class="fl">清理原因: </label>
                                <select name="qlyy_id" lay-filter="option2" class="qlyy">
                                    <option v-for="(item, index) in resonOptions" :key="index">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="rows">
                        <label class="fl">清理收入: </label>
                        <input type="text">元
                    </div>
                    <div class="rows">
                        <div class="layui-form">
                            <div class="layui-form-item">
                                <label class="fl">收款方式: </label>
                                <select name="skfs_id" lay-filter="option2" class="skfs">
                                    <option v-for="(item, index) in typeOptions" :key="index">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--清理确定组件-->
        <div class="cleanConfirm components" style="display: none;">
            <div class="content">
                <p class="tc">资产一旦清理不可撤销，请确定是否进行清理？</p>
            </div>
        </div>
        <!--暂停折旧组件-->
        <div class="zantingzhejiu components" style="display: none">
            <div class="content">
                <div class="rows">
                    <p>请选择暂停折旧开始时间</p>
                    <input name="start_time" type="text" class="Wdate" onfocus="WdatePicker()">
                </div>
                <div class="rows">
                    <p>请选择暂停折旧结束时间</p>
                    <input name="end_time" type="text" class="Wdate" onfocus="WdatePicker()">
                </div>
            </div>
        </div>
    </div>
</div>
<!--公用-->
{{--<script src="/common/js/vue.min.js"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
{{--<script src="/common/js/jquery-2.2.4.js"></script>--}}
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="/common/layui/layui.js" charset="utf-8"></script>
<script src="/common/vue-resource/dist/vue-resource.js"></script>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<script>
    var vm = new Vue({
        'el': '.zjtx',
        data: {
            i: 0,
            num: 'gdzc',
            moreFlag0: false,
            moreFlag1: false,
            moreFlag2: false,
            moreFlag3: false,
            // 下拉框相关(新增)
            flag1: false,
            flag2: false,
            flag3: false,
            data1: '',
            data2: '',
            data3: '',
            // 下拉框相关（编辑）
            flagA: false,
            flagB: false,
            flagC: false,
            dataA: '',
            dataB: '',
            dataC: '',
            // 求和部分
            yzTotal: 0,
            czTotal: 0,
            zjjeTotal: 0,
            ljzjTotal: 0,
            jzTotal: 0,
            // 标签页相关
            tabs: [],
            tabContent: ['gdzc', 'wxzc', 'dtfy', 'ctfy'],
            // 列表渲染参数
            renderList: {zclx: '', zclb: ''},
            formData: [],
            // 编辑弹框的数据
            editData: {yz: 0},
            // 下拉框内容
            dataSearch: '',
            options: [],
            yzkmOptions: [],

            // 残值率
            czl: 0,
            // 被选中的项目
            selectedItems: [],
            isCheckedAll: false,

            resonOptions: [
                {
                    value: '0',
                    label: '报废'
                },
                {
                    value: '1',
                    label: '损毁'
                }
            ],
            typeOptions: [
                {
                    value: '0',
                    label: '现金'
                },
                {
                    value: '1',
                    label: '银行'
                }
            ]
        },
        directives: {
            focus: {
                // 当绑定元素插入到 DOM 中。
                inserted: function (el) {
                    // 聚焦元素
                    el.focus()
                }
            }
        },
        created: function () {
            var _this = this;
            layui.use(['layer', 'form'], function () {
                var form = layui.form; //只有执行了这一步，部分表单元素才会自动修饰成功
                form.render();
                form.on('select(option1)', function (data) {
                    // console.log(data);
                    _this.renderList.zclb = data.value;
                    _this.renderFormData();
                    // console.log(_this.num);
                });
            });
            _this.render();
            _this.requiredInput();
        },
        mounted: function () {
            this.clickBlank();
            this.testNum();
        },
        methods: {
            // 必填项
            requiredInput: function () {
                $('input[required]').siblings('label').prepend('<span style="color:red">*</span>')
            },

            // 渲染标签
            render: function (item, index) {
                var _this = this;
                _this.$http.get('/book/asset/type').then(function (response) {
                    if (response.body.status == 1) {
                        // console.log(response);
                        _this.tabs = response.body.data;
                        // console.log(_this.tabs);
                    }
                });
                _this.renderList.zclx = "固定资产";
                _this.loadOptions(item, index);
            },

            // 加载下拉框
            loadOptions: function (item, index) {
                var _this = this;
                if (index == null) {
                    index = 'gdzc';
                }
                ;
                // console.log(_this.renderList);
                // 资产类别
                _this.$http.get('/book/asset/getAssetZclb', {params: {zclx: index}}).then(function (response) {
                    if (response.body.status == 1) {
                        _this.options = response.body.data;
                        // console.log(_this.options);
                    }
                }).then(function () {
                    layui.use(['form'], function () {
                        var form = layui.form;
                        form.render();
                    })
                });
                // 科目下拉框
                _this.$http.get('{{ route('account_subject.index') }}').then(function (response) {
                    if (response.ok == true) {
                        _this.yzkmOptions = response.body;
                    }
                    // console.log(_this.yzkmOptions.length);
                }).then(function () {
                    layui.use(['form', 'jquery'], function () {
                        var form = layui.form;
                        form.render();
                        form.on('select(option2)', function (data) {
                            // console.log(data);
                        })
                    })
                });
                // console.log(_this.yzkmOptions)
                _this.renderFormData();
            },

            // 渲染列表
            renderFormData: function () {
                var _this = this;
                // console.log(_this.renderList);
                _this.$http.get('/book/asset/getAssetList', {params: _this.renderList}).then(function (response) {
                    _this.formData = response.body;
                    // console.log(response.body);
                    _this.sum();
                })
            },

            // 切换标签页
            tab: function (item, index) {
                // 标签页依据 num 切换
                this.num = index;
                // 加载下拉框
                this.renderList.zclx = item;
                this.renderList.zclb = '';
                this.loadOptions(item, index);
            },

            // 弹出导入弹框
            showDaoru: function () {
                layer.open({
                    type: 1,
                    title: '资产导入',
                    shadeClose: true,
                    content: $('.daoruzj'),
                    skin: 'components',
                    area: ['408px', '226px'],
                    btn: ['确认', '取消'],
                    resize: true,
                    yes: function (index, layero) {

                    },
                    btn2: function (index, layero) {

                    },
                    cancel: function () {

                    }
                });
            },

            // 弹框取消折扣
            showCancelOff: function () {
                layer.open({
                    type: 1,
                    title: '信息',
                    shadeClose: true,
                    content: $('.cancelOff'),
                    skin: 'components',
                    area: ['263px', '162px'],
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {

                    },
                    btn2: function (index, layero) {

                    },
                    cancel: function () {

                    }
                });
            },

            // 弹框新增
            showAddNew: function () {
                var _this = this;
                // 若在科目上不选，默认
                $('.yzkm_id').val(_this.yzkmOptions[0].number);
                $('.yzkm_name').val(_this.yzkmOptions[0].name);
                $('.ljzjkm_id').val(_this.yzkmOptions[0].number);
                $('.ljzjkm_name').val(_this.yzkmOptions[0].name);
                $('.ljzjkm_id').val(_this.yzkmOptions[0].number);
                $('.ljzjkm_name').val(_this.yzkmOptions[0].name);
                if (_this.renderList.zclx == '固定资产') {
                    layer.open({
                        type: 1,
                        title: '新增',
                        shadeClose: true,
                        content: $('.addNew1'),
                        skin: 'components',
                        area: ['860px', '700px'],
                        btn: ['确认', '取消'],
                        yes: function (index, layero) {
                            // console.log(_this.serializeObject($('.addLayerData1')));
                            _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.addLayerData1'))).then(function (response) {
                                // console.log(response);
                                if (response.body.status == 0) {
                                    layer.msg('添加失败');
                                }
                                _this.renderFormData();
                            });
                            _this.resetForm($('.addLayerData1'));
                            layer.close(index);
                        },
                        btn2: function (index, layero) {
                            _this.resetForm($('.addLayerData1'));
                        },
                        cancel: function (index, layero) {
                            _this.resetForm($('.addLayerData1'));
                        }
                    })
                } else if (_this.renderList.zclx == '无形资产') {
                    layer.open({
                        type: 1,
                        title: '新增',
                        shadeClose: true,
                        content: $('.addNew2'),
                        skin: 'components',
                        area: ['860px', '700px'],
                        btn: ['确认', '取消'],
                        yes: function (index, layero) {
                            // console.log(_this.serializeObject($('.addLayerData2')));
                            _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.addLayerData2'))).then(function (response) {
                                // console.log(response);
                                if (response.body.status == 0) {
                                    layer.msg('添加失败');
                                }
                                _this.renderFormData();
                            });
                            _this.resetForm($('.addLayerData2'));
                            layer.close(index);
                        },
                        btn2: function (index, layero) {
                            _this.resetForm($('.addLayerData2'));
                        },
                        cancel: function () {
                            _this.resetForm($('.addLayerData2'));
                        }
                    })
                } else if (_this.renderList.zclx == '待摊费用') {
                    layer.open({
                        type: 1,
                        title: '新增',
                        shadeClose: true,
                        content: $('.addNew3'),
                        skin: 'components',
                        area: ['860px', '630px'],
                        btn: ['确认', '取消'],
                        yes: function (index, layero) {
                            // console.log(_this.serializeObject($('.addLayerData3')));
                            _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.addLayerData3'))).then(function (response) {
                                // console.log(response);
                                if (response.body.status == 0) {
                                    layer.msg('添加失败');
                                }
                                _this.renderFormData();

                            });
                            _this.resetForm($('.addLayerData3'));
                            layer.close(index);
                        },
                        btn2: function (index, layero) {
                            _this.resetForm($('.addLayerData3'));
                        },
                        cancel: function () {
                            _this.resetForm($('.addLayerData3'));
                        }
                    })
                } else if (_this.renderList.zclx == '长摊费用') {
                    layer.open({
                        type: 1,
                        title: '新增',
                        shadeClose: true,
                        content: $('.addNew4'),
                        skin: 'components',
                        area: ['860px', '630px'],
                        btn: ['确认', '取消'],
                        yes: function (index, layero) {
                            // console.log(_this.serializeObject($('.addLayerData4')));
                            _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.addLayerData4'))).then(function (response) {
                                if (response.body.status == 0) {
                                    layer.msg('添加失败');
                                }
                                _this.renderFormData();
                            });
                            _this.resetForm($('.addLayerData4'));
                            layer.close(index);
                        },
                        btn2: function (index, layero) {
                            _this.resetForm($('.addLayerData4'));
                        },
                        cancel: function () {
                            _this.resetForm($('.addLayerData4'));
                        }
                    })
                }
            },

            // 原值
            selectRzkmItem1: function (e) {
                var _this = this;
                _this.data1 = e.target.innerText;
                $('.yzkm_id').val(e.target.getAttribute('data-number'));
                $('.yzkm_name').val(e.target.getAttribute('data-name'));
                $(e.target).addClass('active').siblings().removeClass('active');
            },
            // 编辑
            selectRzkmItemA: function (e) {
                var _this = this;
                // console.log(e.target.getAttribute('data-number'))
                // 点击后
                // 1. 将 li 里的值赋给下拉框
                // 2. 将选中的项目的 number name 分别传到 input hidden 中
                // 3. 给选中的 li 加上 active 类名
                _this.dataA = e.target.innerText;
                if (_this.renderList.zclx == '固定资产') {
                    $('.yzkm_id1').val(e.target.getAttribute('data-number'));
                    $('.yzkm_name1').val(e.target.getAttribute('data-name'));
                } else if (_this.renderList.zclx == '无形资产') {
                    $('.yzkm_id2').val(e.target.getAttribute('data-number'));
                    $('.yzkm_name2').val(e.target.getAttribute('data-name'));
                } else if (this.renderList.zclx == '待摊费用') {
                    $('.yzkm_id3').val(e.target.getAttribute('data-number'));
                    $('.yzkm_name3').val(e.target.getAttribute('data-name'));
                } else if (this.renderList.zclx == '长摊费用') {
                    $('.yzkm_id4').val(e.target.getAttribute('data-number'));
                    $('.yzkm_name4').val(e.target.getAttribute('data-name'));
                }

                $(e.target).addClass('active').siblings().removeClass('active');
            },

            // 累计折旧
            selectRzkmItem2: function (e) {
                var _this = this;
                _this.data2 = e.target.innerText;
                $('.ljzjkm_id').val(e.target.getAttribute('data-number'));
                $('.ljzjkm_name').val(e.target.getAttribute('data-name'));
                $(e.target).addClass('active').siblings().removeClass('active');
            },
            // 编辑
            selectRzkmItemB: function (e) {
                var _this = this;
                // console.log(e.target)
                _this.dataB = e.target.innerText;

                if (_this.renderList.zclx == '固定资产') {
                    $('.ljzjkm_id1').val(e.target.getAttribute('data-number'));
                    $('.ljzjkm_name1').val(e.target.getAttribute('data-name'));
                } else if (_this.renderList.zclx == '无形资产') {
                    $('.ljzjkm_id2').val(e.target.getAttribute('data-number'));
                    $('.ljzjkm_name2').val(e.target.getAttribute('data-name'));
                } else if (this.renderList.zclx == '待摊费用') {
                    $('.ljzjkm_id3').val(e.target.getAttribute('data-number'));
                    $('.ljzjkm_name3').val(e.target.getAttribute('data-name'));
                } else if (this.renderList.zclx == '长摊费用') {
                    $('.ljzjkm_id4').val(e.target.getAttribute('data-number'));
                    $('.ljzjkm_name4').val(e.target.getAttribute('data-name'));
                }

                $(e.target).addClass('active').siblings().removeClass('active');
            },

            // 成本费用
            selectRzkmItem3: function (e) {
                var _this = this;
                _this.data3 = e.target.innerText;
                $('.cbfykm_id').val(e.target.getAttribute('data-number'));
                $('.cbfykm_name').val(e.target.getAttribute('data-name'));
                $(e.target).addClass('active').siblings().removeClass('active');
            },
            // 编辑
            selectRzkmItemC: function (e) {
                var _this = this;
                // console.log(e.target)
                _this.dataC = e.target.innerText;

                if (_this.renderList.zclx == '固定资产') {
                    $('.cbfykm_id1').val(e.target.getAttribute('data-number'));
                    $('.cbfykm_name1').val(e.target.getAttribute('data-name'));
                } else if (_this.renderList.zclx == '无形资产') {
                    $('.cbfykm_id2').val(e.target.getAttribute('data-number'));
                    $('.cbfykm_name2').val(e.target.getAttribute('data-name'));
                } else if (this.renderList.zclx == '待摊费用') {
                    $('.cbfykm_id3').val(e.target.getAttribute('data-number'));
                    $('.cbfykm_name3').val(e.target.getAttribute('data-name'));
                } else if (this.renderList.zclx == '长摊费用') {
                    $('.cbfykm_id4').val(e.target.getAttribute('data-number'));
                    $('.cbfykm_name4').val(e.target.getAttribute('data-name'));
                }

                $(e.target).addClass('active').siblings().removeClass('active');
            },
            // 弹框生成凭证
            show_add_voucher: function (item) {
                var id = item.id;
                var voucher_id = item.voucher_id;
                // var voucher_id = this.invoice_list;
                // console.log(voucher_id);
                if (voucher_id != 0) {
                    layer.msg('该发票已生成记账凭证', {icon: 2, time: 1000});
                    return;
                }
                var items = {"type": '8', "id": id};
                items = JSON.stringify(items);
                localStorage.setItem('invoiceId', items);
                //console.log(items);
                layer.open({
                    type: 2,
                    title: '发票凭证预览页面',
                    shadeClose: true,
                    shade: 0.2,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['1200px', '96%'],
                    content: ['{{ url('book/voucher/addKeep') }}', 'yes'],
                });
            },

            // 点击凭证号
            addInvoice: function (item) {
                var items = item['voucher_id'];
                //console.log(items);
                localStorage.setItem('invoiceId', items);
                layer.open({
                    type: 2,
                    title: '凭证信息',
                    shadeClose: true,
                    shade: 0.2,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['1200px', '96%'],
                    content: ['{{ url('book/voucher/add') }}', 'yes']
                });
            },

            // 弹框编辑
            showEdit: function (index) {
                var _this = this;
                _this.$http.get('/book/asset/getAssetList', {params: _this.renderList}).then(function (response) {
                    if (response.body[index]['voucher_id'] == 0) {
                        // 1. 先判断自己处在哪个标签页(资产类型)上
                        // 2. 然后动态的去改变成对应的表单页
                        // console.log(_this.renderList);
                        if (_this.renderList.zclx == '固定资产') {
                            // 1. 点击编辑，记录参数
                            // 2. 弹出框依据参数向后台请求参数将对应的数据放入表单
                            // 3. 用 v-moodel 绑定修改的数据
                            // 4. 修改完毕后点击确定向后台发送请求，并主动触发 renderFormData 事件
                            _this.$http.get('/book/asset/getAssetList', {params: _this.renderList}).then(function (response) {
                                // 拿到数据
                                _this.editData = response.body[index];
                                $('.idEdit1').val(_this.editData.id);
                                $('.zcType1').val(_this.editData.zclb);
                                $('.fdMethod1').val(_this.editData.zjff);
                                $('.yzkm_name1').val(_this.editData.yzkm);
                                $('.yzkm_id1').val(_this.editData.yzkm_id);
                                $('.ljzjkm_name1').val(_this.editData.ljzjkm);
                                $('.ljzjkm_id1').val(_this.editData.ljzjkm_id);
                                $('.cbfykm_name1').val(_this.editData.cbfykm);
                                $('.cbfykm_id1').val(_this.editData.cbfykm_id);

                                // 下拉框的值
                                _this.dataA = _this.editData.yzkm_id + '--' + _this.editData.yzkm;
                                _this.dataB = _this.editData.ljzjkm_id + '--' + _this.editData.ljzjkm;
                                _this.dataC = _this.editData.cbfykm_id + '--' + _this.editData.cbfykm;
                            });
                            layer.open({
                                type: 1,
                                title: '修改固定资产卡片',
                                shadeClose: true,
                                content: $('.edit1'),
                                skin: 'components',
                                area: ['860px', '700px'],
                                btn: ['确认', '取消'],
                                yes: function (index, layero) {
                                    // console.log(_this.serializeObject($('.editLayerData1')));
                                    _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.editLayerData1'))).then(function (response) {
                                        // console.log(response);
                                        _this.renderFormData();
                                    })
                                    layer.close(index);
                                    _this.resetForm($('.editLayerData1'));
                                },
                                btn2: function (index, layero) {
                                    _this.resetForm($('.editLayerData1'));
                                },
                                cancel: function () {
                                    _this.resetForm($('.editLayerData1'));
                                }
                            })
                        } else if (_this.renderList.zclx == '无形资产') {
                            _this.$http.get('/book/asset/getAssetList', {params: _this.renderList}).then(function (response) {
                                // console.log(response.body[index]);
                                // 拿到数据
                                _this.editData = response.body[index];
                                // console.log(_this.editData);
                                $('.idEdit2').val(_this.editData.id);
                                $('.zcType2').val(_this.editData.zclb);
                                $('.fdMethod2').val(_this.editData.zjff);
                                $('.yzkm_name2').val(_this.editData.yzkm);
                                $('.yzkm_id2').val(_this.editData.yzkm_id);
                                $('.ljzjkm_name2').val(_this.editData.ljzjkm);
                                $('.ljzjkm_id2').val(_this.editData.ljzjkm_id);
                                $('.cbfykm_name2').val(_this.editData.cbfykm);
                                $('.cbfykm_id2').val(_this.editData.cbfykm_id);

                                // 下拉框的值
                                _this.dataA = _this.editData.yzkm_id + '--' + _this.editData.yzkm;
                                _this.dataB = _this.editData.ljzjkm_id + '--' + _this.editData.ljzjkm;
                                _this.dataC = _this.editData.cbfykm_id + '--' + _this.editData.cbfykm;
                            });
                            layer.open({
                                type: 1,
                                title: '修改无形资产卡片',
                                shadeClose: true,
                                content: $('.edit2'),
                                skin: 'components',
                                area: ['860px', '700px'],
                                btn: ['确认', '取消'],
                                yes: function (index, layero) {
                                    // console.log(_this.serializeObject($('.editLayerData2')));
                                    _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.editLayerData2'))).then(function (response) {
                                        // console.log(response);
                                        _this.renderFormData();
                                    })
                                    layer.close(index);
                                    _this.resetForm($('.editLayerData2'));
                                },
                                btn2: function (index, layero) {
                                    _this.resetForm($('.editLayerData2'));
                                },
                                cancel: function () {
                                    _this.resetForm($('.editLayerData2'));
                                }
                            })
                        } else if (_this.renderList.zclx == '待摊费用') {
                            _this.$http.get('/book/asset/getAssetList', {params: _this.renderList}).then(function (response) {
                                // console.log(response.body[index]);
                                // 拿到数据
                                _this.editData = response.body[index];
                                // console.log(_this.editData);
                                $('.idEdit3').val(_this.editData.id);
                                $('.zcType3').val(_this.editData.zclb);
                                $('.fdMethod3').val(_this.editData.zjff);
                                $('.yzkm_name3').val(_this.editData.yzkm);
                                $('.yzkm_id3').val(_this.editData.yzkm_id);
                                $('.cbfykm_name3').val(_this.editData.cbfykm);
                                $('.cbfykm_id3').val(_this.editData.cbfykm_id);

                                // 下拉框的值
                                _this.dataA = _this.editData.yzkm_id + '--' + _this.editData.yzkm;
                                _this.dataC = _this.editData.cbfykm_id + '--' + _this.editData.cbfykm;
                            });
                            layer.open({
                                type: 1,
                                title: '修改待摊费用卡片',
                                shadeClose: true,
                                content: $('.edit3'),
                                skin: 'components',
                                area: ['860px', '630px'],
                                btn: ['确认', '取消'],
                                yes: function (index, layero) {
                                    // console.log(_this.serializeObject($('.editLayerData3')));
                                    _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.editLayerData3'))).then(function (response) {
                                        // console.log(response);
                                        _this.renderFormData();
                                    })
                                    layer.close(index);
                                    _this.resetForm($('.editLayerData3'));
                                },
                                btn2: function (index, layero) {
                                    _this.resetForm($('.editLayerData3'));
                                },
                                cancel: function () {
                                    _this.resetForm($('.editLayerData3'));
                                }
                            })
                        } else if (_this.renderList.zclx == '长摊费用') {
                            _this.$http.get('/book/asset/getAssetList', {params: _this.renderList}).then(function (response) {
                                // console.log(response.body[index]);
                                // 拿到数据
                                _this.editData = response.body[index];
                                // console.log(_this.editData);
                                $('.idEdit4').val(_this.editData.id);
                                $('.zcType4').val(_this.editData.zclb);
                                $('.fdMethod4').val(_this.editData.zjff);
                                $('.yzkm_name4').val(_this.editData.yzkm);
                                $('.yzkm_id4').val(_this.editData.yzkm_id);
                                $('.cbfykm_name4').val(_this.editData.cbfykm);
                                $('.cbfykm_id4').val(_this.editData.cbfykm_id);

                                // 下拉框的值
                                _this.dataA = _this.editData.yzkm_id + '--' + _this.editData.yzkm;
                                _this.dataC = _this.editData.cbfykm_id + '--' + _this.editData.cbfykm;
                            });
                            layer.open({
                                type: 1,
                                title: '修改长摊费用卡片',
                                shadeClose: true,
                                content: $('.edit4'),
                                skin: 'components',
                                area: ['860px', '630px'],
                                btn: ['确认', '取消'],
                                yes: function (index, layero) {
                                    // console.log(_this.serializeObject($('.editLayerData4')));
                                    _this.$http.post('/book/asset/storeAsset', _this.serializeObject($('.editLayerData4'))).then(function (response) {
                                        // console.log(response);
                                        _this.renderFormData();
                                    })
                                    layer.close(index);
                                    _this.resetForm($('.editLayerData4'));
                                },
                                btn2: function (index, layero) {
                                    _this.resetForm($('.editLayerData4'));
                                },
                                cancel: function () {
                                    _this.resetForm($('.editLayerData4'));
                                }
                            })
                        }
                        ;
                    } else {
                        layer.msg('已生成凭证不可编辑', {icon: 2, time: 2000});
                    }
                })


            },

            // 弹框资产清理
            showClean: function () {
                var index = layer.open({
                    type: 1,
                    title: '固定资产清理',
                    shadeClose: true,
                    content: $('.clean'),
                    skin: 'components',
                    area: ['440px', '300px'],
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        layer.close(index)
                        var index1 = layer.open({
                            type: 1,
                            title: '信息',
                            content: $('.cleanConfirm'),
                            area: ['400px', '166px'],
                            skin: 'components',
                            btn: ['确认', '取消'],
                            yes: function (index, layero) {
                                layer.close(index1)
                            }
                        })
                    },
                    btn2: function (index, layero) {

                    },
                    cancel: function () {

                    }
                })
            },

            // 弹框暂停折旧
            showStopZj: function () {
                var _this = this;
                layer.open({
                    type: 1,
                    title: '固定资产暂停折旧',
                    content: $('.zantingzhejiu'),
                    area: ['230px', '260px'],
                    skin: 'components',
                    btn: ['确认', '取消'],
                    yes: function (index, layero) {
                        layer.close()
                    }
                })
            },

            // 切换不正常资产(未用)
            abnormalZc: function () {
                this.i++;
                if (this.i % 2 == 1) {
                    $('.fzczc').css('backgroundColor', '#568bfb');
                    $('.fzczc').text('显示正常资产');
                } else {
                    $('.fzczc').css('backgroundColor', '#fd3336');
                    $('.fzczc').text('显示非正常资产');
                }

            },

            // 表单序列化转对象
            serializeObject: function (form) {
                var o = {};
                $.each(form.serializeArray(), function (index) {
                    if (o[this['name']]) {
                        o[this['name']] = o[this['name']] + "," + this['value'];
                    } else {
                        o[this['name']] = this['value'];
                    }
                });
                return o;
            },

            // 全选和反选
            selectAll: function () {
                var _this = this;
                this.isCheckedAll = !this.isCheckedAll;
                var canISelectAll = true;
                for (var i = 0; i < _this.formData.length; i++) {
                    if (_this.formData[i].voucher_id != 0) {
                        canISelectAll = false;
                        layer.msg('有已生成凭证的项目不可编辑', {icon: 2, time: 2000});
                        _this.isCheckedAll = false;
                        $('.select-checkbox-all').each(function (index, item) {
                            item.checked = false;
                        })
                    }
                }
                if (canISelectAll == true) {
                    if (_this.isCheckedAll) {
                        $('.select-checkbox').each(function (index, item) {
                            item.checked = true;
                        })
                        // 全选时
                        _this.selectedItems = []
                        _this.formData.forEach(function (item) {
                            _this.selectedItems.push(item.id)
                        })
                    } else {
                        $('.select-checkbox').each(function (index, item) {
                            item.checked = false;
                        })
                        this.selectedItems = []
                    }
                    // console.log(this.selectedItems)
                }
            },

            // 选中单个项目
            selectItem: function (param, e) {
                var _this = this;
                // console.log(param)
                // 没有生成凭证的才可以被选中
                _this.formData.forEach(function (item, index) {
                    if (item.id == param) {
                        // console.log(item.voucher_id)
                        // 判断点击的数据是否已生成凭证
                        if (item.voucher_id != 0) {
                            e.target.checked = false;
                            layer.msg('已生成凭证不可编辑', {icon: 2, time: 2000});
                        } else {
                            // console.log(e.target.checked)
                            if (e.target.checked == true) {
                                var idIndex = _this.selectedItems.indexOf(param)
                                if (idIndex >= 0) {
                                    // 如果已经包含了该id, 则去除(单选按钮由选中变为非选中状态)
                                    _this.selectedItems.splice(idIndex, 1)
                                } else {
                                    // 选中该checkbox
                                    _this.selectedItems.push(param)
                                }
                                // console.log(this.selectedItems)

                            } else {
                                var idIndex = _this.selectedItems.indexOf(param)
                                if (idIndex >= 0) {
                                    // 如果已经包含了该id, 则去除(单选按钮由选中变为非选中状态)
                                    _this.selectedItems.splice(idIndex, 1)
                                }
                            }
                            // console.log(this.selectedItems)
                        }
                    }
                })

            },

            // 求和占位
            sum: function () {
                var _this = this;
                var sum1 = 0;
                var sum2 = 0;
                var sum3 = 0;
                var sum4 = 0;
                var sum5 = 0;
                for (var i = 0; i < _this.formData.length; i++) {
                    // console.log(_this.formData[i]);
                    sum1 = (sum1 + (_this.formData[i].yz - 0));
                    sum2 = (sum2 + (_this.formData[i].cz - 0));
                    sum3 = (sum3 + (_this.formData[i].zjje - 0));
                    sum4 = (sum4 + (_this.formData[i].ljzj - 0));
                }
                sum5 = sum1 - sum4;
                _this.yzTotal = sum1;
                _this.czTotal = sum2;
                _this.zjjeTotal = sum3;
                _this.ljzjTotal = sum4;
                _this.jzTotal = sum5;
            },

            // 删除
            deleteItems: function (param1, param2) {
                var _this = this;
                if (param1 != null) { // 点击单个的删除
                    if (param2 == 0) {
                        _this.selectedItems = [param1];

                        this.$http.post('/book/asset/delAsset', {id: _this.selectedItems}).then(function (response) {
                            // console.log(_this.selectedItems);
                            if (response.body.status == 1) {
                                layer.msg('删除成功')
                            } else {
                                layer.msg('删除失败')
                            }
                            // 再重新渲染一下
                            this.renderFormData();
                        })
                    } else {
                        layer.msg('已生成凭证不可编辑', {icon: 2, time: 2000});
                    }
                } else {
                    if (!_this.selectedItems.length) { // 没有选中的项目
                        layer.msg('请选择要删除的项目', {time: 1000})
                        return false;
                    } else {
                        this.$http.post('/book/asset/delAsset', {id: _this.selectedItems}).then(function (response) {
                            // console.log(_this.selectedItems);
                            if (response.body.status == 1) {
                                layer.msg('删除成功')
                            } else {
                                layer.msg('删除失败')
                            }
                            // 再重新渲染一下
                            this.renderFormData();
                        })
                    }
                }


                _this.moreFlag0 = false;
                _this.moreFlag1 = false;
                _this.moreFlag2 = false;
                _this.moreFlag3 = false;
            },

            // 弹框关闭后重置表单
            resetForm: function (select) {
                var _this = this;
                select[0].reset();
                _this.data1 = '';
                _this.data2 = '';
                _this.data3 = '';
                // _this.czl = '';
                _this.editData.yz = '';
                $('.rzkm').find('li').removeClass('active');
            },

            // 点击空白处收起下拉框
            clickBlank: function () {
                var _this = this;
                document.addEventListener('click', function (e) {
                    // console.log((e.target))
                    if (!_this.$refs.more0.contains(e.target)) {
                        _this.moreFlag0 = false;
                    }
                    if (!_this.$refs.more1.contains(e.target)) {
                        _this.moreFlag1 = false;
                    }
                    if (!_this.$refs.more2.contains(e.target)) {
                        _this.moreFlag2 = false;
                    }
                    if (!_this.$refs.more3.contains(e.target)) {
                        _this.moreFlag3 = false;
                    }
                    var _con = $('input');
                    if (!_con.is(e.target)) {
                        _this.flag1 = false;
                        _this.flag2 = false;
                        _this.flag3 = false;
                        _this.flagA = false;
                        _this.flagB = false;
                        _this.flagC = false;
                        // console.log(_this.flag1)
                    }
                    // console.log(_con)
                })

            },

            foldOther: function () {
                this.flag2 = false;
                this.flag3 = false;
            },

            // 输入框中的数字在 0 ~ 100 之间
            testNum: function (num) {
                var reg1 = /^([1-9]{1}[0-9]{0,1}|0|100)([.][0-9]{1,2})?$/;
                if (!reg1.test(num + '')) {
                    return false;
                } else {
                    return true;
                }
            },
        },
        //通过计算属性过滤数据
        computed:{
            list: function(){
                var arrByZM = [];
                //console.log(this.curIndex);
                for (var i=0; i < this.yzkmOptions.length; i++){
                    if((this.yzkmOptions[i].number + this.yzkmOptions[i].name).search(this.dataSearch) != -1){
                        arrByZM.push(this.yzkmOptions[i]);
                    }
                }
                return arrByZM;
            }
        }
    })
</script>
</body>

</html>
