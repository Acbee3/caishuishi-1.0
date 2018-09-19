layui.use('table', function(){
    var table = layui.table;
    table.render({
        elem: '#customerList'
        /*-----后台获取数据接口------*/
        /*,url:'http://localhost:63342/system/table.json'*/
        ,cols: [[
            {type: 'checkbox',width: '5%',}
            ,{field:'id', width: '10%',align:'center', title: '编号'}
            ,{field:'username',width: '15%',  align:'center',title: '公司名称'}
            ,{field:'payer',width: '10%',align:'center', title: '纳税人资格'}
            ,{field:'financial',width: '10%',align:'center', title: '财务联系人'}
            ,{field:'financialContact',width: '10%', align:'center', title: '财务联系方式'}
            ,{field:'address', width: '20%',align:'center', title: '营业地址'}
            ,{field:'handle', width: '25%',align:'center', title: '操作', toolbar: '#customerDemoList'}
        ]]
        ,page: true
        ,height: 'full'
        ,cellMinWidth: 120
        ,limit:10
        /*------mock数据-----------*/
        ,data: [
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "payer": "增值税一般纳税人",
                "financial": "小明",
                "financialContact": "01234567890",
                "address": "苏州工业园区创意产业园1座808"
            },
        ]
    })
    table.render({
        elem: '#customer'
        /*-----后台获取数据接口------*/
        /*,url:'http://localhost:63342/system/table.json'*/
        ,cols: [[
            {type: 'checkbox',width:"100"}
            ,{field:'id', align:'center', title: '编号',width:200,}
            ,{field:'username',  align:'center',title: '企业名称',width:500}
            ,{field:'status',align:'center', title: '企业状态',width:300}
            ,{field:'stopDate', align:'center', title: '停用账期',width:300}
            ,{field:'handle', align:'center', title: '操作', toolbar: '#customerDemo',minWidth:400}
        ]]
        ,page: true
        ,height: 'full'
        ,cellMinWidth: 120
        ,limit:10
        /*-----模拟数据-------*/
        ,data: [
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "current": "2018-04-2",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
            {
                "id": "0021",
                "username": "苏州财税狮网络科技有限公司",
                "status": "停用",
                "stopDate": "2018-04-2"
            },
        ]
    })
    //监听工具条
    table.on('tool(demo)', function(obj) {
        var data = obj.data;
        if (obj.event === 'detail') {
            /*layer.msg('ID：'+ data.id + ' 的查看操作');*/
            window.open('test1.html')
        } else if (obj.event === 'del') {
            layer.confirm('确定删除此行吗?', function (index) {
                obj.del();
                layer.close(index);
            });
        }
    })
})
