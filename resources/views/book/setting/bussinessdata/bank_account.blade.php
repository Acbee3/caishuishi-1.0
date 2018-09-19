@extends('book.layout.base')
<!--公用-->
@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/businessCustomert.css?v=2018081708">
    <style>
        .stop{
            display:inline-block;
            padding:0 10px;
            color:#666;
            font-size:14px;
            background:#ccc;
            height:26px;
            line-height:26px;
            border-radius:4px;
        }
        .start{
            display:inline-block;
            padding:0 10px;
            color:#fff;
            font-size:14px;
            background:#ec7638;
            height:26px;
            line-height:26px;
            border-radius:4px;
        }
        .start:hover{
            color:#fff;
        }
        .stop:hover{
            color:#666;
        }
    </style>
@endsection

@section('content')
<div class="businessDate" id="bookCustomer" v-cloak>
    <div class="businessSearch">
        <div class="search">
            <input type="text" name="keyword" placeholder="请输入账户简称" v-model="keyWord">
            <span @click="searchTable" class="searchBtn">搜索</span>
            {{--<i class="icon iconfont icon-search" @click="searchTable"></i>--}}
        </div>
        <div class="businessEditor">
            <a href="javascript:;" class="editorBtn businessAdd" @click="add">新增</a>
            <a href="javascript:;" class="editorBtn businessDel" @click="del">删除</a>
        </div>
    </div>
    <div class="businessTable" id="bookCustomerTable">
        <div class="fixTableHeader">
            <table border="0">
                <thead>
                <tr>
                    <th class="width10"><input type="checkbox" v-model='checked' @click='checkedAll'></th>
                    <th class="width30">账户简称</th>
                    <th class="width20">对应科目</th>
                    <th class="width15">状态</th>
                    <th class="width25">操作</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="tableScroll" id="tableScroll">
            <table border="0">
                <tbody>
                <tr v-for="(item,index) in tableData" :key="item.id">
                    <td class="width10"><input type="checkbox" v-model='checkList' :value="item.id"></td>
                    <td class="width30">@{{item.name}}</td>
                    <td class="width20">@{{item.account_subject.number}}_@{{item.account_subject.name}}</td>
                    <td class="width15"><a href="javascript:;" :class="classMap[item.status]" v-text="dateMap[item.status]" @click="changeStatus(index)"></a></td>
                    {{-- <td class="width15" :class="classMap[item.status]" v-text="dateMap[item.status]"></td>--}}
                    <td class="width25">
                        <a href="javascript:;" class="icon iconfont icon-bianji" @click="editorShow(index)"></a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="businessDialog" v-show="dialog" style="display:none;">
        <div class="bussinessContent" id="bankInfo">
            <div class="bussinessTitle">
                <p class="text">新增银行账户</p>
                <span @click="cancleBtn" class="iconfont">&#xe61d;</span>
            </div>
            <form id="create_data">
                {{ csrf_field() }}
                <div class="bussiness-item">
                    <label>账户简称:</label>
                    <input type="text" name="name">
                </div>
                <div class="bussiness-item">
                    <label>对应科目:</label>
                    <select name="subject_id">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussinessBtn">
                    <a href="javascript:;" class="sureBtn" @click='create'>确定</a>
                    <a href="javascript:;" class="cancleBtn" @click="cancleBtn">取消</a>
                </div>
            </form>
        </div>
    </div>
    <div class="editorDialog" v-show="editorDialog" style="display:none;">
        <div class="bussinessContent" id="bankInfo1">
            <div class="bussinessTitle">
                <p class="text">编辑银行信息</p>
                <span @click="editorCancle">x</span>
            </div>
            <form id="edit_data">
                {{ csrf_field() }}
                <div class="bussiness-item">
                    <label>账户简称:</label>
                    <input type="text" name="name" :value="customValue">
                </div>
                <div class="bussiness-item">
                    <label>对应科目:</label>
                    <select name="subject_id">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option v-if="{{$v['id']}} == subject_id" selected value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                            <option v-else value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussinessBtn">
                    <a href="javascript:;" class="sureBtn" @click='edit'>确定</a>
                    <a href="javascript:;" class="cancleBtn" @click="editorCancle">取消</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    @parent
<script src="/common/js/jquery.serializejson.min.js"></script>
<script src="/js/book/table.js"></script>
<script>
    layui.use(['layer'], function () {

        new Vue({
            el: "#bookCustomer",
            data: {
                dialog: false,
                editorDialog: false,
                tableData: [],
                customValue:'',
                subject_id:'',
                edit_id: '',
                keyWord: '',
                checked: false,
                allProductTotal: null,
                checkList: []
            },
            created: function () {
                this.getTableData()
                this.dateMap = ['冻结','正常'];
                this.classMap = ['stop','start']
            },
            methods: {
                searchTable:function(){
                    var keyword = this.keyWord;
                    this.$http.get('{{ route('bankaccount.index') }}', {params:{keyword:keyword}}).then(function (response) {
                        this.tableData = response.body
                    }).then(function(){
                        computedHeight()
                    })
                },
                add: function () {
                    this.dialog = true
                },
                create: function () {
                    this.$http.post('{{ route('bankaccount.store') }}', $('#create_data').serializeJSON()).then(function (response) {
                        layer.msg(response.body.success, {icon:1,time:1000});
                        window.location.reload();
                    })
                },
                del: function () {
                    var _this = this;
                    layer.confirm(
                            '确定删除吗？', {icon: 3, title:'提示',skin:'businessAlert'},
                            function () {
                                _this.$http.post('{{ route('bankaccount.del') }}', {_token:"{{csrf_token()}}",data:_this.checkList}).then(function (response) {
                                    if (response.body.success == '操作成功'){
                                        layer.msg(response.body.success, {icon:1,time:1000});
                                        window.location.reload();
                                    }else{
                                        layer.msg(response.body.success, {icon:2,time:1000});
                                        window.location.reload();
                                    }
                                })
                            }
                    );
                },
                cancleBtn: function () {
                    this.dialog = false
                },
                editorShow: function (index) {
                    this.editorDialog = true
                    this.edit_id = this.tableData[index].id
                    this.customValue=this.tableData[index].name
                    this.subject_id = this.tableData[index].account_subject['id']
                },
                edit: function () {
                    this.$http.patch('bankaccount/'+this.edit_id, $('#edit_data').serializeJSON()).then(function (response) {
                        if (response.body.success == '操作成功'){
                            layer.msg(response.body.success, {icon:1,time:1000});
                            window.location.reload();
                        }else{
                            layer.msg(response.body.success, {icon:2,time:1000});
                            window.location.reload();
                        }
                    })
                },
                editorCancle: function () {
                    this.editorDialog = false
                },
                getTableData: function () {
                    this.$http.get('{{route('bankaccount.index')}}').then(function (response) {
                        this.tableData = response.body
                    }).then(function(){
                        computedHeight()
                    })
                },
                changeStatus:function(index){
                    var newStatus = this.tableData[index].status
                    var id = this.tableData[index].id
                    if(newStatus==1){
                        newStatus = 0
                    }else{
                        newStatus = 1
                    }
                    this.$http.post('{{route('bankaccount.freeze')}}',{_token:"{{csrf_token()}}",status:newStatus,id:id}).then(function(response){
                        if (response.body.success == '操作成功'){
                            layer.msg(response.body.success, {icon:1,time:1000});
                            this.tableData[index].status = newStatus
                        }else{
                            layer.msg(response.body.success, {icon:2,time:1000});
                            window.location.reload();
                        }
                    })
                },
                /*----全选-------*/
                checkedAll: function() {
                    if(this.checkList.length != this.tableData.length){
                        this.checkList = []
                        for(var i in this.tableData){
                            this.checkList.push(this.tableData[i].id)
                        }
                    }else{
                        this.checkList = []
                    }
                }
            },
            watch: { //深度 watcher
                "checkList":function(){
                    if(this.checkList.length == this.tableData.length){
                        this.checked = true
                    }else{
                        this.checked = false
                    }
                }
            }
        })
    })
</script>
{{--<script src="/js/book/table.js"></script>--}}
@endsection