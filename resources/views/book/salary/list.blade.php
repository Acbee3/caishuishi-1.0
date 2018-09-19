@extends('book.layout.base')

@section('title')薪酬表@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--薪酬表-->
    <link rel="stylesheet" href="{{asset("css/book/paid/payroll.css?v=20180904")}}">
    <link rel="stylesheet" href="{{asset("css/book/paid/fzxc.css")}}">
@endsection

@section('content')
<div class="payroll" v-cloak>
    <div class="payrollWrapper">
        <div class="payrollTop">
            <div class="payrollCopy">
                <a class="add" href="javascript:void(0);" @click="newSalary">新增</a>
                <a class="copyOld" href="javascript:void(0);" @click="copySalary">复制往期薪酬</a>
                <a href="javascript:void(0);" @click="kmConfig" style="display:none;">凭证科目配置</a>
                <a class="subjectConfig" data-href='{{ route('salary.km_config') }}' data-title="薪酬" href="javascript:parent.creatIframe('{{ route('salary.km_config') }}','凭证科目配置')">凭证科目配置</a>
            </div>
        </div>
        <div class="payrollAllTable">
            <div class="payrollTableHead">
                <table border="0">
                    <thead>
                    <tr>
                        <th class="width2"></th>
                        <th class="width14">薪酬类型</th>
                        <th class="width14">薪酬所属期起</th>
                        <th class="width14">薪酬所属期止</th>
                        <th class="width14">人数</th>
                        <th class="width14">支付方式</th>
                        <th class="width14">凭证号</th>
                        <th class="width14">操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="payrollTableCenter">
                <table>
                    <tbody>
                    <tr v-for="(item, index) in payrollTables" :key="item">
                        <td class="width2" >@{{index+1}}</td>
                        <td class="width14">@{{item.compensationType}}</td>
                        <td class="width14">@{{item.compensationStart}}</td>
                        <td class="width14">@{{item.compensationEnd}}</td>
                        <td class="width14">@{{item.number}}</td>
                        <td class="width14">@{{item.payment}}</td>
                        <td class="width14 marksNum" style="color: #568bfb; cursor: pointer;" @click="showVoucher(item.Certificate_id)">@{{item.Certificate}}</td>
                        <td class="width14">
                            <div class="fs0">
                                <i class="iconfont iconMarked" @click="createVoucher(item)">&#xe602;</i>
                                <i class="iconfont iconEdit" :class="mapClass[item.status]" @click="payrollSearch(item,index)"></i>
                                <i class="iconfont del" v-show="item.setUp" @click="delSalary(item)">&#xe605;</i>
                                <i class="iconfont" v-show="item.setUp" @click="editSalary(item)">&#xe606;</i>
                                <i class="iconfont iconConfig" style="display: none;">@{{item.id}}</i>
                            </div>

                        </td>
                    </tr>
                    </tbody>
                </table>
                <!-- 分页 -->
                <div style="width: 100%; height: 10px; margin: 0 auto; clear: both;"></div>
                <nav class="container_agerant">
                    @if (!empty($data))
                        {{ $data->appends(['st' => 'salary'])->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
</div>
<div style="display: none;" id="edit_view_link"></div>
<div class="formWrapper" id="formWrapper_box" style="display:none;">
    <form class="layui-form payrollForm">
        <div class="payrollForm-item">
            <label class="payrollLabel">新增薪酬类型:</label>
            <select id="salary_select" name="salary_select" lay-filter="moneyType" v-model="salary_select" >
                {!! $salary_options !!}
            </select>
        </div>
        <div class="payrollForm-item moneyDisabled">
            <label class="payrollLabel">薪酬所属期:</label>
            <div class="itemRight">
                <input type="text" id="testDate">
                <i class="iconfont testDate-1">&#xe620;</i>
            </div>
        </div>
        <div class="payrollForm-item moneyDate">
            <label class="payrollLabel">薪酬所属期:</label>
            <div class="itemRight">
                <input id="moneyDate" name="moneyDate" type="text" disabled>
                <i class="iconfont ">&#xe620;</i>
            </div>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">薪酬所属期起:</label>
            <div class="itemRight">
                <input id="moneyDateStart" name="moneyDateStart" type="text">
                <i class="iconfont" id="moneyDateStart-1">&#xe620;</i>
            </div>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">薪酬所属期止:</label>
            <div class="itemRight">
                <input id="moneyDateEnd" name="moneyDateEnd" type="text">
                <i class="iconfont" id="moneyDateEnd-1">&#xe620;</i>
            </div>
        </div>
        <div class="payrollForm-item payType">
            <label class="payrollLabel ">支付方式:</label>
            <select id="pay_select" name="pay_select" lay-filter="blankType" v-model="pay_select">
                {!! $pay_options !!}
            </select>
        </div>
        <div class="payrollForm-item zcMoney_1">
            <label class="payrollLabel">银行账户:</label>
            <select id="bank_select" name="bank_select" v-model="bank_select" >
                {!! $bank_options !!}
            </select>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">企业类型:</label>
            <select id="company_sort_select" name="company_sort_select" v-model="company_sort_select">
                {!! $company_sort_options !!}
            </select>
        </div>
        <div class="payrollForm-item productMenu">
            <label class="payrollLabel">征收方式:</label>
            <select id="zsfs_select" name="zsfs_select" v-model="zsfs_select">
                {!! $zsfs_options !!}
            </select>
        </div>
    </form>
</div>
<div class="copy_salary" id="copy_salary_box" style="display: none;">
    <div class="fzxc-content">
        <div class="xcType">新增薪酬类型:</div>
        <form class="fzxcWrapper layui-form">
            <div class="fzxc-item" style="width: 300px;">
                <select id="copy_salary_select" name="copy_salary_select" v-model="copy_salary_select" lay-filter="fzxc">
                    {!! $salary_options !!}
                </select>
            </div>
            <div class="fzxc-itemA">
                <div class="copy" id="copy_monthShow">
                    <input type="text" id="copy_monthData" value="">
                    <i class="iconfont" id="copy_monthData-1">&#xe61f;</i>
                </div>
                <div class="copy" id="copy_qn" style="display: none;">
                    <input type="text" id="copy_yearData" value="">
                    <i class="iconfont" id="copy_yearData-1">&#xe61f;</i>
                </div>
                <span>复制至</span>
                <div class="copy" id="to_monthShow">
                    <input type="text" disabled :value="copy_to" id="copy_to">
                    <i class="iconfont">&#xe61f;</i>
                </div>
                <div class="copy" id="to_qn" style="display: none;">
                    <input type="text" disabled :value="copy_to_qn" id="copy_to_qn">
                    <i class="iconfont">&#xe61f;</i>
                </div>
            </div>
        </form>
    </div>
</div>
    <style type="text/css">
        .copy input { padding-left: 10px; height: 28px;}
        .fzxc-item .layui-form-select dl dd.layui-this { background: #5FB878;}
    </style>
@endsection

@section('script')
    <script>
        var xinz =  new Vue({
            'el': '.payroll',
            data: {
                paymentList:false,
                getPayment:'',
                paymentOptions:'',
                payrollTables:'',
                selectVal:'',
            },
            created:function(){
                this.getIcon();
                this.mapClass = ['icon-bianji','icon-search'];
                this.getSalaryList();
            },
            methods:{
                /*----每行小图标的不同显示---------*/
                getIcon:function(){
                    for (var i in this.payrollTables){
                        if(this.payrollTables[i].Certificate){
                            this.payrollTables[i].editor = false;
                            this.payrollTables[i].searchContent = true;
                        }
                    }
                },
                /*--- 新增薪酬 ok ---*/
                newSalary:function(){
                    let _this = this;
                    layer.open({
                        type: 1,
                        title: '新增薪酬',
                        skin: 'addAleart',
                        shadeClose: true,
                        shade: false,
                        maxmin: true, //开启最大化最小化按钮
                        area: ['410px', '420px'],
                        content:$('#formWrapper_box'),
                        btn:['确定','取消'],
                        yes: function () {
                            let salary_id = $("#salary_select option:selected").val();
                            let pay_type_id = $("#pay_select option:selected").val();
                            let bank_account_id = $("#bank_select option:selected").val();
                            let company_sort_id = $("#company_sort_select option:selected").val();
                            let zsfs_id = $("#zsfs_select option:selected").val();
                            let belong_time = $("#moneyDate").val();
                            let begin_date = $("#moneyDateStart").val();
                            let end_date = $("#moneyDateEnd").val();
                            let data = {
                                _token: "{{ csrf_token() }}",
                                xclx:salary_id,
                                pay_type_id:pay_type_id,
                                bank_account_id:bank_account_id,
                                company_sort_id:company_sort_id,
                                zsfs_id:zsfs_id,
                                belong_time:belong_time,
                                begin_date:begin_date,
                                end_date:end_date,
                                do:'insert'
                            };
                            _this.$http.post('{{ route('salary.api_add_salary') }}', data).then(function (response) {
                                if (response.body.status == 'success') {
                                    layer.closeAll();
                                    layer.msg(response.body.msg, {icon: 1, time: 2000});

                                    // 更新列表页面
                                    //this.getSalaryList();
                                    window.location.reload();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 2000});
                                }
                            })
                        },
                        success: function () {
                            let belongtime = '{{\App\Entity\Salary::Get_Belong_Time()}}';
                            $("#moneyDate").val(belongtime);

                            // 初始下拉框
                            $('#salary_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=""]').click();
                            $('#pay_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=""]').click();
                        }
                    });
                },
                /*------ 修改薪酬 ok ---------*/
                editSalary:function(item){
                    let _this = this;
                    layer.open({
                        type: 1,
                        title: '编辑薪酬',
                        skin: 'addAleart',
                        shadeClose: true,
                        shade: false,
                        maxmin: true, //开启最大化最小化按钮
                        area: ['410px', '420px'],
                        content:$('#formWrapper_box'),
                        btn:['确定','取消'],
                        yes: function () {
                            let salary_id = $("#salary_select option:selected").val();
                            let pay_type_id = $("#pay_select option:selected").val();
                            let bank_account_id = $("#bank_select option:selected").val();
                            let company_sort_id = $("#company_sort_select option:selected").val();
                            let zsfs_id = $("#zsfs_select option:selected").val();
                            let belong_time = $("#moneyDate").val();
                            let begin_date = $("#moneyDateStart").val();
                            let end_date = $("#moneyDateEnd").val();
                            let data = {
                                _token: "{{ csrf_token() }}",
                                xclx:salary_id,
                                pay_type_id:pay_type_id,
                                bank_account_id:bank_account_id,
                                company_sort_id:company_sort_id,
                                zsfs_id:zsfs_id,
                                belong_time:belong_time,
                                begin_date:begin_date,
                                end_date:end_date,
                                id:item.id,
                                do:'update'
                            };
                            _this.$http.post('{{ route('salary.api_add_salary') }}', data).then(function (response) {
                                if (response.body.status == 'success') {
                                    layer.closeAll();
                                    layer.msg(response.body.msg, {icon: 1, time: 2000});

                                    // 更新列表页面
                                    this.getSalaryList();
                                    //window.location.reload();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 2000});
                                }
                            })
                        },
                        success: function () {
                            let belongtime = '{{\App\Entity\Salary::Get_Belong_Time()}}';
                            $("#moneyDate").val(belongtime);

                            // 设置薪酬类型
                            $('#salary_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + item.xclx + ']').click();
                            if(item.pay_type_id == null){
                                $('#pay_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=""]').click();
                                $('#bank_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=""]').click();
                            }else{
                                $('#pay_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + item.pay_type_id + ']').click();
                                $('#bank_select').siblings("div.layui-form-select").find('dl').find('dd[lay-value=' + item.bank_account_id + ']').click();
                            }
                        }
                    });
                },
                /*------ 删除薪酬 ok ---------*/
                delSalary:function(item){
                    let _this = this;
                    layer.confirm('确定要删除 ' + item.compensationType + ' 吗？', {icon: 3, title: '提示'},
                        function () {
                            _this.$http.post('{{ route('salary.api_del') }}', {
                                _token: "{{csrf_token()}}",
                                id: item.id
                            }).then(function (response) {
                                if (response.body.status == 'success') {
                                    layer.msg(response.body.msg, {icon: 1, time: 1000});
                                    this.getSalaryList();
                                    //window.location.reload();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 1000});
                                }
                            })
                        }
                    );
                },
                /*------ 复制往期薪酬 ok ---------*/
                copySalary:function(){
                    let _this = this;
                    layer.open({
                        type: 1,
                        title: '复制往期薪酬',
                        skin: 'copy_salary',
                        shadeClose: true,
                        shade: false,
                        area: ['480px', '320px'],
                        content:$('#copy_salary_box'),
                        btn:['确定','取消'],
                        yes: function () {
                            let salaryId = $("#copy_salary_select option:selected").val();
                            let copy_from = $("#copy_monthData").val();
                            let copy_from_y = $("#copy_yearData").val();
                            let copy_to = $("#copy_to").val();
                            let copy_to_y = $("#copy_to_qn").val();
                            let data = {
                                _token: "{{ csrf_token() }}",
                                salaryId:salaryId,
                                copy_from:copy_from,
                                copy_from_y:copy_from_y,
                                copy_to:copy_to,
                                copy_to_y:copy_to_y,
                            };
                            _this.$http.post('{{ route('salary.api_copy_salary') }}', data).then(function (response) {
                                if (response.body.status == 'success') {
                                    layer.closeAll();
                                    layer.msg(response.body.msg, {icon: 1, time: 2000});
                                    this.getSalaryList();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 2000});
                                }
                            });
                        },
                        success: function () {
                            // 打开窗口处理逻辑
                            let belongtime = '{{\App\Entity\Salary::Get_Belong_Time()}}';
                            $("#copy_to").val(belongtime);
                            let belongtime_year = '{{\App\Entity\Salary::Get_Belong_Time_Year()}}';
                            $("#copy_to_qn").val(belongtime_year);
                        }
                    });

                },
                /*------ 凭证科目配置  已采用新页面开发---------*/
                kmConfig:function(){
                    layer.msg("凭证科目配置，推后开发……", {icon: 2, time: 2000});

                },
                /*------ 生成记账凭证  ok ---------*/
                createVoucher:function(item){
                    let cert_id = item.Certificate_id;
                    let salary_id = item.id;
                    let s_member = item.number;
                    if( s_member > 0){
                        if( cert_id == null || cert_id == 0)
                        {
                            let _this = this;
                            layer.confirm('确定要生成 ' + item.compensationType + ' 的记账凭证吗？', {icon: 3, title: '提示'},
                                function () {
                                    _this.$http.post('{{ route('salary.api_create_voucher') }}', {
                                        _token: "{{csrf_token()}}",
                                        id: item.id
                                    }).then(function (response) {
                                        if (response.body.status == 'success') {
                                            //layer.msg(response.body.msg, {icon: 1, time: 1000});
                                            layer.closeAll();

                                            let data = {
                                                '_token': '{{ csrf_token()  }}',
                                                'voucher_num':response.body.data.voucher_num,
                                                'attach':response.body.data.attach,
                                                'voucher_date':response.body.data.voucher_date,
                                                'voucher_source':response.body.data.voucher_source,
                                                'total_debit_money':response.body.data.total_debit_money,
                                                'total_credit_money':response.body.data.total_credit_money,
                                                'total_cn':response.body.data.total_cn,
                                                'items':response.body.data.items,
                                                'salary_id':salary_id
                                            };
                                            _this.$http.post('{{ route('salary.make_voucher') }}', data).then(function (response) {
                                                if (response.body.status == '1'){
                                                    let voucher_id = response.body.data.id;
                                                    if(voucher_id > 0){
                                                        localStorage.setItem('voucher_id',voucher_id);
                                                        layer.open({
                                                            type: 2,
                                                            title: '薪酬凭证预览页面',
                                                            shadeClose: true,
                                                            shade: 0.2,
                                                            maxmin: true,
                                                            area: ['1200px', '96%'],
                                                            content: ['{{ url('book/voucher/edit') }}', 'yes']
                                                        });
                                                        _this.getSalaryList();
                                                    }
                                                }
                                            });
                                        } else {
                                            layer.msg(response.body.msg, {icon: 2, time: 1000});
                                        }
                                    })
                                }
                            );
                        }else{
                            layer.confirm('系统已经生成 ' + item.compensationType + ' 的记账凭证了，不需要再次进行操作。', {icon: 3, title: '提示'},
                                function () {
                                    layer.closeAll();
                                }
                            );
                        }
                    }else{
                        layer.confirm('系统检查到 ' + item.compensationType + ' 尚未添加员工薪酬，生成凭证失败！请先添加薪酬再执行生成凭证。', {icon: 3, title: '提示'},
                            function () {
                                layer.closeAll();
                            }
                        );
                    }
                },
                /*-- 查看凭证信息 ok ------*/
                showVoucher:function(voucher_id){
                    if(voucher_id > 0 && voucher_id != null){
                        localStorage.setItem('voucher_id',voucher_id);
                        layer.open({
                            type: 2,
                            title: '薪酬凭证预览页面',
                            shadeClose: true,
                            shade: 0.2,
                            maxmin: true, //开启最大化最小化按钮
                            area: ['1200px', '96%'],
                            content: ['{{ url('book/voucher/edit') }}', 'yes'],
                        });
                    }else{
                        layer.msg("还没有生成此薪酬的凭证呢~~~", {icon: 2, time: 1000});
                    }
                },
                /*--新增form表格内的数据------*/
                getPayments:function(item){
                    this.getPayment = item;
                    this.paymentList = false;
                },
                /*----点击每行的编辑或者查看信息--------*/
                payrollSearch:function(item, index){
                    let cert_id = item.Certificate_id;
                    let _this = this;
                    let data = {
                        _token: "{{ csrf_token() }}",
                        xclx:item.xclx,
                        id:item.id
                    };
                    if( cert_id == null)
                    {
                        //编辑
                        _this.$http.post('{{ route('salary.api_get_link') }}', data).then(function (response) {
                            // 以下用于打开新窗口
                            /*$("#edit_view_link").html(response.body.link);
                            $("#edit_view_link").show();
                            $("#edit_view_link a").click;
                            console.log(response.body.link);*/
                            window.location.href = response.body.link;
                        });
                    }else{
                        //查看
                        _this.$http.post('{{ route('salary.api_get_link') }}', data).then(function (response) {
                            window.location.href = response.body.link;
                        });
                    }
                },
                /*------ 获取薪酬主列表 ok ---------*/
                getSalaryList: function(){
                    let page = '{{$request->page}}';
                    let data = {
                        _token: "{{ csrf_token() }}",
                        'page': page
                    };
                    this.$http.post('{{ route('salary.api_get_salary') }}', data).then(function (response) {
                        response = response.body;
                        this.payrollTables = response.data.items;
                        layer.load(2, {shade: false, time: 500});
                        if(response.data.show){
                            layer.confirm('凭证科目尚未配置，立即配置？', {icon: 3, title: '提示'},
                                function () {
                                    layer.closeAll();
                                    layer.open({
                                        type: 2,
                                        title: '凭证科目配置',
                                        shadeClose: true,
                                        shade: 0.2,
                                        maxmin: true,
                                        area: ['950px', '80%'],
                                        content: ['{{ url('book/salary/salary_km_config') }}', 'yes'],
                                    });
                                }
                            );
                        }
                    })
                }
            }
        })
    </script>
    <!-- 新增弹窗调用的VUE -->
    <script>
        layui.use(['layer','form','laydate','jquery'],function(){
            var laydate = layui.laydate;
            var layer = layui.layer;
            var form = layui.form;
            laydate.render({
                elem: '#testDate'
                ,eventElem: '.testDate-1'
                ,trigger: 'click'
                ,type:'month'
            });
            laydate.render({
                elem: '#testDateYear'
                ,eventElem: '#testDateYear-1'
                ,trigger: 'click'
                ,type:'year'
            });
            laydate.render({
                elem: '#moneyDateStart'
                ,eventElem: '#moneyDateStart-1'
                ,trigger: 'click'
                ,type:'month'
            });
            laydate.render({
                elem: '#moneyDateEnd'
                ,eventElem: '#moneyDateEnd-1'
                ,trigger: 'click'
                ,type:'month'
            });
            /*----监听新增薪酬类型----*/
            form.on('select(moneyType)', function(data){
                var upOption = data.value;
                /*---moneyDisabled所属期可点，moneyDate所属期不可点，payType支付方式，productMenu所属期起所属期止企业类型征收方式---*/
                switch(data.value){
                    case '0':
                        $('.moneyDisabled').hide();
                        $('.moneyDate').show();
                        $('.payType').show();
                        $('.productMenu').hide();
                        break;
                    case '1':
                        $('.moneyDisabled').show();
                        $('.moneyDate').hide();
                        $('.payType').show();
                        $('.productMenu').hide();
                        break;
                    case '2':
                        $('.moneyDisabled').hide();
                        $('.moneyDate').show();
                        $('.payType').show();
                        $('.productMenu').hide();
                        break;
                    case '3':
                        $('.moneyDisabled').show();
                        $('.moneyDate').hide();
                        $('.payType').show();
                        $('.productMenu').hide();
                        break;
                    case '4':
                        $('.moneyDisabled').hide();
                        $('.moneyDate').show();
                        $('.payType').show();
                        $('.productMenu').hide();
                        break;
                    case '5':
                        $('.moneyDisabled').hide();
                        $('.moneyDate').show();
                        $('.payType').hide();
                        $('.productMenu').hide();
                        break;
                    case '6':
                        $('.moneyDisabled').hide();
                        $('.moneyDate').hide();
                        $('.payType').hide();
                        $('.productMenu').show();
                        break;
                    case '7':
                        $('.moneyDisabled').hide();
                        $('.moneyDate').hide();
                        $('.payType').hide();
                        $('.productMenu').show();
                        break;

                }
            });
            /*-----支付方式为银行---*/
            form.on('select(blankType)', function(data){
                if(data.value == 2){
                    $('.zcMoney_1').show()
                }else{
                    $('.zcMoney_1').hide()
                }
            });
            form.render();
        });
        var alertC =  new Vue({
            'el': '.formWrapper',
            data: {
                options: '',
                salary_select:'',
                pay_select:'',
                bank_select:'',
                company_sort_select:'',
                zsfs_select:''
            },
            created:function(){
                //this.getUpData();
            },
            methods: {
                getUpData:function(){
                    //console.log(this.selected)
                }
            }
        })
    </script>
    <!-- 复制薪酬弹窗调用的VUE -->
    <script>
        layui.use(['layer','laydate'],function(){
            var laydate = layui.laydate;
            laydate.render({
                elem: '#copy_monthData'
                ,eventElem: '#copy_monthData-1'
                ,trigger: 'click'
                ,type:'month'
            });
            laydate.render({
                elem: '#copy_yearData'
                ,eventElem: '#copy_yearData-1'
                ,trigger: 'click'
                ,type:'year'
            })
        });
        new Vue({
            'el': '.copy_salary',
            data: {
                copy_qn:true,
                copy_monthShow: true,
                copy_to:'2017-06'
            },
            created:function(){
                let _this = this
                layui.use('form',function(){
                    let form = layui.form;
                    form.on('select(fzxc)',function(data){
                        //console.log(data.value);
                        if(data.value == '2'){
                            $("#copy_monthShow").hide();
                            $("#copy_qn").show();
                            $("#to_monthShow").hide();
                            $("#to_qn").show();
                            //_this.copy_qn = true;
                            //_this.copy_monthShow =false;
                        }else{
                            $("#copy_monthShow").show();
                            $("#copy_qn").hide();
                            $("#to_monthShow").show();
                            $("#to_qn").hide();
                            //_this.copy_qn = false;
                            //_this.copy_monthShow =true;
                        }
                    })
                });
            }
        })
    </script>
@endsection