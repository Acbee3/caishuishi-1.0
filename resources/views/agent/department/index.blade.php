@extends('agent.layouts.agent')

@section('content')
    <link rel="stylesheet" href="{{url("agent/css/agent/zTreeStyle.css")}}" type="text/css">
    <link rel="stylesheet" href="{{url("agent/css/agent/officer.css?v=2018082301")}}">
    <div class="officerContainer" id="department">
        <div class="officerLeft">
            <p class="text">部门</p>
            <div class="officeBtn">
                <input type="checkbox" id="callbackTrigger" checked style="display:none"/>
                {{--<a id="addParent" href="#" title="增加父节点" onclick="return false;" class="officerNewadd treeNewAdd" >父级</a>--}}
                <a id="addLeaf" href="#" title="新建" onclick="return false;" class="officerNewadd treeNewAdd" >新建</a>
                <a id="edit" href="#" title="编辑" onclick="return false;" class="officerNewadd treeNewAdd" >编辑</a>
                <a id="remove" href="#" title="删除" onclick="return false;" class="officerNewaddDel" >删除</a>
            </div>
            <div class="officeTree" id="officeTree">
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
        <div class="officerRight">
            <p class="officerCustomer">人员管理</p>
            <div class="officerSearch">
                <div class="search">
                    <input type="text" placeholder="请输入人员"  v-model="true_name" @keyup.enter="search()">
                    <i class="icon iconfont icon-search" @click="search()" ></i>
                </div>
                <div class="officerRightBtn">
                    <a href="{{url("agent/user/agent-create")}}">新增</a>
                </div>
            </div>
            <div class="officerTable" id="officerTable">
                <table>
                    <thead>
                    <tr>
                        <th class="width10">姓名</th>
                        <th class="width10">登录名</th>
                        <th class="width20">角色</th>
                        <th class="width20">部门</th>
                        <th class="width15">状态</th>
                        <th class="width25">操作</th>
                    </tr>
                    </thead>
                    <tbody>


                    <tr v-for="(item,index) in userList">
                        <td>@{{ item.true_name }}</td>
                        <td>@{{ item.name}}</td>
                        <td>@{{ item.role}}</td>
                        <td>@{{ item.department}}</td>
                        <td>
                            <a href="javascript:;" v-if="item.status=='正常'" class="orangeBtn" @click="changeStatus(index)">@{{ item.status }}</a>
                            <a href="javascript:;" v-if="item.status=='禁用'" @click="changeStatus(index)">@{{ item.status }}</a>
                        </td>
                        <td>
                            <a :href="item.update_url" class="iconfont borderBlure">&#xe606;</a>
                            <a href="javascript:;" class="iconfont del">&#xe605;</a>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <div class="page-bar">
                    <ul>
                        <li v-if="cur>1"><a @click="cur--,pageClick()">上一页</a></li>
                        <li v-if="cur==1"><a class="banclick">上一页</a></li>
                        <li v-for="index in indexs"  :class="{ 'active': cur == index}">
                            <a @click="btnClick(index)">@{{ index }}</a>
                        </li>
                        <li v-if="cur!=all"><a @click="cur++,pageClick()">下一页</a></li>
                        <li v-if="cur == all"><a class="banclick">下一页</a></li>
                        <li><a>共<i>@{{all}}</i>页</a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('footer')
    @include('agent.common.footer')
    @include('agent.common.page')
    <script src="{{url("agent/js/agent/tree.js")}}"></script>
    <script type="text/javascript" src="{{url("agent/js/agent/jquery.ztree.core-3.5.js")}}"></script>
    <script type="text/javascript" src="{{url("agent/js/agent/jquery.ztree.exedit-3.5.js")}}"></script>
    <script>

        new Vue({
            el: "#department",
            data: {
                userList:[],
                true_name:'',
                all: 0, //总页数
                cur: 0, //当前页码
                pageSize:20, //分页个数
            },
            created: function () {
                this.getData();
            },
            methods: {
                search:function(){

                    this.$http.post("/agent/user/agent-index",{_token:"{{csrf_token()}}",page:this.cur,pageSize:this.pageSize,true_name:this.true_name}).then(function(response){
                        response = response.body;
                        this.userList =  response.data;
                        this.all =  response.last_page;
                        this.cur =  response.current_page;
                    })
                },
                getData:function (){

                    this.$http.post("/agent/user/agent-index",{_token:"{{csrf_token()}}",page:this.cur,pageSize:this.pageSize}).then(function(response){
                        response = response.body;
                        this.userList =  response.data;
                        this.all =  response.last_page;
                        this.cur =  response.current_page;
                    })
                },
                changeStatus:function(index){
                    id = this.userList[index].id
                    this.$http.post("/agent/user/change-status",{_token:"{{csrf_token()}}",'id':id}).then(function(response){
                        this.getData();
                    });
                },
                btnClick: function(data){//页码点击事件
                    if(data != this.cur){
                        this.cur = data
                    }
                    this.getData();
                },
                pageClick: function(){
                    this.getData();
                }
            },watch: {
                cur: function(oldValue , newValue){
                    //console.log(arguments);
                }
            },
            computed: {
                indexs: function(){
                    var left = 1;
                    var right = this.all;
                    var ar = [];
                    if(this.all>= 5){
                        if(this.cur > 3 && this.cur < this.all-2){
                            left = this.cur - 2
                            right = this.cur + 2
                        }else{
                            if(this.cur<=3){
                                left = 1
                                right = 5
                            }else{
                                right = this.all
                                left = this.all -4
                            }
                        }
                    }
                    while (left <= right){
                        ar.push(left)
                        left ++
                    }
                    return ar
                }

            }
        })
    </script>
@endsection