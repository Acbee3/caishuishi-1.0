@extends('book.layout.base')
@section('title')部门员工@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="{{asset("css/agent/zTreeStyle.css")}}" type="text/css">
    <link rel="stylesheet" href="{{asset("agent/common/css/agent_center_table.css")}}">
    <!--员工模块-->
    <link rel="stylesheet" href="{{asset("css/book/paid/profess.css?v=20180821")}}">
@endsection

@section('content')
<div id="office" v-cloak>
    <div class="officerContainer">
        <div class="officerLeft">
            <p class="text">部门管理</p>
            <div class="officeBtn">
                <input type="checkbox" id="callbackTrigger" checked style="display:none"/>
                <a id="addLeaf" href="javascript:void(0);" title="增加" onclick="return false;"
                   class="officerNewadd treeNewAdd btn_department_add">增加</a>
                <a id="edit" href="javascript:void(0);" title="编辑" vid="{{$DepartmentId}}" onclick="return false;"
                   class="officerNewadd treeNewAdd btn_department_edit">修改</a>
                <a id="remove" href="javascript:void(0);" title="删除" vid="{{$DepartmentId}}" onclick="return false;"
                   class="officerNewadd officerNewaddDel btn_department_del">删除</a>
            </div>
            <div class="officeTree" id="officeTree">
                <ul id="treeDemo" class="ztree"></ul>
            </div>
        </div>
        <div class="officerRight">
            <p class="officerCustomer">员工管理</p>
            <div class="officerSearch">

                <div class="professTip">
                    <p>温馨提示：删除人员请谨慎！已有收入明细数据的人员如若删除将会影响申报数据！</p>
                </div>
                <div class="officerRightBtn">
                    <a href="javascript:void(0);" class="exportIn btn_employee_add">新增人员</a>
                    <a href="javascript:void(0);" class="exportIn" @click="importEmployee">导入</a>
                    <a href="javascript:void(0);" class="exportOut" @click="exportEmployee">导出Excel</a>
                    <a href="javascript:void(0);" class="professDel" @click="changeDepartment"
                       style="background: #25a1f8; color: #fff; border: none;">调整部门</a>
                    <a href="javascript:void(0);" class="professDel" @click="delSelected">删除</a>
                </div>
            </div>
            <div class="professorSearch">
                <div class="professContent" ref="curMenu">
                    <div class="professHead" @click="professList = !professList">
                        <span class="titleTop">@{{getProfessName}}</span>
                        <i class="icon iconfont icon-xialazhishijiantou"></i>
                    </div>
                    <ul class="professTitle" v-show="professList">
                        <li v-for="item in options" :key="item.index" @click="getProfess(item)">
                            @{{item}}
                        </li>
                    </ul>
                </div>
                <div class="search">
                    <input type="text" :placeholder="'请输入'+getProfessName" v-model="sv">
                    <i class="icon iconfont icon-sousuo" @click="doSearch"></i>
                </div>
            </div>
            <div class="professTableTotal">
                <div class="professTableHeader">
                    <table border="0">
                        <thead>
                        <tr>
                            <th class="width4"><input type="checkbox" @click="allSelect" v-model="checked"></th>
                            <th class="width12">姓名</th>
                            <th class="width12">证件类型</th>
                            <th class="width16">证件号码</th>
                            <th class="width8">是否股东</th>
                            <th class="width10">国籍</th>
                            <th class="width14">部门</th>
                            <th class="width12">人员状态</th>
                            <th class="width12">操作</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="professTableCenter">
                    <table>
                        <tbody>
                        <tr v-if="professorTables && professorTables.length" v-for="(item,index) in professorTables"
                            :key="item">
                            <td class="width4"><input type="checkbox" v-model="selected" :value="item.id"></td>
                            <td class="width12">@{{item.employee_name}}</td>
                            <td class="width12">@{{item.zjlx}}</td>
                            <td class="width16">@{{item.zjhm}}</td>
                            <td class="width8">@{{item.sf_shareholder}}</td>
                            <td class="width10">@{{item.country}}</td>
                            <td class="width14">@{{item.department_name}}</td>
                            <td class="width12">
                                <a href="javascript:void(0);" :class="mapClass[item.personnelState]"
                                   @click="changeStatus(index,item.employee_name,item.id,item.personnelState,item)"
                                   v-text="statusContent[item.personnelState]"></a>
                            </td>
                            <td class="width12">
                                <i class="icon iconfont addEditor" @click="professEdit(item.id)">&#xe60c;</i>
                                <i class="icon iconfont del" @click="professDel(index,item.employee_name,item.id)">&#xe605;</i>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <!-- 分页 -->
                    <div style="width: 100%; height: 10px; margin: 0 auto; clear: both;"></div>
                    <nav class="container_agerant">
                        @if (!empty($data))
                            {{ $data->appends(['st' => 'employee', 'id' => $DepartmentId])->links() }}
                        @endif
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/template" id="import_box">
    <div class="import_box">
        <div class="cfs-upload">
            <div class="file-upload" style="width:100%;">
                <form id="uploadDataForm" name="cfs_uploadDataForm" action="" method="post"
                      enctype="multipart/form-data">
                    <input class="chk_file" type="text" readonly="true">
                    <a id="chk_a" class="chk_a" href="javascript:void(0);">选择员工文件</a>
                    <input id="cfs_file" class="cfs_file" type="file" name="cfs_file"
                           accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                </form>
            </div>
            <p class="m_top20">员工导入模板：<a href="/static/assets/models/employee_import.xlsx" style="color:red">点击下载</a>
            </p>
        </div>
    </div>
</script>
<script type="text/template" id="department_box">
    <div class="department_box">
        <div class="department_list">
            <label class="title">请选择部门:</label>
            <select id="department_select" name="department_select">
                <option value="">loading...</option>
            </select>
        </div>
    </div>
</script>
<!--公用-->
<script src="{{asset("js/agent/jquery.ztree.core-3.5.js")}}" type="text/javascript"></script>
<script src="{{asset("js/agent/jquery.ztree.exedit-3.5.js")}}" type="text/javascript"></script>
<!--<script src="{{asset("agent/common/js/layer.js")}}"></script>-->
<script type="text/javascript">
    var contentSelector = $("#import_box").html();
    var contentDepartment = $("#department_box").html();
</script>
<script>
    new Vue({
        "el": "#office",
        data: {
            professList: false,
            getProfessName: '姓名',
            checked: false,
            setting: {
                view: {
                    selectedMulti: false
                },
                edit: {
                    enable: true,
                    showRemoveBtn: false,
                    showRenameBtn: false
                },
                data: {
                    keep: {
                        parent: true,
                        leaf: true
                    },
                    simpleData: {
                        enable: true
                    }
                },
                callback: {
                    beforeDrag: this.beforeDrag,
                    beforeRemove: this.beforeRemove,
                    beforeRename: this.beforeRename,
                    onRemove: this.onRemove,
                    onClick: this.zTreeOnClick,
                }
            },
            zNodes:{!! $tree_children !!},
            options: [
                '姓名', '证件号码'
            ],
            selected: [],
            professorTables: [],
            sv: '',
            cfs_file_url: '',
            em_num:''
        },
        mounted: function () {
            let nodeData = this.zNodes;
            $.fn.zTree.init($("#treeDemo"), this.setting, nodeData);
            this._getNodes();
            this.clickBlank()
        },
        created: function () {
            this.mapClass = ['abnormal', 'normal'];
            this.statusContent = ['非正常', '正常'];
            // 右侧人员列表
            this.getEmployeeList()
        },
        methods: {
            /*----点击空白处隐藏---*/
            clickBlank:function(){
                /*--会计期间始kjStart，结束kjEnd,日期弹窗dateMenu*/
                var curMenu = this.$refs.curMenu;
                var _this = this;
                document.addEventListener('click',function(e){
                    if(!curMenu.contains(e.target)){
                        _this.professList = false;
                    }
                });
            },
            /*-----z-tree---*/
            getTime: function () {
                var now = new Date(),
                    h = now.getHours(),
                    m = now.getMinutes(),
                    s = now.getSeconds(),
                    ms = now.getMilliseconds();
                return (h + ":" + m + ":" + s + " " + ms)
            },
            beforeRename: function (treeId, treeNode, newName) {
                if (newName.length == 0) {
                    alert("节点名称不能为空");
                    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                    setTimeout(function () {
                        zTree.editName(treeNode)
                    }, 10);
                    return false;
                }
                return true;
            },
            _getNodes: function () {
                var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
                var sNodes = treeObj.getSelectedNodes();
                treeNode = sNodes[0];
                if (sNodes.length > 0) {
                    var tId = sNodes[0].tId;
                    //console.log(tId)
                }
            },
            /*------获取用户名---------*/
            getProfess: function (item) {
                this.professList = false;
                this.getProfessName = item;
            },
            /*------ 全选 ok ----------*/
            allSelect: function () {
                var _this = this;
                if (_this.checked) {
                    //反选
                    _this.selected = [];
                } else {
                    //全选
                    _this.selected = [];
                    _this.professorTables.forEach(function (item, index) {
                        if (index >= 0) {
                            _this.selected.push(item.id);
                        }
                    });
                }
            },
            /*------ 执行多个人员删除操作 ok ----------*/
            delSelected: function () {
                if (this.selected.length <= 0) {
                    layer.msg("请选择要删除的人员！", {icon: 2, time: 2000});
                    return false;
                }
                var _this = this;
                layer.confirm('确定要删除已选择的人员吗？', {icon: 3, title: '提示'},
                    function () {
                        _this.$http.post('{{ route('employee.api_del_ids') }}', {
                            _token: "{{csrf_token()}}",
                            ids: _this.selected
                        }).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.msg(response.body.msg, {icon: 1, time: 1000});
                                window.location.reload();
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 1000});
                            }
                        })
                    }
                );
            },
            /*------ 执行单个人员删除操作 ok ----------*/
            professDel: function (index, name, id) {
                var _this = this;
                layer.confirm('确定要删除员工 ' + name + ' 吗？', {icon: 3, title: '提示'},
                    function () {
                        //console.log(index);
                        _this.$http.post('{{ route('employee.api_del') }}', {
                            _token: "{{csrf_token()}}",
                            id: id
                        }).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.msg(response.body.msg, {icon: 1, time: 1000});
                                window.location.reload();
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 1000});
                            }
                        })
                    }
                );
            },
            /*------ 导出excel ok ----------*/
            exportEmployee: function () {
                let _this = this;
                let employee_num = _this.em_num;
                if(employee_num > 0){
                    if (_this.selected.length <= 0) {
                        layer.confirm('您没有选择要导出的员工，确定要导出全部员工信息吗？', {icon: 3, title: '提示'},
                            function () {
                                layer.closeAll();
                                window.location.href = "{{ route('employee.export', ['ids'=>'all']) }}";
                            }
                        );
                    } else {
                        layer.confirm('确定导出当前已选择的员工信息？', {icon: 3, title: '提示'},
                            function () {
                                layer.closeAll();
                                window.location.href = "/book/employee/export?ids=" + _this.selected;
                            }
                        );
                    }
                }else{
                    layer.confirm('当前公司没有员工，不可执行导出。请先添加员工再执行导出！', {icon: 3, title: '提示'},
                        function () {
                            layer.closeAll();
                        }
                    );
                }
            },
            /*------ 导入excel do ----------*/
            importEmployee: function () {
                var _this = this;
                layer.open({
                    type: 1,
                    zIndex: 900,
                    title: '批量导入员工',
                    area: ['500px', '230px'],
                    shift: 0,
                    scrollbar: false,
                    skin: 'components',
                    content: contentSelector,
                    btn: ["确认", "取消"],
                    yes: function (index) {
                        var jnfilePath = $("input[name=cfs_file]").val();
                        var filePath = _this.cfs_file_url;

                        var jnfileName = jnfilePath.indexOf("\\") != -1 ? (jnfileName = jnfilePath.substring(jnfilePath.lastIndexOf("\\") + 1)) : jnfilePath;
                        var jntypeName = jnfileName.substring(jnfileName.lastIndexOf(".") + 1);

                        if (!jntypeName) {
                            //layer.close(index);
                            layer.msg('请选择上传文件！', {icon: 2, time: 2000});
                            return false;
                        }

                        if (jntypeName) {
                            if (jntypeName.toLowerCase() != "xls" && jntypeName.toLowerCase() != "xlsx") {
                                layer.close(index);
                                layer.msg('文件格式错误，请选择扩展名为xls或xlsx的文件！', {icon: 2, time: 2000});
                                return false;
                            }
                        }

                        $('#uploadDataForm').attr('target', "uploadIframe");

                        //var cfs_file = document.form["uploadDataForm"].cfs_file.files[0];

                        var data = {
                            _token: "{{ csrf_token() }}",
                            cfs_file: jnfilePath,
                            file_path: filePath,
                            file_Name: jnfileName,
                            type_Name: jntypeName,
                            cid: '{{$CompanyId}}',
                        };
                        _this.$http.post('{{ route('employee.api_import') }}', data).then(function (response) {
                            //console.log(data);
                            if (response.body.status == 'success') {
                                layer.closeAll();
                                layer.msg(response.body.msg, {icon: 1, time: 2000});

                                // 更新员工列表页面
                                this.getEmployeeList();
                                //window.location.reload();
                            } else {
                                layer.closeAll();
                                layer.msg(response.body.msg, {icon: 2, time: 2000});
                            }
                        })
                    },
                    success: function () {
                        $("#uploadDataForm input[name=cfs_file]").on("change", function (event) {
                            $(this).prev().prev().val(this.value.replace(/^.+?\\([^\\]+?)(\.[^\.\\]*?)?$/gi, "$1") + this.value.replace(/.+\./, "."));
                        });

                        $("#chk_a").hover(function () {
                            $(this).addClass("cur");
                        }, function () {
                            $(this).removeClass("cur");
                        });

                        // 员工导入相关处理
                        $("#chk_a").click(function () {
                            $("#cfs_file").click();
                        });

                        $("#cfs_file").on("change", function () {
                            var $file = $(this);
                            var fileObj = $file[0];

                            var windowURL = window.URL || window.webkitURL;
                            var dataURL;

                            if (fileObj && fileObj.files && fileObj.files[0]) {
                                dataURL = windowURL.createObjectURL(fileObj.files[0]);
                            } else {
                                dataURL = $file.val();
                            }

                            _this.cfs_file_url = dataURL;
                        });
                    }
                });
            },
            /*------ 编辑单个人员 ok ----------*/
            professEdit: function (id) {
                window.location.href = "/book/employee/" + id + "/edit";
            },
            /*------ 切换人员状态 ok ----------*/
            changeStatus: function (index, name, id, status, list) {
                var _this = this;
                var item = index;
                layer.confirm('确定要更改员工 ' + name + ' 人员状态吗？', {icon: 3, title: '提示'},
                    function () {
                        _this.$http.post('{{ route('employee.api_change_status') }}', {
                            _token: "{{csrf_token()}}",
                            id: id,
                            status: status
                        }).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.msg(response.body.msg, {icon: 1, time: 1000});
                                list.personnelState = response.body.sid;
                                // 鉴于刷新用户体验不佳，以下行刷新页面，后续可以优化成只修改点击按钮的文本及相关 20180706
                                //window.location.reload();
                            } else {
                                layer.msg(response.body.msg, {icon: 2, time: 1000});
                            }
                        })
                    }
                );
            },
            /*------ 搜索 ok ----------*/
            doSearch: function () {
                var sv = this.sv;
                var tit = this.getProfessName;
                var dep_id = '{{$DepartmentId}}';
                window.location.href = "/book/department/search?dep_id=" + dep_id + "&sv=" + sv + "&tit=" + tit + "&do=so";
            },
            /*------ 批量调整部门 ok ----------*/
            changeDepartment: function () {
                var _this = this;
                if (_this.selected.length <= 0) {
                    layer.msg("请选择要调整部门的人员！", {icon: 2, time: 2000});
                    return false;
                }
                layer.open({
                    type: 1,
                    zIndex: 900,
                    title: '批量调整部门',
                    area: ['400px', '230px'],
                    shift: 0,
                    scrollbar: false,
                    skin: 'components',
                    content: contentDepartment,
                    btn: ["确认", "取消"],
                    yes: function (index) {
                        var dep_id = $("#department_select option:selected").val();

                        var data = {
                            _token: "{{ csrf_token() }}",
                            cid: '{{$CompanyId}}',
                            dep_id: dep_id,
                            ids: _this.selected
                        };
                        _this.$http.post('{{ route('employee.api_change_department') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                layer.closeAll();
                                layer.msg(response.body.msg, {icon: 1, time: 2000});

                                // 更新员工列表页面
                                this.getEmployeeList();
                            } else {
                                layer.closeAll();
                                layer.msg(response.body.msg, {icon: 2, time: 2000});
                            }
                        })
                    },
                    success: function () {
                        var data = {
                            _token: "{{ csrf_token() }}",
                            cid: '{{$CompanyId}}',
                        };
                        _this.$http.post('{{ route('employee.api_get_department') }}', data).then(function (response) {
                            if (response.body.status == 'success') {
                                $("#department_select").html(response.body.options);
                            } else {
                                $("#department_select").html(response.body.options);
                            }
                        })
                    }
                });
            },
            /*------ 获取人员列表 ok ---------*/
            getEmployeeList: function () {
                var dep_id = '{{$DepartmentId}}';
                var page = '{{$request->page}}';
                this.$http.post("/book/employee/list", {
                    _token: "{{csrf_token()}}",
                    'dep_id': dep_id,
                    'page': page
                }).then(function (response) {
                    response = response.body;
                    this.professorTables = response.data.items;
                    this.em_num = response.data.employee_num;
                    layer.load(2, {shade: false, time: 500});
                })
            },
        },
        /*------ 复选框 ok ---------*/
        /*watch: {
            'selected': {
                handler: function() {
                    if (this.selected.length === this.professorTables.length - 1) {
                        this.checked = true;
                    } else {
                        this.checked = false;
                    }
                },
                deep: true
            }
        },*/
        watch: {
            "selected": function () {
                if (this.selected.length == this.professorTables.length) {
                    this.checked = true
                } else {
                    this.checked = false
                }
            }
        }
    })
</script>
<script>
    // 设置菜单树部门选中状态
    $(document).ready(function () {
        var dep_name = '{{$DepartmentName}}';
        $("#treeDemo_1_ul li").find("a[title=" + dep_name + "]").addClass("dep_cur");
    });

    // 切换会员状态 VUE处理

    // 添加人员
    $(".btn_employee_add").click(function () {
        window.location.href = "/book/employee/create";
    });

    // 部门主菜单点击
    $("#treeDemo_1_a").click(function () {
        window.location.href = "/book/department";
    });

    // 切换部门、人员
    function change_employee_list(obj) {
        var vid = obj;
        if (vid.length == 0) {
            return false;
        } else {
            window.location.href = "/book/department/?id=" + vid;
        }
    }

    // 薪酬 －> 添加部门
    $(".btn_department_add").click(function () {
        layer.prompt({
            formType: 3,
            value: '',
            title: '新增部门'
        }, function (value, index) {
            if (value.length >= 2 && value.length < 20) {
                $.ajax({
                    type: 'post',
                    url: '/book/department/api_add',
                    data: {'name': value},
                    success: function (res) {
                        if (res.status == 'success') {
                            layer.close(index);
                            layer.msg(res.msg, {icon: 1, time: 2000});
                            window.location.reload();
                            return true;
                        } else {
                            layer.close(index);
                            layer.msg(res.msg, {icon: 2, time: 2000});
                            return false;
                        }
                    },
                    error: function () {
                        layer.close(index);
                        layer.msg('新增部门失败', {icon: 2, time: 2000});
                        return false;
                    }
                });
            } else {
                layer.close(index);
                layer.msg('部门名称字符长度2~20', {icon: 2, time: 2000});
            }
            ;
        });
    });

    // 薪酬 －> 编辑部门
    $(".btn_department_edit").click(function () {
        var dep_name = '{{$DepartmentName}}';
        if (dep_name.length > 0) {
            layer.prompt({
                formType: 3,
                value: dep_name,
                title: '编辑部门'
            }, function (value, index) {
                if (value.length >= 2 && value.length < 20) {
                    $.ajax({
                        type: 'post',
                        url: '/book/department/api_edit',
                        data: {'name': value, 'id': '{{$DepartmentId}}'},
                        success: function (res) {
                            if (res.status == 'success') {
                                layer.close(index);
                                layer.msg(res.msg, {icon: 1, time: 2000});
                                window.location.reload();
                                return true;
                            } else {
                                layer.close(index);
                                layer.msg(res.msg, {icon: 2, time: 2000});
                                return false;
                            }
                        },
                        error: function () {
                            layer.close(index);
                            layer.msg('编辑部门失败', {icon: 2, time: 2000});
                            return false;
                        }
                    });
                } else {
                    layer.close(index);
                    layer.msg('部门名称字符长度2~20', {icon: 2, time: 2000});
                }
                ;
            });
        } else {
            layer.msg('请选择部门后再进行编辑操作！', {icon: 2, time: 2000});
        }
    });

    // 薪酬 －> 删除部门
    $(".btn_department_del").click(function () {
        var dep_name = '{{$DepartmentName}}';
        if (dep_name.length > 0) {
            layer.confirm(
                '确认要删除部门 ' + dep_name + ' 吗？', {icon: 3, title: '提示'},
                function () {
                    $.ajax({
                        type: 'post',
                        url: '/book/department/api_del',
                        data: {'name': dep_name, 'id': '{{$DepartmentId}}'},
                        success: function (res) {
                            if (res.status == 'success') {
                                layer.msg(res.msg, {icon: 1, time: 2000});
                                window.location.href = "{{ route('department.index') }}";
                                return true;
                            } else {
                                layer.msg(res.msg, {icon: 2, time: 2000});
                                return false;
                            }
                        },
                        error: function () {
                            layer.msg('删除部门失败', {icon: 2, time: 2000});
                            return false;
                        }
                    });
                }
            );
        } else {
            layer.msg('请选择部门后再进行删除操作！', {icon: 2, time: 2000});
        }
    });
</script>
@endsection
