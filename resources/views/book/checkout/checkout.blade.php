<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>结账</title>
    <!--公用-->
    <link rel="stylesheet" href="/common/css/reset.css">
    <link rel="stylesheet" href="/common/fonts/iconfont.css">
    <link rel="stylesheet" href="/common/layui/css/layui.css">
    <link rel="stylesheet" href="/common/css/table.css">
    <!--结账-->
    <link rel="stylesheet" href="/css/book/checkout/checkout.css">
    <!--[if lt IE 9]>
    <script src="../../../public/common/js/html5shiv.min.js"></script>
    <script src="../../../public/common/js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="checkout" v-cloak>
    <!--动画前-->
    <!--左边的盒子-->
    <div class="box1 clearfix">
        <div class="qimochuli" style="height: 780px;">
            <p>
                <span class="titl1">01</span>
                <span class="titlcontent ">期末处理</span>
            </p>
            <div class="clearfix clBox">
                <table>
                    <tr>
                        <td style="width:120px">
                            <!--背景图片-->
                            <i class="icon ico1"></i>
                        </td>
                        <td>
                            <p class="tl">
                                <span class="text1">清单凭证</span>
                            </p>
                            <p class="text2 tl">清单信息完整方可批量生成凭证</p>
                            <!--<p class="btn1 tl">-->
                            <!--<span class="m-btns m-btns-nom m-btns-bor btns-radio" id="xgsz">习惯设置</span>-->
                            <!--</p>-->
                        </td>
                        <td style="width:110px">
                            <span class="m-btns m-btns-nom m-btns-blue" id="batchScpz" @click="showQdpzScpz">批量生成凭证</span>
                            <span class="m-btns m-btns-nom m-btns-bor lookPz" @click="pingzheng" data-href="{{ route('voucher.index') }}" data-title="凭证" ref="ckPz">查看凭证</span>
                            <span class="m-btns m-btns-nom m-btns-null fs14">删除凭证</span>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="clearfix clBox">
                <table>
                    <tr>
                        <td style="width:120px">
                            <!--背景图片-->
                            <i class="icon ico2"></i>
                        </td>
                        <td>
                            <p class="tl">
                                <span class="text1">税金计提</span>
                            </p>
                            <p class="text2 tl">未生成税表将影响税金凭证计提</p>
                            <p class="btn1 tl">
                                <span class="m-btns m-btns-nom m-btns-bor btns-radio btns-select" id="sjxz"
                                      @click="showSjxz">税金选择</span>
                            </p>
                        </td>
                        <td style="width:110px">
                            <span class="m-btns m-btns-nom m-btns-blue" id="scjtpz" @click="showSjjtScpz">批量生成凭证</span>
                            {{--<span class="m-btns m-btns-nom m-btns-bor lookPz" @click="showSjjtPz">查看凭证</span>--}}
                            <span class="m-btns m-btns-nom m-btns-bor lookPz" @click="pingzheng" data-href="{{ route('voucher.index') }}" data-title="凭证" ref="ckPz">查看凭证</span>
                            <span class="m-btns m-btns-nom m-btns-red" @click="deletePz(10)">删除凭证</span>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="clearfix clBox">
                <table>
                    <tr>
                        <td style="width:120px">
                            <!--背景图片-->
                            <i class="icon ico3"></i>
                        </td>
                        <td>
                            <p class="tl">
                                <span class="text1">损益结转</span>
                            </p>
                            <p class="text2 tl">优先进行损益结转，避免结账出现问题</p>
                            <p class="btn1 tl">
                                {{--<span class="m-btns m-btns-nom m-btns-bor btns-radio" id="jzsz"--}}
                                {{--@click="showJzsz">结转设置</span>--}}
                                <span class="m-btns m-btns-nom m-btns-null btns-radio" id="jzsz">结转设置</span>

                            </p>
                        </td>
                        <td style="width:110px">
                            <span class="m-btns m-btns-nom m-btns-blue" id="scjzpz" @click="showSyjzScpz">批量生成凭证</span>
                            {{--<span class="m-btns m-btns-nom m-btns-bor lookPz" @click="pingzheng">查看凭证</span>--}}
                            <span class="m-btns m-btns-nom m-btns-bor lookPz" @click="pingzheng" data-href="{{ route('voucher.index') }}" data-title="凭证" ref="ckPz">查看凭证</span>
                            <span class="m-btns m-btns-nom m-btns-red" @click="deletePz(16)">删除凭证</span>
                        </td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
    <!--右边的盒子-->
    <div class="box2 clearfix">
        <div class="jzyujc" style="height: 780px;">
            <p class="pppp">
                <span class="titl1">02</span>
                <span class="titlcontent">结账与检查</span>
                <span class="colye">*结账前请先进行期末处理</span>
            </p>
            <div class="quanWap">
                <!--大文字的背景-->
                <div class="div div2"></div>
                <!--要旋转的-->
                <div class="div div1"></div>
                <div class="div colfff clearfix jcBtn" style="font-size:40px">
                    <p class="wenzi">风险<br>检测</p>
                </div>
            </div>
            <p class="righttext1">避免财税风险，建议立即检查！</p>
            <p class="righttext2">良好的财税健康度可助企业茁壮成长，小云建议您在结账前先进行风险检查</p>
            <p class="righttext3">
                <span class="m-btns m-btns-big m-btns-red2" id="ljjc" @click="startCheck">立即检查</span>
                <span class="m-btns m-btns-big m-btns-blue qrjz"
                      style="width:120px; margin-left: 15px;" @click="showZjjz" v-if="checkedOut == 0">直接结账</span>
                <span class="m-btns m-btns-big m-btns-blue fjz"
                      style="width:120px; margin-left: 15px;" @click="fanJz" v-if="checkedOut == 1">反结账</span>

                <span class="m-btns m-btns-big m-btns-blue jzzxq" id="jzzxq" @click="goNext()">跳转至下期</span>
            </p>

        </div>
    </div>

    <!--动画后-->
    <!--左边的盒子-->
    <div class="box3 clearfix">
        <div class="jkzs" style="height: 780px;">
            <p class="box3T">
                <span class="titl1">03</span>
                <span class="titlcontent ">检查完成</span>
            </p>
            <p class="jkzsTit">健康指数</p>
            <div class="quanWap">
                <!--大文字的背景-->
                <div class="div div2"></div>
                <!--要旋转的-->
                <div class="div div1"></div>
                <div class="div colfff clearfix jcBtn" style="font-size:40px">
                    {{--<p class="wenzi" v-text="progress"></p>--}}
                    <p class="wenzi">检测<br>完毕</p>
                </div>
            </div>
            <p class="resText">
                还有
                <span id="checkResNum" style="color:rgb(255, 102, 0)">@{{ errorNum }}</span>
                项存在异常哦
            </p>
            <p class="checkTime">
                最后检查时间：
                <span id="checkResTime">@{{ checkTime }}</span>
            </p>
            <p class="box3btns">
                <span class="m-btns m-btns-big m-btns-red2" id="cxjc" @click="recheck">重新检查</span>
                <span class="m-btns m-btns-big m-btns-blue zjjz m-btn-checkout" @click="showZjjz" v-if="checkedOut == 0">直接结账</span>
                <span class="m-btns m-btns-big m-btns-blue fjz"
                      @click="fanJz" v-if="checkedOut == 1">反结账</span>

                <a href="javascript:;" class="goBack" @click="location.reload()"> <i class="iconfont icon-fanhui fontwrap"></i>返回</a>
            </p>
        </div>
    </div>
    <!--右边的盒子-->
    <div class="box4 clearfix" v-if="checkResults.length">
        <div class="default">
            <div class="qdList" style="height: 778px;">
                <div class="title" @click="toggleBar(1)" data-index="1">
                    <span class="itemTitle">
                        @{{ checkResults[0].name }}
                    </span>
                    <span class="abnormal">
                        <span class="abnormalItem">异常项：</span>
                        <span class="abnormalItemNum">@{{checkResults[0].num}}</span>
                        项
                        <i class="iconfont one icon-shouqi"></i>
                    </span>
                </div>
                <div class="cont" v-show="flag1" data-flag="1">
                    <ul>
                        <li class="clearfix" v-for="(item, index) in checkResults[0].list">
                            <a :class="['fr', 'errorMessage', 'wrong', item.status == 1 ? '' : 'dpn']" :href="item.url">@{{ item.msg }}</a>
                            <span :class="['fr', 'errorMessage', item.status == 0 ? '' : 'dpn']">@{{ item.msg }}</span>
                            <i :class="['iconfont', item.status == 1 ? 'icon-tixing' : 'icon-wancheng']"></i>
                            <span class="errorName">@{{ item.name }}</span>
                        </li>
                    </ul>
                </div>
                <div class="title" @click="toggleBar(2)" data-index="2">
                    <span class="itemTitle">
                        @{{ checkResults[1].name }}
                    </span>
                    <span class="abnormal">
                        <span class="abnormalItem">异常项：</span>
                        <span class="abnormalItemNum">@{{checkResults[1].num}}</span>
                        项
                        <i class="iconfont two icon-shouqi"></i>
                    </span>
                </div>
                <div class="cont" v-show="flag2" data-flag="2">
                    <ul>
                        <li class="clearfix" v-for="(item, index) in checkResults[1].list">
                            <a :class="['fr', 'errorMessage', 'wrong', item.status == 1 ? '' : 'dpn']" :href="item.url">@{{ item.msg }}</a>
                            <span :class="['fr', 'errorMessage', item.status == 0 ? '' : 'dpn']">@{{ item.msg }}</span>
                            <i :class="['iconfont', item.status == 1 ? 'icon-tixing' : 'icon-wancheng']"></i>
                            <span class="errorName">@{{ item.name }}</span>
                        </li>
                    </ul>
                </div>
                <div class="title" @click="toggleBar(3)" data-index="3">
                    <span class="itemTitle">
                        @{{ checkResults[2].name }}
                    </span>
                    <span class="abnormal">
                        <span class="abnormalItem">异常项：</span>
                        <span class="abnormalItemNum">@{{checkResults[2].num}}</span>
                        项
                        <i class="iconfont three icon-shouqi"></i>
                    </span>
                </div>
                <div class="cont" v-show="flag3" data-flag="3">
                    <ul>
                        <li class="clearfix" v-for="(item, index) in checkResults[2].list">
                            <a :class="['fr', 'errorMessage', 'wrong', item.status == 1 ? '' : 'dpn']" :href="item.url">@{{ item.msg }}</a>
                            <span :class="['fr', 'errorMessage', item.status == 0 ? '' : 'dpn']">@{{ item.msg }}</span>
                            <i :class="['iconfont', item.status == 1 ? 'icon-tixing' : 'icon-wancheng']"></i>
                            <span class="errorName">@{{ item.name }}</span>
                        </li>
                    </ul>
                </div>
                <div class="title" @click="toggleBar(4)" data-index="4">
                    <span class="itemTitle">
                        @{{ checkResults[3].name }}
                    </span>
                    <span class="abnormal">
                        <span class="abnormalItem">异常项：</span>
                        <span class="abnormalItemNum">@{{checkResults[3].num}}</span>
                        项
                        <i class="iconfont four icon-shouqi"></i>
                    </span>
                </div>
                <div class="cont" v-show="flag4" data-flag="4">
                    <ul>
                        <li class="clearfix" v-for="(item, index) in checkResults[3].list">
                            <a :class="['fr', 'errorMessage', 'wrong', item.status == 1 ? '' : 'dpn']" :href="item.url">@{{ item.msg }}</a>
                            <span :class="['fr', 'errorMessage', item.status == 0 ? '' : 'dpn']">@{{ item.msg }}</span>
                            <i :class="['iconfont', item.status == 1 ? 'icon-tixing' : 'icon-wancheng']"></i>
                            <span class="errorName">@{{ item.name }}</span>
                        </li>
                    </ul>
                </div>
                <div class="title" @click="toggleBar(5)" data-index="5">
                    <span class="itemTitle">
                        @{{ checkResults[4].name }}
                    </span>
                    <span class="abnormal">
                        <span class="abnormalItem">异常项：</span>
                        <span class="abnormalItemNum">@{{checkResults[4].num}}</span>
                        项
                        <i class="iconfont five icon-shouqi"></i>
                    </span>
                </div>
                <div class="cont" v-show="flag5" data-flag="5">
                    <ul>
                        <li class="clearfix" v-for="(item, index) in checkResults[4].list">
                            <a :class="['fr', 'errorMessage', 'wrong', item.status == 1 ? '' : 'dpn']" :href="item.url">@{{ item.msg }}</a>
                            <span :class="['fr', 'errorMessage', item.status == 0 ? '' : 'dpn']">@{{ item.msg }}</span>
                            <i :class="['iconfont', item.status == 1 ? 'icon-tixing' : 'icon-wancheng']"></i>
                            <span class="errorName">@{{ item.name }}</span>
                        </li>
                    </ul>
                </div>
            </div>
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
    var vm = new Vue({
        'el': '.checkout',
        data: {
            i1: 0,
            i2: 0,
            i3: 0,
            i4: 0,
            i5: 0,
            flag1: true,
            flag2: true,
            flag3: true,
            flag4: true,
            flag5: true,
            // 检查进度
            progress: '0%',
            // 错误数
            errorNum: 0,
            // 检查时间
            checkTime: '',
            // 检查结果
            checkResults: [],
            // 快捷键开关
            kjjSwitch: true,
            // 是否结账 未结账状态为 0
            checkedOut: 0
        },
        created: function () {
            var _this = this;
            // 检查本期是否结账
            _this.$http.post('/book/accountClose/checkClose', {
                company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                _token: '{{csrf_token()}}'
            }).then(function (response) {
                if (response.body.status == 1) {
                    // console.log(response.body.data.close_status)
                    _this.checkedOut = response.body.data.close_status;
                }
            });
            // 直接结账与反结账的快捷键
            document.onkeydown = function (e) {
                var key = window.event.keyCode;
                // console.log(e)
                if (key == 90 && e.ctrlKey == true && _this.kjjSwitch == true && _this.checkedOut == 0) {
                    _this.showZjjz();
                    _this.kjjSwitch = false;
                } else if (key == 88 && e.ctrlKey == true && _this.kjjSwitch == true && _this.checkedOut == 1) {
                    _this.fanJz();
                    _this.kjjSwitch = false;
                }
            };
            layui.use(['form', 'jquery', 'layer'], function () {
                var form = layui.form;
                var layer = layui.layer;
            });

        },
        mounted: function () {

        },
        methods: {
            // 检查
            startCheck: function () {
                var _this = this;
                // 1.点击立即检查
                // 2.div1 背景图开始旋转 rotate，div 水平 translateX
                // 3.同时 box2 向左平移，box1 随 box2 的平移宽度变窄，最终 宽度变为0后，设置属性display: none
                // 4.box2 移到最左后，内容文字改变
                // 5.随着加载进度，健康指数发生变化
                // 6.加载完毕后，数字定下来后，所有的动画执行完毕

                //this.checkResult();
                var _this = this;
                _this.$http.post('{{ url('book/accountClose/check') }}', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{ csrf_token() }}'
                }).then(function (response) {
                    if (response.status == 200) {
                        console.log(response.body.data);
                        _this.checkResults = response.body.data;
                        _this.getErrorNum();
                        // console.log(_this.checkResults[0])
                    }
                });
                // 旋转
                $('.div1').addClass('active');
                // 背景平移
                $('.quanWap').addClass('active');
                // 记录检查时间
                _this.checkTimer();

                // box2 宽度变化，同时向左平移，box1 随着 box2 的平移宽度变窄
                setTimeout(function () {
                    // box2 宽度变窄
                    $('.box2').animate({
                        width: '30%'
                    }, 600, 'linear');
                    // box1 宽度变窄
                    $('.box1').animate({
                        width: '0'
                    }, 600, 'linear', function () {
                        $('.box1').css({
                            display: 'none'
                        })
                    });
                    // box2 向左平移
                    $('.box2').css({
                        marginLeft: '20px'
                    });
                    $('.box2').animate({
                        marginRight: '30%',
                    }, 600, 'linear', function () {
                        $('.box2').css({
                            display: 'none'
                        });
                        $('.box3').css({
                            display: 'block'
                        });
                        _this.progress = '68%';
                        setTimeout(function () {
                            $('.div1').removeClass('active');
                            $('.quanWap').removeClass('active');
                            $('.box4').css({
                                display: 'block'
                            })
                        }, 1000)
                    })
                }, 1000);

            },
            // 重新选择
            recheck: function () {
                // 旋转
                $('.div1').addClass('active');
                // 背景平移
                $('.quanWap').addClass('active');
                setTimeout(function () {
                    $('.div1').removeClass('active');
                    $('.quanWap').removeClass('active');
                    $('.box4').css({
                        display: 'block'
                    })
                }, 1000);
                this.checkTimer()
            },
            // 弹框税金选择
            showSjxz: function () {
                var _this = this;
                _this.$http.get('{{ url('book/taxConfig/list') }}', {params: {company_id: '{{ \App\Entity\Company::sessionCompany()->id }}'}}).then(function (response) {
                    if (response.status == 200) {
                        vm_component_group.sjData = response.data;
                        // console.log(_this.sjData);
                    }
                });
                layer.open({
                    type: 1,
                    title: '选择税金计提',
                    skin: 'components',
                    // shadeClose: true,
                    maxmin: false, //开启最大化最小化按钮
                    zIndex: 1000,
                    area: ['600px', '450px'],
                    content: $('#selectSjjt'),
                    btn: ['关闭'],
                    yes: function (index, layero) {
                        layer.close(index)
                    }
                })
            },
            // 弹框转结设置
            showJzsz: function () {
                var _this = this;
                layer.open({
                    type: 1,
                    title: '结转设置',
                    skin: 'components',
                    // shadeClose: true,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['330px', '470px'],
                    content: $('#jiezhuangSz'),
                    btn: ['确定', '取消'],
                    yes: function (index, layero) {
                        layer.close(index)
                    }
                })
            },
            // 弹框税金计提凭证
            showSjjtPz: function () {
                var _this = this;
                layer.open({
                    type: 1,
                    title: '查看税金计提凭证',
                    skin: 'components',
                    // shadeClose: true,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['600px', '565px'],
                    content: $('#sjjtPz'),
                    btn: []
                })
            },
            // 弹框直接结账
            showZjjz: function () {
                var _this = this;
                _this.$http.post('/book/accountClose/run', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{csrf_token()}}'
                }).then(function (response) {
                    // console.log(response.body);
                    vm_zhijieJz.result = response.body;
                    if (vm_zhijieJz.result.status == 1) {
                        this.checkedOut = 1;
                        layer.open({
                            type: 1,
                            title: '结账情况校验结果',
                            skin: 'components',
                            shadeClose: false,
                            // shade: false,
                            maxmin: false, //开启最大化最小化按钮
                            area: ['250px', '165px'],
                            content: $('#zhijieJz'),
                            btn: ['确认'],
                            cancel: function () {
                                _this.kjjSwitch = true;
                                top.location.reload()
                            },
                            yes: function (index, layero) {
                                // 打开快捷键锁
                                _this.kjjSwitch = true;
                                top.location.reload();
                                layer.close(index)
                            }
                        })
                    } else if (vm_zhijieJz.result.status == 0) {
                        vm_jiezhangShibai.result = response.body;
                        // 结账失败
                        layer.open({
                            type: 1,
                            title: '结账情况校验结果',
                            skin: 'components',
                            shadeClose: false,
                            // shade: false,
                            maxmin: false, //开启最大化最小化按钮
                            area: ['315px', '200px'],
                            content: $('#jiezhangShibai'),
                            btn: ['确认'],
                            cancel: function () {
                                _this.kjjSwitch = true;
                                // top.location.reload()
                            },
                            yes: function (index, layero) {
                                // 打开快捷键锁
                                _this.kjjSwitch = true;
                                // top.location.reload()
                                layer.close(index)
                            }
                        })
                    }
                })

            },
            // 弹框清单凭证_批量生成凭证
            showQdpzScpz: function () {
                var _this = this;
                _this.$http.post('/book/accountClose/makeVoucherByQingdan', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{csrf_token()}}'
                }).then(function (response) {
                    if (response.body.status == 1) {
                        vm_qdpz_piliangPz.scpzData = response.body.data;
                    }
                    // console.log(response.body.data)
                });
                layer.open({
                    type: 1,
                    title: '批量生成凭证结果',
                    skin: 'components',
                    shadeClose: true,
                    // shade: false,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['600px', '554px'],
                    content: $('#qdpz_piliangPz'),
                    btn: ['确认'],
                    yes: function (index, layero) {

                        layer.close(index)
                    }
                })
            },
            // 弹框税金计提_批量生成凭证
            showSjjtScpz: function () {
                var _this = this;
                _this.$http.post('/book/accountClose/makeVoucherByTax', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{csrf_token()}}',
                }).then(function (response) {
                    if (response.body.status == 1) {
                        vm_sjjt_piliangPz.scpzData = response.body.data;
                    }
                    // console.log(response)
                });
                layer.open({
                    type: 1,
                    title: '生成计提凭证结果',
                    skin: 'components',
                    shadeClose: true,
                    // shade: false,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['600px', '554px'],
                    content: $('#sjjt_piliangPz'),
                    btn: ['确认'],
                    yes: function (index, layero) {

                        layer.close(index)
                    }
                })
            },
            // 弹框损益结转_批量生成凭证
            showSyjzScpz: function () {
                var _this = this;
                _this.$http.post('/book/accountClose/makeVoucherBySunyi', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{csrf_token()}}',
                }).then(function (response) {
                    vm_syjz_piliangPz.msg = response.body.info;
                    // if (response.body.status == 1) {
                    //     vm_syjz_piliangPz.msg = response.body.info;
                    // }
                    // console.log(response)
                });
                layer.open({
                    type: 1,
                    title: '信息',
                    skin: 'components',
                    shadeClose: true,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['260px', '150px'],
                    content: $('#syjz_piliangPz'),
                    btn: ['确认'],
                    yes: function (index, layero) {

                        layer.close(index)
                    }
                })
            },
            // 跳转至凭证
            pingzheng: function () {
                var ckPz = this.$refs.ckPz;
                top.Hui_admin_tab(ckPz)
            },
            // 展开收起
            toggleBar: function (num) {
                var _this = this;
                // console.log(num);
                if (num == 1) {
                    if (_this.i1 % 2 == 0) {
                        $('.one').removeClass('icon-shouqi').addClass('icon-zhankai');
                        _this.flag1 = false;
                        _this.i1++;
                    } else {
                        $('.one').removeClass('icon-zhankai').addClass('icon-shouqi');
                        _this.flag1 = true;
                        _this.i1++;
                    }
                } else if (num == 2) {
                    if (_this.i2 % 2 == 0) {
                        $('.two').removeClass('icon-shouqi').addClass('icon-zhankai');
                        _this.flag2 = false;
                        _this.i2++;
                    } else {
                        $('.two').removeClass('icon-zhankai').addClass('icon-shouqi');
                        _this.flag2 = true;
                        _this.i2++;
                    }
                } else if (num == 3) {
                    if (_this.i3 % 2 == 0) {
                        $('.three').removeClass('icon-shouqi').addClass('icon-zhankai');
                        _this.flag3 = false;
                        _this.i3++;
                    } else {
                        $('.three').removeClass('icon-zhankai').addClass('icon-shouqi');
                        _this.flag3 = true;
                        _this.i3++;
                    }
                } else if (num == 4) {
                    if (_this.i4 % 2 == 0) {
                        $('.four').removeClass('icon-shouqi').addClass('icon-zhankai');
                        _this.flag4 = false;
                        _this.i4++;
                    } else {
                        $('.four').removeClass('icon-zhankai').addClass('icon-shouqi');
                        _this.flag4 = true;
                        _this.i4++;
                    }
                } else if (num == 5) {
                    if (_this.i5 % 2 == 0) {
                        $('.five').removeClass('icon-shouqi').addClass('icon-zhankai');
                        _this.flag5 = false;
                        _this.i5++;
                    } else {
                        $('.five').removeClass('icon-zhankai').addClass('icon-shouqi');
                        _this.flag5 = true;
                        _this.i5++;
                    }
                }
            },
            // 删除凭证
            deletePz: function (param) {
                var _this = this;
                _this.$http.post('/book/accountClose/deleteJitiVoucher', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    source: param,
                    _token: '{{csrf_token()}}',
                }).then(function (response) {
                    console.log(response);
                    if (response.body.status == 1) {
                        layer.msg('删除成功', {time: 1000});
                    }
                })
            },
            // 得到检查时间
            checkTimer: function () {
                var _this = this;
                var date = new Date();
                var seperator1 = ".";
                var seperator2 = ":";
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var strDate = date.getDate();
                var hour = date.getHours();
                var minute = date.getMinutes();
                var second = date.getSeconds();
                if (month >= 1 && month <= 9) {
                    month = "0" + month;
                }
                if (strDate >= 0 && strDate <= 9) {
                    strDate = "0" + strDate;
                }
                if (hour >= 0 && hour <= 9) {
                    hour = "0" + hour;
                }
                if (minute >= 0 && minute <= 9) {
                    minute = "0" + minute;
                }
                if (second >= 0 && second <= 9) {
                    second = "0" + second;
                }
                _this.checkTime = year + seperator1 + month + seperator1 + strDate + ' ' + hour + seperator2 + minute + seperator2 + second;
            },
            // 获得检查结果
            checkResult: function () {
                var _this = this;
                _this.$http.post('{{ url('book/accountClose/check') }}', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{ csrf_token() }}'
                }).then(function (response) {
                    if (response.status == 200) {
                        _this.checkResults = response.body.data;
                        // console.log(_this.checkResults[0])
                    }
                });

            },
            // 问题数量
            getErrorNum: function () {
                var _this = this;
                _this.errorNum = _this.checkResults[0].num + _this.checkResults[1].num + _this.checkResults[2].num + _this.checkResults[3].num + _this.checkResults[4].num
            },
            // 反结账
            fanJz: function () {
                var _this = this;
                _this.$http.post('/book/accountClose/reverse', {
                    company_id: '{{ \App\Entity\Company::sessionCompany()->id }}',
                    fiscal_period: '{{ \App\Entity\Period::currentPeriod() }}',
                    _token: '{{csrf_token()}}'
                }).then(function (response) {
                    // console.log(response)
                    vm_fanJz.result = response.body;
                    if (vm_fanJz.result.status == 1) {
                        this.checkedOut = 0;
                    }

                });
                layer.open({
                    type: 1,
                    title: '结账情况校验结果',
                    skin: 'components',
                    shadeClose: false,
                    // shade: false,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['250px', '165px'],
                    content: $('#fanJz'),
                    btn: ['确认'],
                    cancel: function () {
                        _this.kjjSwitch = true;
                        top.location.reload()
                    },
                    yes: function (index, layero) {
                        // 打开快捷键锁
                        _this.kjjSwitch = true;
                        top.location.reload();
                        layer.close(index)
                    }
                })
            },
            // 跳转到下期
            goNext: function () {
                var _this = this;

                if (_this.checkedOut == 0) {
                    layer.msg('请先为本期结账', {icon: 2, time: 2000});
                } else if (_this.checkedOut == 1) {
                    _this.$http.get('/book/home/periodList', {
                        params: {company_id: '{{ \App\Entity\Company::sessionCompany()->id }}'}
                    }).then(function (response) {
                        var fiscalPeriod = '{{ \App\Entity\Period::currentPeriod() }}';
                        var fiscalPeriod_Year = fiscalPeriod.split('-')[0];
                        var fiscalPeriod_Month = fiscalPeriod.split('-')[1];
                        if (fiscalPeriod_Month.indexOf(0) == '0') {
                            fiscalPeriod_Month = fiscalPeriod_Month.slice(1);
                        }
                        // console.log(response.body.data)
                        // 如果月份为12 跳转到下年一期
                        if (fiscalPeriod_Month == 12) {
                            fiscalPeriod_Year++;
                            fiscalPeriod_Month = 1;
                        } else {
                            fiscalPeriod_Month++;
                        }
                        var url = response.body.data[fiscalPeriod_Year][fiscalPeriod_Month].url;
                        var domain = window.location.host;
                        top.location.href = 'http://' + domain + url;
                    })
                }
            }
        }
    })
</script>
</body>
<!--组件-->
<div class="component_group">
    <!--税金选择-->
    <div id="selectSjjt" class="components" style="display: none;">
        <div class="content">
            <table cellpadding="0" cellspacing="0">
                <colgroup>
                    <col style="width: 270px;" col="0">
                    <col style="width: 162px;" col="1">
                    <col style="width: 110px;" col="2">
                </colgroup>
                <tr>
                    <th>税金计提项目</th>
                    <th>启用状态</th>
                    <th>操作</th>
                </tr>
                <tr v-for="(item, index) in sjData.data" :key="index">
                    <td class="tl">@{{item.tax_name}}</td>
                    <td class="tc">
                        <p :class="['stateControl', item.status == 0 ? 'close' : '']" @click="openOrClose($event, item)">@{{ item.status == 0 ? '未开启' : '已开启' }}</p>
                        <!--<p class="stateControl close"></p>-->
                    </td>
                    <td class="tc">
                        <i class="iconfont icon-bianji" @click="showSzPzmb(item)"></i>
                    </td>
                </tr>

            </table>
        </div>
    </div>
    <!--设置凭证模板-->
    <div id="szPzmb" class="components" style="display: none;">
        <div class="content">
            <!--<div class="mbName">-->
            <!--<label for="mbName">模板名称</label>-->
            <!--<div class="textBox" style="width: 498px; height: 23px;">-->
            <!--<span class="textBoxBtn"></span>-->
            <!--<input type="text" readonly>-->
            <!--</div>-->
            <!--</div>-->
            <table cellpadding="0" cellspacing="0">
                <colgroup>
                    <col style="width: 220px;"></col>
                    <col style="width: 138px;"></col>
                    <col style="width: 192px;"></col>
                </colgroup>
                <tr>
                    <th class="tl">摘要</th>
                    <th class="tl">启用状态</th>
                    <th>会计科目</th>
                </tr>
                <tr>
                    <td class="tl">@{{ sjItemData }}</td>
                    <td class="tl">借方</td>
                    <td class="kjkmItem">
                        <div class="kjkm fl" @click="flag1 = !flag1" ref="option1">
                            <input type="text" readonly class="fl" :value="data1">
                            <span class="fl">
                                <i class="iconfont icon--xialajiantou"></i>
                            </span>
                            <ul class="items" v-show="flag1" @click="selectKjkmItem1($event)">
                                <li v-for="(item, index) in kjkmItem" :key="index">@{{item.number}} @{{item.name}}</li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="tl">@{{ sjItemData }}</td>
                    <td class="tl">贷方</td>
                    <td class="kjkmItem">
                        <div class="kjkm fl" @click="flag2 = !flag2" ref="option2">
                            <input type="text" readonly class="fl" :value="data2">
                            <span class="fl">
                                <i class="iconfont icon--xialajiantou"></i>
                            </span>
                            <ul class="items" v-show="flag2" @click="selectKjkmItem2($event)">
                                <li v-for="(item, index) in kjkmItem" :key="index">@{{item.number}} @{{item.name}}</li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>
            <div id="pzWarming" style="color:red; margin-top:10px;">
                @{{ tip1 }}
                <p style="margin-left:10px">@{{ tip2 }}</p>
                <p style="margin-left:26px">@{{ tip3 }}</p>
            </div>
        </div>
    </div>
</div>
<!--结转设置-->
<div id="jiezhuangSz" class="components" style="display: none;">
    <div class="content">
        <table>
            <colgroup>
                <col style="width: 90px;">
                <col style="width: 200px;">
            </colgroup>
            <tr class="tr-item">
                <td colspan="2" height="30" class="tl">期末汇率</td>
            </tr>
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">USD</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].USD">
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">EUR</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].EUR">
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">JPY</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].JPY">
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">HKD</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].HKD">
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">NZD</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].NZD">
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">ZAR</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].ZAR">
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">SEK</td>
                <td class="tl">
                    <input type="text" style="width: 160px;" class="bzhl" :value="exchange_rate[0].SEK">
                </td>
            </tr>
            <tr class="tr-item">
                <td colspan="2" height="30" class="tl">科目设置</td>
            </tr>
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">汇兑收益科目</td>
                <td>
                    <div class="huiduiKm fl">
                        <div class="show fl" @click="flag1 = !flag1" ref="option1">
                            <input type="text" readonly class="fl" :value="date1">
                            <span class="fl"></span>
                            <ul class="kmItem" v-show="flag1" @click="selectItem1($event)">
                                <li v-for="(item, index) in optionItems" :key="index">@{{ item.number }} @{{ item.name }}</li>
                            </ul>
                        </div>
                        <div class="date"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td height="30" style="padding-right: 10px;" class="tr">汇兑损失科目</td>
                <td>
                    <div class="huiduiKm fl">
                        <div class="show fl" @click="flag2 = !flag2" ref="option2">
                            <input type="text" readonly class="fl" :value="date2">
                            <span class="fl"></span>
                            <ul class="kmItem" v-show="flag2" @click="selectItem2($event)">
                                <li v-for="(item, index) in optionItems" :key="index">@{{ item.number }} @{{ item.name }}</li>
                            </ul>
                        </div>
                        <div class="date"></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<!--查看税金计提凭证-->
<div id="sjjtPz" class="components" style="display: none;">
    <div class="content">
        <table cellpadding="0" cellspacing="0">
            <colgroup>
                <col style="width: 350px">
                <col style="width: 200px">
            </colgroup>
            <tr>
                <th class="tl">税金计提项目</th>
                <th class="tl">凭证号</th>
            </tr>
            <tr v-for="(item, index) in formData" :key="index">
                <td class="tl">@{{item.label}}</td>
                <td class="tl">@{{item.value}}</td>
            </tr>
        </table>
    </div>
</div>
<!--直接结账-->
<div id="zhijieJz" class="components" style="display: none;">
    <div class="content" v-if="false">
        <table>
            <tr>
                <td>
                    <table class="pzTable">
                        <colgroup>
                            <col style="width: 68px">
                            <col style="width: 116px">
                            <col style="width: 360px">
                        </colgroup>
                        <tr>
                            <td colspan="3">
                                <p style="font-weight: bold;">2017年12月结账信息</p>
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="3">发票</td>
                            <td>进项发票</td>
                            <td>全部生成凭证</td>
                        </tr>
                        <tr>
                            <td>销项发票</td>
                            <td>
                                <p>销项未全部生成凭证</p>
                            </td>
                        </tr>
                        <tr>
                            <td>费用发票</td>
                            <td>全部生成凭证</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="content" style="padding-bottom: 0; padding-top: 30px;">
        <p>结账完毕</p>
    </div>
</div>
<!--结账失败-->
<div id="jiezhangShibai" class="components" style="display: none;">
    <div class="content" style="padding-bottom: 0; padding-top: 30px;">
        <p><span style="color: red;">结账失败：</span>@{{ result.info }}</p>
    </div>
</div>
<!--反结账-->
<div id="fanJz" class="components" style="display: none;">
    <div class="content" style="padding-bottom: 0; padding-top: 30px;">
        <p>@{{ result.status == 1 ? '反结账完毕' : '反结账不成功' }}</p>
    </div>
</div>
<!--清单凭证_批量生成凭证-->
<div id="qdpz_piliangPz" class="components" style="display: none;">
    <div class="content">
        <table>
            <colgroup>
                <col style="width: 222px;">
                <col style="width: 290px;">
            </colgroup>
            <tr v-for="(item, index) in scpzData" :key="index">
                <td>@{{ item.name }}</td>
                <td>@{{ item.msg }}</td>
            </tr>
        </table>
    </div>
</div>
<!--税金计提_批量生成凭证-->
<div id="sjjt_piliangPz" class="components" style="display: none;">
    <div class="content">
        <table>
            <colgroup>
                <col style="width: 222px;">
                <col style="width: 290px;">
            </colgroup>
            <tr v-for="(item, index) in scpzData" :key="index">
                <td>@{{ item.tax_name }}</td>
                <td>@{{ item.jiti_status == 1 ? '已计提' : '未计提' }}</td>
            </tr>
        </table>
    </div>
</div>
<!--损益结转_批量生成凭证-->
<div id="syjz_piliangPz" class="components" style="display: none;">
    <div class="content">
        <p>@{{ msg }}</p>
    </div>
</div>

<!--税金选择 和 设置凭证模板-->
<script>
    var vm_component_group = new Vue({
        'el': '.component_group',
        data: {
            // 税金选择
            sjData: '',
            // 编辑单个税金
            sjItemData: '',
            flag1: false,
            flag2: false,
            data1: '',
            data2: '',
            // 下拉框内容（会计科目项目）
            kjkmItem: '',
            tip1: '',
            tip2: '',
            tip3: ''
        },
        mounted: function () {
            this.clickBlank();
        },
        created: function () {
            layui.use(['form', 'jquery', 'layer'], function () {
                var form = layui.form;
                var layer = layui.layer;
            })
        },
        methods: {
            // 税金选择初始化
            render1: function () {

            },
            render2: function () {

            },
            // 未开启 已开启
            openOrClose: function (e, item) {
                // console.log(item.status)
                var _this = this;
                // console.log(this.sjData)
                var tar = e.target;
                var $tar = $(tar);
                if ($tar.hasClass('close')) {
                    var param2 = {};
                    item.status = 1;
                    param2.company_id = item.company_id;
                    param2.tax_id = item.tax_id;
                    param2.tax_name = item.tax_name;
                    param2.debit_number = item.debit_number;
                    param2.debit_name = item.debit_name;
                    param2.credit_number = item.credit_number;
                    param2.credit_name = item.credit_name;
                    param2.status = item.status;
                    param2.id = item.id;
                    param2._token = '{{csrf_token()}}';
                    $tar.removeClass('close');
                    $tar.text('已开启');
                    layer.msg('已开启');
                    _this.$http.post('{{ url('book/taxConfig/save') }}', param2).then(function (response) {
                        if (response.status == 200) {
                            this.render1()
                            // console.log(response);
                        }
                    });
                } else {
                    $tar.addClass('close');
                    var param2 = {};
                    item.status = 0;
                    param2.company_id = item.company_id;
                    param2.tax_id = item.tax_id;
                    param2.tax_name = item.tax_name;
                    param2.debit_number = item.debit_number;
                    param2.debit_name = item.debit_name;
                    param2.credit_number = item.credit_number;
                    param2.credit_name = item.credit_name;
                    param2.status = item.status;
                    param2.id = item.id;
                    param2._token = '{{csrf_token()}}';
                    $tar.text('未开启');
                    layer.msg('已关闭');
                    _this.$http.post('{{ url('book/taxConfig/save') }}', param2).then(function (response) {
                        if (response.status == 200) {
                            this.render1()
                            // console.log(response);
                        }
                    });
                }
            },
            // 弹框设置凭证模板
            showSzPzmb: function (param) {
                var _this = this;
                _this.$http.get('{{ route('account_subject.index') }}').then(function (response) {
                    if (response.status == 200) {
                        _this.kjkmItem = response.body;
                        // console.log(_this.kjkmItem)
                    }
                });
                // console.log(param)
                _this.sjItemData = param.tax_name;
                _this.data1 = param.debit_number + ' ' + param.debit_name;
                _this.data2 = param.credit_number + ' ' + param.credit_name;
                // console.log(param.example.split(' '));
                _this.tip1 = param.example.split(' ')[0];
                _this.tip2 = param.example.split(' ')[1];
                _this.tip3 = param.example.split(' ')[3];
                layer.open({
                    type: 1,
                    title: '设置凭证模板',
                    skin: 'components',
                    shadeClose: true,
                    shade: false,
                    maxmin: false, //开启最大化最小化按钮
                    area: ['600px', '400px'],
                    content: $('#szPzmb'),
                    btn: ['保存', '取消'],
                    yes: function (index, layero) {
                        var param1 = {};
                        param1.company_id = param.company_id;
                        param1.tax_id = param.tax_id;
                        param1.tax_name = param.tax_name;
                        param1.debit_number = _this.data1.split(' ')[0];
                        param1.debit_name = _this.data1.split(' ')[1];
                        param1.credit_number = _this.data2.split(' ')[0];
                        param1.credit_name = _this.data2.split(' ')[1];
                        param1.status = param.status;
                        param1.id = param.id;
                        _this.$http.post('{{ url('book/taxConfig/save') }}', param1).then(function (response) {
                            if (response.status == 200) {
                                this.render1();
                                // console.log(response);
                                _this.$http.get('{{ url('book/taxConfig/list') }}', {params: {company_id: '{{ \App\Entity\Company::sessionCompany()->id }}'}}).then(function (response) {
                                    if (response.status == 200) {
                                        vm_component_group.sjData = response.data;

                                    }
                                })
                            }
                        });
                        layer.close(index)
                    }
                })
            },

            // 选择会计科目
            selectKjkmItem1: function (e) {
                // console.log(e.target.innerText)
                this.data1 = e.target.innerText;

            },
            selectKjkmItem2: function (e) {
                this.data2 = e.target.innerText;
            },
            // 点击空白处收起下拉框
            clickBlank: function () {
                var _this = this;
                document.addEventListener('click', function (e) {
                    // console.log(!_this.$refs.option1.contains(e.target))
                    if (!_this.$refs.option1.contains(e.target)) {
                        _this.flag1 = false;
                    }
                    if (!_this.$refs.option2.contains(e.target)) {
                        _this.flag2 = false;
                    }
                })

            }
        }
    })
</script>
<!--结转设置-->
<script>
    new Vue({
        'el': '#jiezhuangSz',
        data: {
            // 下拉框
            flag1: false,
            flag2: false,
            date1: '',
            date2: '',
            // 损益结转
            optionItems: '',
            // optionItems2: [
            //     {
            //         id: 1,
            //         label: '100101 库存现金_88888'
            //     },
            //     {
            //         id: 2,
            //         label: '100102 库存现金_24343'
            //     },
            //     {
            //         id: 3,
            //         label: '100103 库存现金_1'
            //     },
            //     {
            //         id: 4,
            //         label: '100201 银行存款_江苏紫金农村商业银行股份有限公司金箔路支行'
            //     },
            //     {
            //         id: 5,
            //         label: '100202 银行存款_123123'
            //     }
            // ],
            // 汇率
            exchange_rate: [
                {
                    'USD': '6.6166',
                    'EUR': '7.6515',
                    'JPY': '0.059914',
                    'HKD': '0.8431',
                    'NZD': '4.4704',
                    'ZAR': '0',
                    'SEK': '1'
                }
            ]
        },
        mounted: function () {
            this.clickBlank();
        },
        created: function () {
            this.render()
        },
        methods: {
            selectItem1: function (e) {
                this.date1 = e.target.innerText;
            },
            selectItem2: function (e) {
                this.date2 = e.target.innerText;
            },
            render: function () {
                var _this = this;
                _this.$http.get('{{ route('account_subject.index') }}').then(function (response) {
                    if (response.status == 200) {
                        _this.optionItems = response.body;
                        // console.log(_this.optionItems)
                    }

                })
            },
            // 点击空白处收起下拉框
            clickBlank: function () {
                var _this = this;
                document.addEventListener('click', function (e) {
                    // console.log(!_this.$refs.option1.contains(e.target))
                    if (!_this.$refs.option1.contains(e.target)) {
                        _this.flag1 = false;
                    }
                    if (!_this.$refs.option2.contains(e.target)) {
                        _this.flag2 = false;
                    }
                })

            }
        }

    })
</script>
<!--查看税金计提凭证-->
<script>
    new Vue({
        'el': '#sjjtPz',
        data: {
            formData: [
                {
                    label: '应交教育附加',
                    value: '金额为0,不生成凭证'
                },
                {
                    label: '计提印花税',
                    value: '记-26'
                },
                {
                    label: '应交地方教育费附加',
                    value: '金额为0,不生成凭证'
                }
            ]
        },
        created: function () {

        },
        methods: {}
    })
</script>
<!--直接结账-->
<script>
    var vm_zhijieJz = new Vue({
        'el': '#zhijieJz',
        data: {
            result: '',
        },
        created: function () {

        },
        mounted: function () {

        },
        methods: {}
    })
</script>
<!--失败结账-->
<script>
    var vm_jiezhangShibai = new Vue({
        'el': '#jiezhangShibai',
        data: {
            result: '',
        },
        created: function () {

        },
        mounted: function () {

        },
        methods: {}
    })
</script>
<!--反结账-->
<script>
    var vm_fanJz = new Vue({
        'el': '#fanJz',
        data: {
            result: '',
        },
        created: function () {

        },
        mounted: function () {

        },
        methods: {}
    })
</script>
<!--清单凭证_批量生成凭证-->
<script>
    var vm_qdpz_piliangPz = new Vue({
        'el': '#qdpz_piliangPz',
        data: {
            scpzData: '',
        },
        created: function () {
            this.render()
        },
        methods: {
            render: function () {

            }
        }
    })
</script>
<!--税金计提_批量生成凭证-->
<script>
    var vm_sjjt_piliangPz = new Vue({
        'el': '#sjjt_piliangPz',
        data: {
            scpzData: '',
        },
        created: function () {
            this.render()
        },
        mounted: function () {

        },
        methods: {
            render: function () {

            }
        }
    })
</script>
<!--损益结转_批量生成凭证-->
<script>
    var vm_syjz_piliangPz = new Vue({
        'el': '#syjz_piliangPz',
        data: {
            msg: ''
        },
        created: function () {
            this.render()
        },
        mounted: function () {

        },
        methods: {
            render: function () {

            }
        }
    })
</script>
</html>