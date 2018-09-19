@extends('agent.layouts.agent')

@section('content')
    <div class="menuTop">
        <div class="menuTopLeft">
            <div class="statistics">
                <div class="export">
                    <span>进度统计:</span>
                    <span class="exportText">导出</span>
                </div>
                <div class="date">
                </div>
            </div>
            <div class="charts">
                <div id="main" style="height:256px;"></div>
                <div id="main2" style="height:256px;"></div>
                <div id="main3" style="height:256px;"></div>
            </div>
        </div>
        <div class="menuTopRight"></div>
    </div>
    <div class="menuBottom">
        <div class="search">
            <div class="selectFilter">
                <div class="selectHead" @click="codeList = !codeList">
                    <span class="titleTop"><!-- TODO @{{getDate}} --></span>
                    <i class="icon iconfont icon--xialajiantou"></i>
                </div>
                <!-- TODO
                <ul class="showTitle" v-show="codeList">
                    <li v-for="item in options" :key="item.index" @click="getNewAdds(item.label)">
                        @{{item.label}}
                    </li>
                </ul>
                -->
            </div>
            <div class="searchContainer">
                <input type="text" placeholder="请输入企业编码或名称">
                <i class="icon iconfont icon-search"></i>
            </div>
        </div>
        <div class="tableWrapper">
            <table class="layui-table" id="index" lay-filter="demo">
            </table>
        </div>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
    <script>
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
        var myChart2 = echarts.init(document.getElementById('main2'));
        var myChart3 = echarts.init(document.getElementById('main3'));
        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '理票',
                x: '16',
                y:'12',
                textStyle:{
                    color:'#333',
                    fontStyle:'normal',
                    fontWeight:'normal',
                    //字体大小
                    fontSize:14
                }
            },
            series : [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius: '55%',
                    data:[
                        {value:235, name:'未开始:9(2%)'},
                        {value:274, name:'进行中:9(2%)'},
                        {value:310, name:'已完成:9(2%)'}
                    ]
                }
            ]
        };
        var option1 = {
            title: {
                text: '记账',
                x: '16',
                y:'12',
                textStyle:{
                    color:'#333',
                    fontStyle:'normal',
                    fontWeight:'normal',
                    fontSize:14
                }
            },
            series : [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius: '55%',
                    data:[
                        {value:235, name:'未开始:9(2%)'},
                        {value:274, name:'进行中:9(2%)'},
                        {value:310, name:'已完成:9(2%)'}
                    ]
                }
            ]
        };
        var option2 = {
            title: {
                text: '报税',
                x: '16',
                y:'12',
                textStyle:{
                    color:'#333',
                    fontStyle:'normal',
                    fontWeight:'normal',
                    fontSize:14
                }
            },
            series : [
                {
                    name: '访问来源',
                    type: 'pie',
                    radius: '55%',
                    data:[
                        {value:235, name:'未开始:9(2%)'},
                        {value:274, name:'进行中:9(2%)'},
                        {value:310, name:'已完成:9(2%)'}
                    ]
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
        myChart2.setOption(option1);
        myChart3.setOption(option2);
    </script>
@endsection