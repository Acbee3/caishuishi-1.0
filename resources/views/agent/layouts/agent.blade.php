<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', '财税狮智能财税业务平台') }}</title>
    <link rel="stylesheet" href="{{url("agent/common/css/reset.css")}}">
    <link rel="stylesheet" href="{{url("common/fonts/iconfont.css?v=2018082801")}}">

    <!--header-->
    <link rel="stylesheet" href="{{url("agent/common/css/header.css")}}">
    <link rel="stylesheet" href="{{url("agent/common/layui/css/layui.css")}}">
    <link rel="stylesheet" href="{{url("agent/views/css/main.css")}}">
    <link rel="stylesheet" href="{{url("agent/common/css/table.css?v=2018082301")}}">
    <link rel="stylesheet" href="{{url("agent/common/css/agent_center_table.css?v=2018082301")}}">
    <link rel="stylesheet" href="{{url("agent/views/css/search.css")}}">

    <link rel="shortcut icon" href="{{url("agent/logoicon.png")}}">

    <!--[if lt IE 9]>
    <link rel="stylesheet" href="{{url(" agent/common/js/html5shiv.min.js")}}">
    <link rel="stylesheet" href="{{url(" agent/common/js/respond.min.js")}}">
    <![endif]-->
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .icon {
            width: 24px; height: 24px;
            vertical-align: middle;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="header">
        <div class="headerTop">
            <div class="headerLeft">
                <img src="{{url("agent/common/images/logoLitter.png")}}" alt="logo">
            </div>
            <div class="headerRight">
                <div class="header-item">
                    {{--<i><img src="{{url("agent/common/images/company.png")}}" alt="company"></i>--}}
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-qiye"></use>
                    </svg>
                    <span>{{ Auth::guard('agent')->user()->name  }}</span>
                </div>
                <div class="header-item">
                    {{--<i><img src="{{url("agent/common/images/notes.png")}}" alt="company"></i>--}}
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-xiaoxi2"></use>
                    </svg>
                    <span>通知公告</span>
                </div>
                <div class="header-item">
                    <svg class="icon" aria-hidden="true">
                        <use xlink:href="#icon-tuichu1"></use>
                    </svg>
                    {{--<i><img src="{{url("agent/common/images/personalcenter.png")}}" alt="company"></i>--}}
                    <a href="{{ url('/agent/login/logout') }}">{{ __('退出') }}</a>
                    @guest

                    @else
                        <span><a href="{{ route('agent') }}">{{ Auth::user()->name }}</a></span>
                        <a href="{{ route('agent.logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            {{ __('退出') }}
                        </a>
                        <form id="logout-form" action="{{ route('agent.logout') }}" method="POST" style="display: none;">
                            {{csrf_field()}}
                        </form>
                    @endguest
                </div>
            </div>
        </div>
        <div class="menu_bar tab" >
            <ul class="tabList main_menu">
                <li class="activeBg"><a href="/agent/system">首 页</a></li>
                <li v-for="(item,index) in items" :key="item.index" @mouseenter="showMenus(index)" @mouseleave="hiddenMenus(index)" >
                    <a :href="item.action_route">
                        @{{item.action_name}}
                    </a>
                    <ul v-if="item.child_arr && item.child_arr.length && ishow == index " class="showList" v-bind:class="{showmenu:ishowmenu}" style="display:none;">
                        <li v-for="item in item.child_arr" :key="item.id" >
                            <a :href="item.action_route">@{{item.action_name}}</a>
                        </li>
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
{{--<script src="{{url("agent/common/js/vue.min.js")}}"></script>--}}
<script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
<script src="{{url("vue-resource/dist/vue-resource.min.js")}}"></script>
{{--<script src="{{url("agent/common/js/jquery-2.2.4.js")}}"></script>--}}
<script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="{{url("agent/common/layui/layui.js")}}" charset="utf-8"></script>
<script src="https://cdn.bootcss.com/echarts/2.2.0/echarts.js"></script>

<script src="{{url("agent/views/js/navBar.js")}}"></script>
<script src="{{url("agent/views/js/indexTable.js")}}"></script>
<script src="{{url("agent/views/js/customerTable.js")}}"></script>
<script src="{{url("common/fonts/iconfont.js")}}"></script>

<script>
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
</script>
<div class="footer">@yield('footer')</div>
<script>
    var header =  new Vue({
        el: ".header",
        data: {
            dialog:false,
            getDate: '企业编码或名称',
            codeList: false,
            customerMore: false,
            ishow:false,
            ishowmenu:false,
            company_name: '***',
            items:[
                {
                    child_arr:[]
                }
            ]
        },
        created:function() {
            this.getAgentmenu();
        },
        methods: {

            getAgentmenu:function(){
                this.$http.get('{{url("/agent/api/agent_header")}}').then(function(response) {
                    response = response.body;
                    this.items =  response.data.items;
                    this.company_name = response.data.company_name;
                    //alert(response.data.company_name);
                })
            },
            showMenus:function(index) {
                this.ishow = index
                this.ishowmenu = true
            },
            hiddenMenus:function(index) {
                this.ishow = index
                this.ishowmenu = false
            }
        }
    })
</script>
</body>
</html>