@extends('book.layout.base')

@section('title')外籍人员正常工资薪金@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--薪酬表-->
    <link rel="stylesheet" href="{{asset("css/book/paid/payrollEditor.css")}}">
@endsection

@section('content')
    <div id="foreignEditor" v-cloak>
        <div class="payrollEditorWrapper">
            <div class="payrollEditorMenu">
                <div class="payrollEditorMenu-left">
                    <div class="payrollMenuBlack">
                        <a href="javascript:history.back(-1);" class="payrollBlack">返回</a>
                    </div>
                </div>
                <div class="payrollEditorMenu-right">
                    <a href="javascript:void(0);" @click="doDelay">新增</a>
                    <a href="javascript:void(0);" @click="doDelay">保存</a>
                    <a href="javascript:void(0);" @click="doDelay">删除</a>
                    <a href="javascript:void(0);" @click="doDelay">导出Excel</a>
                    <a href="javascript:void(0);" @click="doDelay">打印</a>
                    <a href="javascript:void(0);" @click="doDelay">复制工资表</a>
                </div>
            </div>
            <div class="payrollYearTable">
                <div class="yearTable-header">
                    <table>
                        <thead>
                        <tr>
                            <th class="width2">
                                <input type="checkbox">
                            </th>
                            <th class="width3">序号</th>
                            <th class="width7">姓名</th>
                            <th class="width7">费用类型</th>
                            <th class="width7">适用公式</th>
                            <th class="width7">收入</th>
                            <th class="width7">免税所得</th>
                            <th class="width7">代扣社保</th>
                            <th class="width7">其他</th>
                            <th class="width7">代扣公积金</th>
                            <th class="width7">实际捐赠额</th>
                            <th class="width11">允许列支的捐赠比例(%)</th>
                            <th class="width7">代扣个税</th>
                            <th class="width7">实发工资</th>
                            <th class="width7">操作</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="yearTable-Center">
                    <table>
                        <tr v-for="(item,index) in foreignTables" :key="item.id">
                            <td class="width2"><input type="checkbox"></td>
                            <td class="width3">@{{index+1}}</td>
                            <td class="width7">@{{item.name}}</td>
                            <td class="width7">@{{item.moneyType}}</td>
                            <td class="width7">@{{item.gs}}</td>
                            <td class="width7">@{{item.sr}}</td>
                            <td class="width7">@{{item.mssd}}</td>
                            <td class="width7">@{{item.social}}</td>
                            <td class="width7">@{{item.other}}</td>
                            <td class="width7">@{{item.publicFond}}</td>
                            <td class="width7">@{{item.actual}}</td>
                            <td class="width11">@{{item.percent}}</td>
                            <td class="width7">@{{item.tax}}</td>
                            <td class="width7">@{{item.factMoney}}</td>
                            <td class="width7">
                                <i class="iconfont">&#xe606;</i>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        new Vue({
            "el": '#foreignEditor',
            data: {
                foreignTables:[]
            },
            methods: {
                /*------ 暂时缓开发提示 delay ---------*/
                doDelay:function(){
                    layer.msg("无操作权限!", {icon: 2, time: 2000});
                },


            }

        })
    </script>
@endsection