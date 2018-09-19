@extends('book.layout.base')

@section('title')凭证科目配置@endsection

@section('css')
    @parent
    <!--凭证配置-->
    <link rel="stylesheet" href="{{asset("css/book/paid/pzkmpz.css")}}">
@endsection

@section('content')
    <div class="pzkmpz" style="margin-left: 100px;">
        <div class="pzkm-btn">
            <a href="javascript:void(0);" class="autoConfig" @click="doAutoSettingKM">自动设置科目</a>
        </div>
        <form class="kmForm">
            <div class="kmMenu layui-form">
                <p class="text">计提科目</p>
                <div class="kmWrapper">
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>工资</label>
                            <div class="km-item gz_con">
                                <select id="gz_select" name="gz_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>年终奖</label>
                            <div class="km-item nzj_con">
                                <select id="nzj_select" name="nzj_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>企业公积金</label>
                            <div class="km-item qy_gjj_con">
                                <select id="qy_gjj_select" name="qy_gjj_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>企业社保</label>
                            <div class="km-item qy_sb_con">
                                <select id="qy_sb_select" name="qy_sb_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>个人公积金</label>
                            <div class="km-item gr_gjj_con">
                                <select id="gr_gjj_select" name="gr_gjj_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>个人社保</label>
                            <div class="km-item gr_sb_con">
                                <select id="gr_sb_select" name="gr_sb_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>个税</label>
                            <div class="km-item gs_con">
                                <select id="gs_select" name="gs_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>股息红利</label>
                            <div class="km-item gx_con">
                                <select id="gx_select" name="gx_select">
                                    {!! $ac_options !!}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kmMenu kmFy">
                <p class="text">成本费用</p>
                <div class="kmWrapper" v-for="(list,index) in moneyContent" :key="list">
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>费用类型</label>
                            <div class="km-item">
                                <select v-model="list.cost_type">
                                    <option :value="item.value" v-for="item in type_options" :key="item">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>工资</label>
                            <div class="km-item">
                                <select v-model="list.gz">
                                    <option :value="item.value" v-for="item in options" :key="item">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>年终奖</label>
                            <div class="km-item">
                                <select v-model="list.nzj">
                                    <option :value="item.value" v-for="item in options" :key="item">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="jtkm">
                        <div class="jtkm-first">
                            <label>企业社保</label>
                            <div class="km-item">
                                <select v-model="list.qy_sb">
                                    <option :value="item.value" v-for="item in options" :key="item">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label>企业公积金</label>
                            <div class="km-item">
                                <select v-model="list.qy_gjj">
                                    <option :value="item.value" v-for="item in options" :key="item">@{{item.label}}</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" v-model="list.status">
                    </div>
                    <i class="iconfont" @click="delKm(list, index)">&#xe605;</i>
                </div>
            </div>
            <div class="kmAdd">
                <a href="javascript:void(0);" @click="addMoneyType">新增费用类型</a>
            </div>

            <div class="pzkm-btn" style="margin-top: 30px">
                <a href="javascript:void(0);" style="padding: 1px 30px;" @click="doSaveAccountSubject">保存</a>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        new Vue({
            'el': '.pzkmpz',
            data: {
                moneyContent:[],
                options:[],
                type_options:[],
                cid:'',
                status:'0'
            },
            created:function(){
                // 获取列表数据
                this.getAccountSubject();
            },
            methods: {
                addMoneyType:function(){
                    if(this.moneyContent.length < 8){
                        this.moneyContent.splice(this.moneyContent.length,0,
                            {})
                    }else{
                        layer.msg('成本费用不能大于8条', {icon: 3, time: 1000});
                    }
                },
                /*------ 删除成本费用 行设置 ok ---------*/
                delKm:function(list, index){
                    if(list.status == 0 && list.id > 0) {
                        let data = {
                            _token: "{{ csrf_token() }}",
                            id: list.id, status: list.status
                        };
                        this.$http.post('{{ route('salary.api_del_cost_config') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.msg(response.body.msg, {icon: 1, time: 1000});
                                this.getAccountSubject();
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 2000});
                            }
                        })
                    }else if(list.status == 1 && list.id > 0){
                        layer.msg("此费用类型已生成凭证，不可删除。", {icon: 2, time: 2000});
                    }else{
                        this.moneyContent.splice(index,this.moneyContent.length);
                        return false;
                    }
                },
                /*------ 自动设置科目 do ---------*/
                doAutoSettingKM:function(){
                    //layer.msg("自动设置科目功能暂缓开发!", {icon: 2, time: 2000});
                    let _this = this;
                    layer.confirm('系统将自动创建并设置计提科目和成本费用科目，确定设置吗？', {icon: 3, title: '提示'},
                        function () {
                            _this.$http.post('{{ route('salary.api_auto_config') }}', {
                                _token: "{{csrf_token()}}"
                            }).then(function (response) {
                                if (response.body.status == 'success') {
                                    layer.msg(response.body.msg, {icon: 1, time: 1000});
                                    //_this.getAccountSubject();
                                    window.location.reload();
                                } else {
                                    layer.msg(response.body.msg, {icon: 2, time: 1000});
                                }
                            })
                        }
                    );
                },
                /*------ 保存设置科目 ok ---------*/
                doSaveAccountSubject:function(){
                    let gz = $("#gz_select option:selected").val();
                    let nzj = $("#nzj_select option:selected").val();
                    let qy_gjj = $("#qy_gjj_select option:selected").val();
                    let qy_sb = $("#qy_sb_select option:selected").val();
                    let gr_gjj = $("#gr_gjj_select option:selected").val();
                    let gr_sb = $("#gr_sb_select option:selected").val();
                    let gs = $("#gs_select option:selected").val();
                    let gx = $("#gx_select option:selected").val();
                    let id = this.cid;
                    let status = this.status;
                    let cost_list = this.moneyContent;

                    // 判断成本费用类型重复与否

                    let data = {
                        _token: "{{ csrf_token() }}",
                        id:id,status:status,gz:gz, nzj:nzj, qy_gjj:qy_gjj, qy_sb:qy_sb, gr_gjj:gr_gjj, gr_sb:gr_sb, gs:gs, gx:gx,cost_list:cost_list
                    };
                    this.$http.post('{{ route('salary.api_save_config') }}', data).then(function (response) {
                        if (response.body.status == 'success') {
                            layer.msg(response.body.msg, {icon: 1, time: 1000});
                            this.getAccountSubject();
                        } else {
                            layer.msg(response.body.msg, {icon: 2, time: 2000});
                        }
                    })
                },
                getAccountSubject:function(){
                    let data = {
                        _token: "{{ csrf_token() }}"
                    };
                    this.$http.post('{{ route('salary.api_account_list') }}', data).then(function (response) {
                        if (response.body.status == 'success') {
                            this.options = response.body.data.items;
                            this.type_options = response.body.data.type;

                            if(response.body.data.info != null){
                                // 设置初始选中项
                                this.cid = response.body.data.info.id;
                                this.status = response.body.data.info.status;

                                $('#gz_select').siblings(".gz_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.gz+']').click();
                                $('#nzj_select').siblings(".nzj_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.nzj+']').click();
                                $('#qy_gjj_select').siblings(".qy_gjj_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.qy_gjj+']').click();
                                $('#qy_sb_select').siblings(".qy_sb_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.qy_sb+']').click();
                                $('#gr_gjj_select').siblings(".gr_gjj_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.gr_gjj+']').click();
                                $('#gr_sb_select').siblings(".gr_sb_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.gr_sb+']').click();
                                $('#gs_select').siblings(".gs_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.gs+']').click();
                                $('#gx_select').siblings(".gx_con .layui-form-select").find('dl').find('dd[lay-value='+response.body.data.info.gx+']').click();
                                //console.log(response.body.data.info);

                                // 费用类型
                                //this.type_options = response.body.data.type;

                                // 成本费用列表
                                if(response.body.data.costs.length > 0){
                                    this.moneyContent = response.body.data.costs;
                                }
                            }
                        } else {
                            layer.msg(response.body.msg, {icon: 2, time: 1000});
                        }
                    })/*.then(
                        layui.use('form',function(){
                            var form = layui.form;
                            form.render();
                        })
                    )*/
                }
            }
        })
    </script>
@endsection