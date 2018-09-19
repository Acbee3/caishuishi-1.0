<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', '财税狮智能财税业务平台') }}</title>
    <link rel="stylesheet" href="{{url("system/common/css/reset.css")}}">
    <link rel="stylesheet" href="{{url("agent")}}">

    <!--header-->
    <link rel="stylesheet" href="{{url("system/common/css/header.css")}}">
    <link rel="stylesheet" href="{{url("system/common/layui/css/layui.css")}}">
    <link rel="stylesheet" href="{{url("system/views/css/main.css")}}">
    <link rel="stylesheet" href="{{url("system/common/css/table.css")}}">
    <link rel="stylesheet" href="{{url("system/common/css/agent_center_table.css")}}">
    <link rel="stylesheet" href="{{url("system/views/css/search.css")}}">

    <link rel="shortcut icon" href="{{url("system/logoicon.png")}}">

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="{{url("system/common/js/html5shiv.min.js")}}">
    <link rel="stylesheet" href="{{url("system/common/js/respond.min.js")}}">
    <![endif]-->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div id="app">
    <div class="header">
        <div class="headerTop">
            <div class="headerLeft">
                <img src="{{url("system/common/images/logoLitter.png")}}" alt="logo">
            </div>
            <div class="headerRight">
                <div class="header-item">
                    <i><img src="{{url("system/common/images/company.png")}}" alt="company"></i>
                    <span>@{{company_name}}</span>
                </div>
                <div class="header-item">
                    <i><img src="{{url("system/common/images/notes.png")}}" alt="company"></i>
                    <span>通知公告00</span>
                </div>
                <div class="header-item">
                    <i><img src="{{url("system/common/images/personalcenter.png")}}" alt="company"></i>
                    @guest

                    @else
                        <span><a href="{{ route('system') }}">{{ Auth::user()->name }}</a></span>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('退出') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{csrf_field()}}
                        </form>
                    @endguest
                </div>
            </div>
        </div>
        <div class="tab">
            <ul class="tabList">
                <li class="activeBg"><a href="/agent/system">首 页</a></li>
                <li v-for="item in items" :key="item.id">
                    <a :href="item.action_route">
                        @{{item.action_name}}
                    </a>
                </li>
                <li style="display: none;">
                    基础设置
                    <ul class="showList" style="display: none">
                        <li>角色权限</li>
                        <li>部门人员</li>
                        <li>客户授权</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="container">
        <main>
            @yield('content')
        </main>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn borderBtn layui-btn-xs borderBtn" lay-event="detail">进入账簿</a>
</script>
{{--<script src="{{url("system/common/js/vue.min.js")}}"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
{{--<script src="{{url("system/common/js/jquery-2.2.4.js")}}"></script>--}}
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="{{url("system/common/layui/layui.js")}}" charset="utf-8"></script>
<script src="https://cdn.bootcss.com/echarts/2.2.0/echarts.js"></script>
<script>
    new Vue({
        el: "#app",
        data() {
            return {
                activeIndex: 'home',
                value1: '',
                codeList: false,
                getDate: '企业编码或名称',
                company_name: '***',
                route_now: '',
                tableData3: [
                    {
                        "code": "00217",
                        "name": "苏州财税狮有限公司",
                        "currentPeriod": "2018-04",
                        "rationale": "已完成",
                        "account": "未完成",
                        "declaration": "未开始"
                    },
                    {
                        "code": "00217",
                        "name": "苏州财税狮有限公司",
                        "currentPeriod": "2018-04",
                        "rationale": "已完成",
                        "account": "未完成",
                        "declaration": "未开始"
                    },
                    {
                        "code": "00217",
                        "name": "苏州财税狮有限公司",
                        "currentPeriod": "2018-04",
                        "rationale": "已完成",
                        "account": "未完成",
                        "declaration": "未开始"
                    }
                ],
                /*----此处模拟数据------后台获取items: []--------*/
                items000: [
                    {
                        index: 'home',
                        title: '首页'
                    },
                    {
                        index: 'customer',
                        title: '客户信息'
                    },
                    {
                        index: '3',
                        title: '基础设置',
                        subs: [
                            {
                                index: 'rose',
                                title: '角色权限'
                            },
                            {
                                index: 'officer',
                                title: '部门人员'
                            },
                            {
                                index: 'authorization',
                                title: '客户授权'
                            }
                        ]
                    }
                ],
                items:[],
                list: [],
                options: [
                    {
                    value: '1',
                    label: '企业编码或名称'
                },
                    {
                    value: '2',
                    label: '财务进度'
                },
                    {
                    value: '3',
                    label: '报税进度'
                }
                ],
                options000: []
            }
        },
        created: function () {
            /*this.getDate();*/

            this.$http.get('{{url("/cfs/api/agent_header")}}').then(function(response) {
                response = response.body;
                this.items =  response.data.items;
                this.company_name = response.data.company_name;
                //console.log(this.items);
            })
        },
        mounted() {
            //console.log(this.getDate());
            //this.getDate();

        },
        methods: {
            handleSelect(key, keyPath) {
                //console.log(key, keyPath)
            },
            getNewAdds(value) {
                this.codeList = false;
                this.getDate = value
            },
            getDate() {
                this.$http.get('{{url("/cfs/api/agent_header")}}').then(function(response) {
                    response = response.body;
                    this.items =  response.data
                })
            }
        }
    })
</script>
<script src="{{url("system/views/js/navBar.js")}}"></script>
<script src="{{url("system/views/js/indexTable.js")}}"></script>
<script src="{{url("system/views/js/customerTable.js")}}"></script>
<script>
    // 基于准备好的dom，初始化echarts实例
    /*var myChart = echarts.init(document.getElementById('main'));
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
    myChart3.setOption(option2);*/
</script>
<div class="footer">@yield('footer')</div>
</body>
</html>