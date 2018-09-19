@extends('book.layout.base')
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
            <input type="text" name="keyword" placeholder="请输入{{ $types[$type] }}名称" v-model="keyWord">
            {{--<i class="icon iconfont icon-search" @click="searchTable"></i>--}}
            <span @click="searchTable" class="searchBtn">搜索</span>
        </div>
        <div class="businessEditor">
            <a href="javascript:;" class="editorBtn businessAdd" @click="add">新增</a>
            <a href="javascript:;" class="editorBtn businessDel" @click="delcomfirm">删除</a>
        </div>
    </div>
    <div class="businessTable" id="bookCustomerTable">
        <div class="fixTableHeader">
            <table border="0">
                <thead>
                <tr>
                    <th class="width10"><input type="checkbox" v-model='checked' @click='checkedAll'></th>
                    <th class="width30">{{ $types[$type] }}名称</th>
                    <th class="width20">别名</th>
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
                    <td class="width20">@{{item.short_name}}</td>
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
        <div class="bussinessContent" >
            <div class="bussinessTitle">
               <p class="text">新增{{ $types[$type] }}</p>
                <span @click="cancleBtn" class="iconfont">&#xe61d;</span>
            </div>
            <form id="create_data">
                {{ csrf_field() }}
                <input type="hidden" name="type" value={{$type}}>
                <div class="bussiness-item">
                    <label>{{ $types[$type] }}名称:</label>
                    <input type="text" name="name">
                </div>
                <div class="bussiness-item">
                    <label>对应科目1:</label>
                    <select name="subject1">
                        <option value="">--请选择--</option>
                    @foreach($subjects as $v)
                            <option value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目2:</label>
                    <select name="subject2">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目3:</label>
                    <select name="subject3">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目4:</label>
                    <select name="subject4">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目5:</label>
                    <select name="subject5">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>别名:</label>
                    <input type="text" name="short_name">
                </div>
                <div class="bussinessBtn">
                    <a href="javascript:;" class="sureBtn" @click='create'>确定</a>
                    <a href="javascript:;" class="cancleBtn" @click="cancleBtn">取消</a>
                </div>
            </form>
        </div>
    </div>
    <div class="editorDialog" v-show="editorDialog" style="display:none;">
        <div class="bussinessContent" >
            <div class="bussinessTitle">
                <p class="text">编辑{{ $types[$type] }}</p>
                <span @click="editorCancle" class="iconfont">&#xe61d;</span>
            </div>
            <form id="edit_data">
                {{ csrf_field() }}
                <input type="hidden" name="type" value={{$type}}>
                <input type="hidden" name="company_id" value="0">
                <div class="bussiness-item">
                    <label>客户名称:</label>
                    <input type="text" name="name" :value="customValue">
                </div>
                <div class="bussiness-item">
                    <label>对应科目1:</label>
                    <select name="subject1">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option v-if="{{$v['id']}} == subject1" selected value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                            <option v-else value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目2:</label>
                    <select name="subject2">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option v-if="{{$v['id']}} == subject2" selected value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                            <option v-else value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目3:</label>
                    <select name="subject3">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option v-if="{{$v['id']}} == subject3" selected value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                            <option v-else value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目4:</label>
                    <select name="subject4">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option v-if="{{$v['id']}} == subject4" selected value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                            <option v-else value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>对应科目5:</label>
                    <select name="subject5">
                        <option value="">--请选择--</option>
                        @foreach($subjects as $v)
                            <option v-if="{{$v['id']}} == subject5" selected value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                            <option v-else value="{{$v['id']}}">{{ str_repeat('　　', $v['level']).$v['number'].'_'.$v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="bussiness-item">
                    <label>别名:</label>
                    <input type="text" name="short_name" :value="shortName">
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
                shortName:'',
                subject1:'',
                subject2:'',
                subject3:'',
                subject4:'',
                subject5:'',
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
                    this.$http.get('{{ route('bussinessdata.index') }}', {params:{type:"{{ $type }}",keyword:keyword}}).then(function (response) {
                        this.tableData = response.body
                    }).then(function(){
                        computedHeight()
                    })
                },
                add: function () {
                    this.dialog = true
                },
                create: function () {
                    this.$http.post('{{ route('bussinessdata.store') }}', $('#create_data').serializeJSON()).then(function (response) {
                        layer.msg(response.body.success, {icon:1,time:1000});
                        window.location.reload();
                    })
                },
                delcomfirm:function () {
                    var _this = this;
                    layer.confirm(
                        '确定删除吗？', {icon: 3, title:'提示',skin:'businessAlert'},
                        function () {
                            _this.$http.post('{{ route('bussinessdata.del') }}', {_token:"{{csrf_token()}}",data:_this.checkList}).then(function (response) {
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
                    this.editorDialog = true;
                    this.edit_id = this.tableData[index].id;
                    this.customValue=this.tableData[index].name;
                    this.shortName = this.tableData[index].short_name;
                    this.subject1 = this.tableData[index].account_subjects[0]['id'];
                    //二期优化最好不要写死
                    if(this.tableData[index].account_subjects.length >=2 ){
                        this.subject2 = this.tableData[index].account_subjects[1]['id'];
                    }else{
                        this.subject2 = '';
                    }
                    if(this.tableData[index].account_subjects.length >=3 ){
                        this.subject3 = this.tableData[index].account_subjects[2]['id'];
                    }else{
                        this.subject3 = '';
                    }
                    if(this.tableData[index].account_subjects.length >=4 ){
                        this.subject4 = this.tableData[index].account_subjects[3]['id'];
                    }else{
                        this.subject4 = '';
                    }
                    if(this.tableData[index].account_subjects.length >=5 ){
                        this.subject5 = this.tableData[index].account_subjects[4]['id'];
                    }else{
                        this.subject5 = '';
                    }

                },
                edit: function () {
                    this.$http.patch('bussinessdata/'+this.edit_id, $('#edit_data').serializeJSON()).then(function (response) {
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
                    this.$http.get('{{route('bussinessdata.index',['type'=>$type])}}').then(function (response) {
                        this.tableData = response.body
                    }).then(function(){
                        computedHeight()
                    })
                },
                changeStatus:function(index){
                    var newStatus = this.tableData[index].status;
                    var id = this.tableData[index].id;
                    if(newStatus==1){
                         newStatus = 0
                    }else{
                        newStatus = 1
                    }
                    this.$http.post('{{route('bussinessdata.freeze')}}',{_token:"{{csrf_token()}}",status:newStatus,id:id}).then(function(response){
                        if (response.body.success == '操作成功'){
                            layer.msg(response.body.success, {icon:1,time:1000});
                            this.tableData[index].status = newStatus
                        }else{
                            layer.msg(response.body.success, {icon:2,time:1000});
                            window.location.reload();
                        }
                    })
                },
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

@endsection