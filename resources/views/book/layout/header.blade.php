<header class="main-header head mainNav" id="head" v-cloak>
    <div class="headerTop">
        <div class="headerLeft">
            <img src="/images/book/moneyLogo.png" alt="logo">
        </div>
        <div class="headerCenter">
            <div class="company">
                <span>{{ $data->company_name }}</span>
                {{--<i class="iconfont">&#xe62b;</i>--}}
            </div>
            <div class="cssDate" ref="cander">
                <div class="curDate" @click="calendShow($event)">
                    <span>@{{ currentYear }}年第@{{ currentMonth }}期</span>
                    <i class="iconfont downTip">&#xe620;</i>
                </div>
                <div class="dateCalend" v-show="calend" style="display:none;" id="myPanel">
                    <ul class="dateHead">
                        <li class="left">
                            <i class="iconfont" @click="prePick">&#xe624;</i>
                        </li>
                        <li class="center">@{{currentYear}}</li>
                        <li class="right">
                            <i class="iconfont" @click="nextPick">&#xe623;</i>
                        </li>
                    </ul>
                    <div>
                        <ul class="dateBody">
                            {{--<li class="jz" title="结账">1</li>
                            <li class="active">2</li>--}}
                            <li v-for="(time,index) in months" @click="getDateWrapper(index,time)" :class="{'cantClick':cannotClick.indexOf(time) != -1, 'active':time == nowIndex}" ref="curDate">
                                <span>@{{time}}</span>
                                <div>
                                    <span v-if="time == yjz[index]" class="jz" title="结账" v-for="(jz,index) in yjz">@{{time}}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="yjz" v-show="yjzIcon">已结账</div>
            <div class="wjz" v-show="!yjzIcon">未结账</div>
        </div>
        <div class="headerRight">
            <div class="header-item moneyBg">
                <i class="icon iconfont">&#xe60d;</i>
                <span class="clientMsg">
                    <a href="{{ url('/agent/companies') }}">客户信息</a>
                </span>
            </div>
            <div class="header-item" style="display: none;">
                <i class="icon iconfont icon-tixing2"></i>
            </div>
            <div class="header-item" style="display: none;">
                <i class="iconfont">&#xe60d;</i>
                <span><a href="javascript:void(0)">返回首页</a></span>
            </div>
            <div class="header-item logOut">
                <a href="{{ url('/agent/login/logout') }}">
                    <i class="icon iconfont icon-tuichu2"></i>
                    <span>退出</span>
                </a>
            </div>
            <div class="header-item fullScreen">
                <div id="enter_qp"  onclick="fullScreen()">
                    <i class="icon iconfont icon-quanping"></i>
                    <span>全屏</span>
                </div>
                <div id="exit_qp" onclick="exitScreen()" style="display: none;">
                    <i class="icon iconfont icon-tuichuquanping"></i>
                    <span>退出全屏</span>
                </div>

            </div>
        </div>
    </div>
    <script>
        //全屏
        let ell = document.documentElement;

        function fullScreen() {
            let rfs = ell.requestFullScreen || ell.webkitRequestFullScreen || ell.mozRequestFullScreen || ell.msRequestFullscreen;
            if (typeof rfs != "undefined" && rfs) {
                rfs.call(ell);
                $("#enter_qp").hide();
                $("#exit_qp").show();
            }
            ;
            return false;
        }

        //退出全屏
        function exitScreen() {
            $("#exit_qp").hide();
            $("#enter_qp").show();
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
            else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            }
            else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            }
            else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
            if (typeof cfs != "undefined" && cfs) {
                cfs.call(ell);
            }
        }

    </script>
</header>
