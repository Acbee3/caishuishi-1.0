@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/moneyHeader.css?v=2018081607">
    <link rel="stylesheet" type="text/css" href="/css/book/C-admin.css?v=2018082301"/>
    <style type="text/css">
        .icon {
            width: 24px; height: 24px;
            vertical-align: middle;
            fill: currentColor;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
    @include('book.layout.header')
    @include('book.layout.sidebar')
    <section class="Hui-article-box">
        <div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
            <div class="Hui-tabNav-wp">
                <ul id="min_title_list" class="acrossTab cl">
                    <li class="active">
                        <span title="我的桌面" data-href="">我的桌面</span>
                        <em></em>
                    </li>
                </ul>
            </div>
            <div class="Hui-tabNav-more btn-group">
                <a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;">
                    <i class="Hui-iconfont">&#xe6d4;</i>
                </a>
                <a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;">
                    <i class="Hui-iconfont">&#xe6d7;</i>
                </a>
            </div>
        </div>

        <div id="iframe_box" class="Hui-article">
            <div class="show_iframe">
                <div style="display:none" class="loading"></div>
                @yield('table')
            </div>
        </div>
    </section>
@endsection

@section('script')
    @parent
    <script src="/js/book/H-ui.admin.js"></script>
    <script src="/js/book/nav.js"></script>
    <script src="/common/fonts/iconfont.js"></script>
    <script type="text/javascript">
        (function () {
            var openTime = new Date().getTime();
            var openTimes = localStorage.getItem('openTime');
            if (openTimes) {
                openTimes = JSON.parse(openTimes);
            }
            else {
                openTimes = [];
            }
            openTimes.push(openTime);
            localStorage.setItem('openTime', JSON.stringify(openTimes));

            function check() {
                localStorage.setItem('liveTime', new Date().getTime());
                var times = JSON.parse(localStorage.getItem('openTime'));
                if (openTime != times[times.length - 1]) {
//                    alert('不能打开多个控制台');
                    times = [times[times.length - 1]];
                    localStorage.setItem('openTime', JSON.stringify(times));
                    location.href = '/errors/404';
                    return;
                }
                setTimeout(function () {
                    check();
                }, 300);
            }

            try {
                check();
            } catch (e) {
                alert(e);
            }
        })()
    </script>
    <script>
        new Vue({
            'el': '#head',
            data: {
                calend: false,
                nowIndex: '',
                currentYear: '',
                minYear: '',
                maxYear: '',
                currentMonth: '',
                yjzIcon: false,
                // wjzIcon: !yjzIcon,
                months: [],
                yjz: [],
                contentData: '',
                data: '',
                cannotClick: []
            },
            created: function () {
                this.getKjqj()
            },
            mounted: function () {
                this.clickBlank()
            },
            methods: {

                // 获取会计区间
                getKjqj: function () {
                    var _this = this;
                    _this.$http.get('/book/home/periodList', {
                        params: {company_id: '{{ \App\Entity\Company::sessionCompany()->id }}'}
                    }).then(function (response) {
                        var year = [];
                        if (response.body.status == 1) {
                            // console.log(response.body.data)
                            for (var key in response.body.data) {
                                year.push(key)
                            }

                            // 规定年的选择区间
                            _this.minYear = year[0];
                            _this.maxYear = year[year.length - 1];

                            _this.data = response.body.data;

                            // 获得当前的时期
                            {{--console.log('{{ \App\Entity\Period::currentPeriod() }}')--}}
                            var fiscalPeriod = '{{ \App\Entity\Period::currentPeriod() }}';
                            var fiscalPeriod_Year = fiscalPeriod.split('-')[0];
                            var fiscalPeriod_Month = fiscalPeriod.split('-')[1];
                            // console.log(fiscalPeriod_Month.indexOf(0))
                            if (fiscalPeriod_Month.indexOf(0) == '0') {
                                fiscalPeriod_Month = fiscalPeriod_Month.slice(1);
                            }
                            _this.currentYear = fiscalPeriod_Year;
                            _this.currentMonth = fiscalPeriod_Month;
                            if (response.body.data[_this.currentYear][_this.currentMonth]['close_status'] == 1) {
                                _this.yjzIcon = true;
                            }
                            // console.log(response.body.data[_this.currentYear])
                            var months_box = [];
                            for (var key in response.body.data[_this.currentYear]) {
                                months_box.push(key)
                            }
                            _this.contentData = response.body.data[_this.currentYear];
                            _this.months = months_box;
                            _this.nowIndex = _this.currentMonth;

                            // 获得已结账的期
                            for (var key in response.body.data[_this.currentYear]) {
                                for (var key1 in response.body.data[_this.currentYear][key]) {
                                    if (response.body.data[_this.currentYear][key]['close_status'] == 1) {
                                        if (_this.yjz.indexOf(key) == -1) {
                                            _this.yjz.push(key)
                                            _this.nowIndex = _this.currentMonth;
                                        }
                                    }
                                    if (response.body.data[_this.currentYear][key]['cannot_click'] == 1) {
                                        if (_this.cannotClick.indexOf(key) == -1) {
                                            _this.cannotClick.push(key)
                                        }

                                    }
                                }
                            }
                            // console.log(_this.cannotClick)
                            // console.log(_this.yjz)
                            // console.log(_this.nowIndex)
                        }

                    })
                },

                // 点击切换年后数据再渲染
                renderMonth: function () {
                    var _this = this;
                    _this.$http.get('/book/home/periodList', {
                        params: {company_id: '{{ \App\Entity\Company::sessionCompany()->id }}'}
                    }).then(function (response) {
                        var months_box = [];
                        for (var key in response.body.data[_this.currentYear]) {
                            months_box.push(key)
                            // console.log(months_box)
                        }
                        _this.nowIndex = '';
                        _this.months = months_box;

                        _this.yjz = [];
                        _this.cannotClick = [];
                        for (var key in response.body.data[_this.currentYear]) {
                            for (var key1 in response.body.data[_this.currentYear][key]) {
                                if (response.body.data[_this.currentYear][key]['close_status'] == 1) {
                                    if (_this.yjz.indexOf(key) == -1) {
                                        _this.yjz.push(key)
                                        _this.nowIndex = _this.currentMonth;
                                    }
                                }
                                if (response.body.data[_this.currentYear][key]['cannot_click'] == 1) {
                                    if (_this.cannotClick.indexOf(key) == -1) {
                                        _this.cannotClick.push(key)
                                    }

                                }
                            }
                        }
                    })
                },
                //点击空白处相应div隐藏
                clickBlank: function () {
                    /*---日历--*/
                    var cander = this.$refs.cander;
                    var _this = this;
                    document.addEventListener('click', function (e) {
                        if (!cander.contains(e.target)) {
                            _this.calend = false;
                        }
                    })
                },
                calendShow: function (e) {

                    this.calend = !this.calend;
                },
                /*------点击日历<*/
                prePick: function () {
                    //此处请求后台渲染数据
                    if (this.currentYear > this.minYear) {
                        this.currentYear--;
                        this.renderMonth()
                    }

                },
                nextPick: function () {
                    //此处请求后台渲染数据
                    if (this.currentYear < this.maxYear) {
                        this.currentYear++;
                        this.renderMonth()
                    }
                },
                /*--------点击当前日期-----*/
                getDateWrapper: function (index, time) {
                    var _this = this;
                    // console.log(_this.contentData)
                    var curDate = this.$refs.curDate[index];
                    var cur = curDate[index];
                    if ($(curDate).find("span").hasClass('jz')) {
                        _this.yjzIcon = true;
                    } else {
                        _this.yjzIcon = false;
                    }

                    var url = _this.data[_this.currentYear][time].url;
                    // console.log(_this.data[_this.currentYear][time])
                    // console.log(time)
                    var clickorNot = _this.data[_this.currentYear][time]['cannot_click'];
                    // 上线改域名
                    //window.location.href = 'http://css.web.com' + url;
                    var domain = window.location.host;

                    if (clickorNot == 0) {
                        this.calend = false;
                        this.nowIndex = time;
                        this.num = this.currentYear;
                        this.currentMonth = time;
                        window.location.href = 'http://'+domain+'' + url;
                    }
                },
            }
        })
    </script>
@endsection