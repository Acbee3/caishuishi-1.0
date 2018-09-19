
var setting = {
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
            parent:true,
            leaf:true
        },
        simpleData: {
            enable: true
        }
    },
    callback: {
        beforeDrag: beforeDrag,
        beforeRemove: beforeRemove,
        beforeRename: beforeRename,
        onRemove: onRemove
    }
};

/*var zNodes =[];*/
var zNodes =[
    { id:1, pId:0, name:"苏州财税狮网络科技有限公司", open:true},
    { id:11, pId:1, name:"系统管理员"},
    { id:12, pId:1, name:"会计"},
];

var log, className = "dark";
function beforeDrag(treeId, treeNodes) {
    return false;
}
function beforeRemove(treeId, treeNode) {
    className = (className === "dark" ? "":"dark");
    showLog("[ "+getTime()+" beforeRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
    return confirm("确认删除-" + treeNode.name + " 吗？");
}
function onRemove(e, treeId, treeNode) {
    showLog("[ "+getTime()+" onRemove ]&nbsp;&nbsp;&nbsp;&nbsp; " + treeNode.name);
}
function beforeRename(treeId, treeNode, newName) {
    if (newName.length == 0) {
        alert("节点名称不能为空.");
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        setTimeout(function(){zTree.editName(treeNode)}, 10);
        return false;
    }
    return true;
}
function showLog(str) {
    if (!log) log = $("#log");
    log.append("<li class='"+className+"'>"+str+"</li>");
    if(log.children("li").length > 8) {
        log.get(0).removeChild(log.children("li")[0]);
    }
}
function getTime() {
    var now= new Date(),
        h=now.getHours(),
        m=now.getMinutes(),
        s=now.getSeconds(),
        ms=now.getMilliseconds();
    return (h+":"+m+":"+s+ " " +ms);
}

var newCount = 1;
function add(e) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
        isParent = e.data.isParent,
        nodes = zTree.getSelectedNodes(),
        treeNode = nodes[0];

        treeNode = zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, isParent:isParent, name:"角色组" + (newCount++)});
};
function edit() {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
        nodes = zTree.getSelectedNodes(),
        treeNode = nodes[0];
    if (nodes.length == 0) {
        alert("请先选择一个节点");
        return;
    }
    zTree.editName(treeNode);
};
function remove(e) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
        nodes = zTree.getSelectedNodes(),
        treeNode = nodes[0];
    if (nodes.length == 0) {
        alert("请先选择一个节点");
        return;
    }
    var callbackFlag = $("#callbackTrigger").attr("checked");
    zTree.removeNode(treeNode, callbackFlag);
};

//修改权限
function saveAuth(jsid, zyid, oldiskcz, iskcz, roid) {
    $.ajax({
        type: 'post',
        url: '/agent/rolerelations/changepermission',
        data: {'jsid':jsid,'zyid':zyid,'oldiskcz':oldiskcz,'iskcz':iskcz,'roid':roid},
        success:function (res) {
            //console.log(res);
            if(res.status == 'success'){
                layer.msg(res.msg, {icon:1,time:2000});
                return true;
            }else{
                layer.msg(res.msg, {icon:2,time:2000});
                return false;
            }
        },
        error:function () {
            layer.msg('授权失败', {icon:2,time:2000});
            return false;
        }
    });
}

// 添加角色
function sysaddrole() {
    layer.prompt({
        formType: 3,
        value: '',
        title: '新增角色'
    }, function(value, index){
        if( value.length >= 4 && value.length < 10 ){
            $.ajax({
                type: 'post',
                url: '/agent/rolerelations/addrolenew',
                data: {'name':value},
                success:function (res) {
                    if(res.status == 'success'){
                        layer.close(index);
                        layer.msg(res.msg, {icon:1,time:2000});
                        window.location.reload();
                        return true;
                    }else{
                        layer.close(index);
                        layer.msg(res.msg, {icon:2,time:2000});
                        return false;
                    }
                },
                error:function () {
                    layer.close(index);
                    layer.msg('新增失败', {icon:2,time:2000});
                    return false;
                }
            });
        }else{
            layer.close(index);
            layer.msg('角色名称字符长度6~20', {icon:2,time:2000});
        };
    });
}

// 添加授权客户
function authorization() {
    var rid = $(this).attr("rid");
    var cid = $(this).attr("cid");
    var set_name = 'names_'+rid+'_'+cid;
    var names = $(this).parent().find('span').html();

    $("#auth_info_box .auth_checked_con").html(names);

    var LayerIndex = layer.open({
        type: 1,
        zIndex: 900,
        title: '修改授权人员',
        area: ['800px', '400px'],
        shift: 0,
        scrollbar: false,
        skin: 'auth_open_box layui-layer-rim layui-anim layui-layer-page',
        content: $("#auth_info_box"),
        btn: ["确认", "取消"],
        success: function (index)
        {
            $.ajax({
                type: 'post',
                url: '/agent/authorizations/getagentusers',
                data: {'rid':rid},
                success:function (res) {
                    if(res.status == 'success'){
                        $("#auth_info_box .checkbox").html(res.data);
                        $("#auth_info_box").show();
                    }else{
                        layer.close(index);
                        layer.msg('请求数据异常，请稍后重试。', {icon:2,time:2000});
                    }
                },
                error:function () {
                    layer.close(index);
                    layer.msg('请求数据异常，请稍后重试。', {icon:2,time:2000});
                }
            });
        },
        yes: function (index, LayerIndex)
        {
            var ids= new Array();
            var i=0;
            $("input:checkbox[name='users']:checked").each(function() {
                ids[i++]=$(this).val();
            });

            $.ajax({
                type: 'post',
                url: '/agent/authorizations/authusers',
                dataType: "json",
                data: {'rid':rid, 'cid':cid, 'ids':ids},
                success:function (res)
                {
                    $("#auth_info_box .checkbox").html('');
                    $("#auth_info_box").hide();

                    if(res.status == 'success'){
                        layer.msg(res.msg, {icon:1,time:2000});
                        layer.close(index);

                        console.log(res);

                        $(".auth_data_list").find("."+set_name).html(res.names);
                    }else{
                        layer.msg(res.msg, {icon:2,time:2000});
                        layer.close(index);
                    }
                },
                error:function () {
                    layer.close(index);
                    layer.msg('授权失败', {icon:2,time:2000});
                }
            });
        }
    });
}

$(document).ready(function(){

    $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    $("#addParent").bind("click", {isParent:true}, add);
    $("#addLeaf").bind("click", {isParent:false}, add);
    $("#edit").bind("click", edit);
    $("#remove").bind("click", remove);

    $("#addnewrole").bind("click", sysaddrole);

    $(".auth_btn").bind("click", authorization);
});
