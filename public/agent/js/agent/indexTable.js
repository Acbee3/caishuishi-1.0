layui.use('table', function(){
    var table = layui.table;
    table.render({
        elem: '#index'
        /*-----后台获取数据接口------*/
        /*,url:'http://localhost:63342/system/table.json'*/
        ,cols: [[
            {field:'id', width:220,align:'center', title: '编码'}
            ,{field:'username', width:600, align:'center',title: '企业名称'}
            ,{field:'current', width:400,align:'center', title: '当前账期'}
            ,{field:'handle', minWidth:200,align:'center', title: '操作', toolbar: '#barDemo'}
        ]]
        ,page: true
        ,height: '440'
        ,cellMinWidth: 120
        ,limit:10
        ,data: [
            {
                "id": "0024",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "handel": ""
            }
        ]
    })
    //监听工具条
    table.on('tool(demo)', function(obj){
        var data = obj.data;
        if(obj.event === 'detail'){
            /*layer.msg('ID：'+ data.id + ' 的查看操作');*/
           /*window.open('test1.html')*/
        }else if (obj.event === 'del') {
            layer.confirm('确定删除此行吗?', function (index) {
                obj.del();
                layer.close(index);
            });
        }
    })
})

