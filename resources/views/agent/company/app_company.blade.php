<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <title>{{ config('app.name', '财税狮智能财税业务平台') }}</title>
  <link rel="stylesheet" href="{{url("agent/common/css/reset.css")}}">
  <link rel="stylesheet" href="{{url("common/fonts/iconfont.css")}}">

  <!--header-->
  <link rel="stylesheet" href="{{url("agent/common/css/header.css?v=2018082201")}}">
  <link rel="stylesheet" href="{{url("agent/common/layui/css/layui.css")}}">
  <link rel="stylesheet" href="{{url("agent/views/css/main.css")}}">
  <link rel="stylesheet" href="{{url("agent/common/css/table.css")}}">
  <link rel="stylesheet" href="{{url("agent/common/css/agent_center_table.css")}}">
  <link rel="stylesheet" href="{{url("agent/common/css/agent/customer.css?v=2018082202")}}">
  <link rel="stylesheet" href="{{url("agent/common/css/agent/search.css?v=2018082201")}}">
  <link rel="stylesheet" href="{{url("agent/common/css/agent/editor.css")}}">

  <link rel="shortcut icon" href="{{url("agent/logoicon.png")}}">

  <!--[if lt IE 9]>
  <link rel="stylesheet" href="{{url("agent/common/js/html5shiv.min.js")}}">
  <link rel="stylesheet" href="{{url("agent/common/js/respond.min.js")}}">
  <![endif]-->

  {{--<script src="{{url("agent/common/js/vue.min.js")}}"></script>--}}
  <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
  <script src="{{url("agent/common/js/vue-resource.js")}}"></script>
  {{--<script src="{{url("agent/common/js/jquery-2.2.4.js")}}"></script>--}}
  <script src="https://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
  <script src="{{url("agent/common/layui/layui.js")}}" charset="utf-8"></script>
  <script src="{{url("common/fonts/iconfont.js")}}"></script>
  <script src="https://cdn.bootcss.com/echarts/2.2.0/echarts.js"></script>


  <style>
    .modelDate{
      position:fixed;
      width:100%;
      height:100%;
      left:0;
      top:0;
      background:rgba(0,0,0,0.4);
      z-index:2;
    }
    .dialog{
      width:400px;
      height:150px;
      border:1px solid #333;
      background:#fff;
      position:absolute;
      left:50%;
      top:50%;
      margin:-75px 0 0 -200px;
      z-index:4;
    }
    .icon {
        width: 24px; height: 24px;
        vertical-align: middle;
        fill: currentColor;
        overflow: hidden;
    }
  </style>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <svg class="icon" aria-hidden="true">
                <use xlink:href="#icon-xiaoxi2"></use>
            </svg>
          {{--<i><img src="{{url("agent/common/images/notes.png")}}" alt="company"></i>--}}
          <span>通知公告</span>
        </div>
        <div class="header-item">
          {{--<i><img src="{{url("agent/common/images/personalcenter.png")}}" alt="company"></i>--}}
            <svg class="icon" aria-hidden="true">
                <use xlink:href="#icon-tuichu1"></use>
            </svg>
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
    <div class="tab sys_menu_area" >
      <ul class="tabList main_menu">
        <li class="activeBg"><a href="/agent/system">首 页</a></li>
        <li v-for="(item,index) in items" :key="item.index" @mouseenter="showMenus(index)" @mouseleave="hiddenMenus(index)">
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

<script>
    var curOption
    new Vue({
        el: "#app",
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
            ],
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
            tableDate: [],
            list:["基本信息","账套信息"],
            nowIndex: 0,
            selected: '{{\App\Models\Company::Get_level_Set($cid)}}',
            selectOption:[
                { text: '1', value: '1' },
                { text: '2', value: '2' },
                { text: '3', value: '3' },
                { text: '4', value: '4' },
                { text: '5', value: '5' },
                { text: '6', value: '6' },
                { text: '7', value: '7' },
                { text: '8', value: '8' },
                { text: '9', value: '9' },
                { text: '10', value: '10' },
                { text: '11', value: '11' },
                { text: '12', value: '12' },
            ],
            boxs:[]
        },
        created:function() {
            this.getAgentmenu();
            this.boxs = [];
            for(var i=0;i<(this.selected-1);i++){
                var data = '';
                this.boxs.push(data);
            }
        },
        mounted() {
            this.clickBlank()
        },
        methods: {
            //点击空白处相应div隐藏
            clickBlank: function () {
                var _this = this;
                $(document).click(function (event) {
                    var _con = $('.curList');  // 设置目标区域
                    if (!_con.is(event.target) && _con.has(event.target).length === 0) { // Mark 1
                        //银行下拉框
                        _this.codeList = false;
                        _this.customerMore = false;
                    }
                });
            },
            getNewAdds:function(value) {
                this.codeList = false
                this.getDate = value
            },
            getAgentmenu:function(){
                this.$http.get('{{url("/agent/api/agent_header")}}').then(function(response) {
                    response = response.body;
                    this.items =  response.data.items;
                    this.company_name = response.data.company_name;
                    //this.selected = 5;
                })
            },
            see:function(index) {
                this.dialog = true
                /*  this.name = this.tableDate[index].id*/
                this.$refs.title.innerHTML = this.tableDate[index].id
            },
            close:function(){
                this.dialog = false
            },
            showMenus:function(index) {
                this.ishow = index
                this.ishowmenu = true
            },
            hiddenMenus:function(index) {
                this.ishow = index
                this.ishowmenu = false
            },
            changeStatus:function(value) {
                console.log(value)
            },
            showTable:function(index){
                this.nowIndex = index
            },
            chooseOption:function(){
                this.boxs = []
                for(var i=0;i<(this.selected-1);i++){
                    var data = ''
                    this.boxs.push(data)
                }
            }
        }
    })
</script>
<script>
    layui.use(['table','jquery'], function(){
        var table = layui.table;
        $(".add").click(function(){
            layer.confirm('真的删除行么', function(index){
                layer.close(index);
            });
        })
    })
</script>
<script>
    /*----- 搜索 ok -----*/
    $("#icon-search").click(function(){
        layer.load(2, {shade: false, time: 500});
        setTimeout(function(){$("#search_btn").click();}, 500);
    });
    /*----- 启用和停用 ok -----*/
    $(".status_book").click(function(){
        var id = $(this).attr("vid");
        var status = $(this).attr("v_status");
        var name = $(this).attr("vname");
        var vhref = $(this).attr("vhref");
        if( status == 'yes'){
            var txt = '停用'+name;
        }else{
            var txt = '启用'+name;
        }
        layer.confirm('确定要 '+txt+' 吗？', {icon: 3, title: '提示',skin:'companyAlert'},
            function () {
                layer.closeAll();
                window.location.href = vhref;
            }
        );
    });
    /*----- 编辑 ok -----*/
    $(".edit_book").click(function(){
        var e_href = $(this).attr("vhref");
        window.location.href = e_href;
    });
    /*----- 查看 ok -----*/
    $(".view_book").click(function(){
        var v_href = $(this).attr("vhref");
        window.location.href = v_href;
    });
    /*----- 进入账薄 ok -----*/
    $(".enter_book").click(function(){
        var vid = $(this).attr("vid");
        var vencode = $(this).attr("vencode");
        var ac = $(this).attr("vac");
        if(ac.length == 1){
            var rto_href = "/book/home/"+vid+"/"+vencode;
            window.open(rto_href);
        }else{
            var v_href = "/agent/companies/edit?id="+vid+"&vzt=yes";
            layer.confirm("此公司尚未完善账套信息，<br>需完善后才能进入账薄操作!", {icon: 3, title: '提示'},
                function () {
                    layer.closeAll();
                    window.location.href = v_href;
                }
            );
        }
    });
    /*----- 删除 ok -----*/
    $(".del_book").click(function(){
        var vid = $(this).attr("vid");
        var v_href = $(this).attr("vhref");
        if (vid > 0) {
            layer.confirm(
                '确认要删除此公司吗？', {icon: 3, title: '提示'},
                function () {
                    $.ajax({
                        type: 'post',
                        url: '/agent/companies/api_del',
                        data: {'id': vid},
                        success: function (res) {
                            if (res.status == 'success') {
                                layer.msg(res.msg, {icon: 1, time: 2000});
                                setTimeout(function(){window.location.href = v_href;}, 2200);
                                return true;
                            } else {
                                layer.msg(res.msg, {icon: 2, time: 2000});
                                return false;
                            }
                        },
                        error: function () {
                            layer.msg('删除失败', {icon: 2, time: 2000});
                            return false;
                        }
                    });
                }
            );
        } else {
            layer.msg('请选择部门后再进行删除操作！', {icon: 2, time: 2000});
        }
    });
    /*----- 延迟开发功能 -----*/
    $(".page_loading").click(function(){
        alert('功能开发中……');
    });
</script>
<div class="footer">@yield('footer')</div>
</body>
</html>