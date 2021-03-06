
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
        onRemove: onRemove,
        onRename:onRename,//编辑后触发，用于操作后台
        onClick: onClick,
    }
};

var zNodes =[
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
    $.post("/agent/department/del",
        {
            department_id:treeNode.id,
        },
        function(result){
            console.log(result);
            if(result.result =='error'){
                alert("部门删除失败！");
            }
            setNodes();
        });
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

function onRename(e,treeId,treeNode,isCancel){

    $.post("/agent/department/create",
        {
            id:treeNode.id,
            pid:treeNode.pId,
            level:treeNode.level,
            department_name:treeNode.name,
        },
        function(result){
            console.log(result);
            if(result.result =='error'){
                alert("部门更新失败！");
            }
            setNodes();
    });


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

    if (nodes.length == 0) {
        alert("请先选择公司再新增");
        return;
    }

    if (treeNode) {
        treeNode = zTree.addNodes(treeNode, {id:(100 + newCount+'-a'), pId:treeNode.id, isParent:isParent, name:"部门" + (newCount++)});
        zTree.editName(treeNode[0]);
        treeNode1 = zTree.getSelectedNodes();
    } else {
        treeNode = zTree.addNodes(null, {id:(100 + newCount), pId:1, isParent:isParent, name:"部门" + (newCount++)});
    }


    /* if (treeNode) {
     zTree.editName(treeNode[0]);
     } else {
     alert("子节点被锁定，无法增加子节点");
     }*/
};

function edit() {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
        nodes = zTree.getSelectedNodes(),
        treeNode = nodes[0];
    if (treeNode.level == 0) {
        alert("公司名称不能编辑");
        return;
    }
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
    if (treeNode.level == 0) {
        alert("公司名称不能编辑");
        return;
    }
    var callbackFlag = $("#callbackTrigger").attr("checked");
    zTree.removeNode(treeNode, callbackFlag);
};

function setNodes() {
    $.post("/agent/department/index",{},function(result){
        zNodes = result.data;
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    });

}


function onClick(e){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
        nodes = zTree.getSelectedNodes(),
        treeNode = nodes[0];

    userList();


}

$(document).ready(function(){
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    $("#addParent").bind("click", {isParent:true}, add);
    $("#addLeaf").bind("click", {isParent:false}, add);
    $("#edit").bind("click", edit);
    $("#remove").bind("click", remove);
    setNodes();

});


function userList(data) {
    new Vue({
        el: "#app",
        data: {
            userList:[],
        },
        created: function () {
            this.getData();
        },
        methods: {
            getData:function (){
                this.$http.post("/agent/user/agent-index",{data}).then(function(response){
                    response = response.body
                    this.userList =  response.data.items
                })
            }
        }
    })
}
