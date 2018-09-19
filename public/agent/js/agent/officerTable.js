layui.use(['table','jquery'], function(){
    var table = layui.table;
    table.render({
        elem: '#accountTable'
        /*-----后台获取数据接口------*/
        /*,url:'http://localhost:63342/system/table.json'*/
        ,cols: [[
            {field:'username', width:200,align:'center', title: '姓名'}
            ,{field:'login', width:200, align:'center',title: '登录名'}
            ,{field:'role', width:400,align:'center', title: '角色'}
            ,{field:'officer', width:300,align:'center', title: '部门'}
            ,{field:'status', width:200,align:'center', title: '状态',toolbar: '#status' }
            ,{field:'handle', minWidth:200,align:'center', title: '操作', toolbar: '#accountList'}
        ]]
        ,page: true
        /*,height: 'full'*/
        ,cellMinWidth: 120
        ,limit:15
        ,data: [
            {
                "username": "欧欧",
                "login": "caishuishi",
                "role": "会计,系统管理员",
                "officer": "南京小沛企业管理咨询有限公司",
                "status": ""
            }
        ]
    })
    //监听工具条
    table.on('tool(demo)', function(obj){
        var data = obj.data;
        if(obj.event === 'newStatus'){
            /*layer.msg('ID：'+ data.id + ' 的查看操作');*/
            if($(this).html() === '正常'){
                $(this).html('禁用')
                $(this).css({"background": "#ccc","color": "#fff"})
            }else{
                $(this).html('正常')
                $(this).css({"background": "#ec7638","color": "#fff"})
            }
        }else if (obj.event === 'del') {
            layer.confirm('确定删除此行吗?', function (index) {
                obj.del();
                layer.close(index);
            });
        }
    })
})
