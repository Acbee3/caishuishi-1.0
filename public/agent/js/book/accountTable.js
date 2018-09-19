layui.use(['table','jquery'], function(){
    var table = layui.table;
    table.render({
        elem: '#accountTable'
        /*-----后台获取数据接口------*/
        /*,url:'http://localhost:63342/system/table.json'*/
        ,cols: [[
            {field:'id', width:120,align:'center', title: '科目编码'}
            ,{field:'username', width:300, align:'center',title: '科目名称'}
            ,{field:'direction', width:100,align:'center', title: '余额方向'}
            ,{field:'quantitative', width:200,align:'center', title: '数量核算'}
            ,{field:'currency', width:200,align:'center', title: '外币'}
            ,{field:'status', width:200,align:'center', title: '状态',toolbar: '#status' }
            ,{field:'handle', minWidth:200,align:'center', title: '操作', toolbar: '#accountList'}
        ]]
        ,page: true
        /*,height: 'full'*/
        ,cellMinWidth: 120
        ,limit:15
        ,data: [
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "direction": "借",
                "quantitative": "100000",
                "currency": "不知道啥",
                "status": ""
            },
        ]
    })
    //监听工具条
    table.on('tool(demo)', function(obj){
       var data = obj.data;
        if(obj.event === 'newStatus'){
            /*layer.msg('ID：'+ data.id + ' 的查看操作');*/
            if($(this).html() === '已启用'){
                $(this).html('已冻结')
                $(this).css({"background": "#ccc","color": "#fff"})
            }else{
                $(this).html('已启用')
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
