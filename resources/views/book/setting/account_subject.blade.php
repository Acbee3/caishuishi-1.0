@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/common/css/table.css">
    <link rel="stylesheet" href="/css/book/accountBalance.css?v=2018083001">
@endsection

@section('content')
    <div class="account" id="account">
        <div class="accountSearch">
            <form action="{{ route('account_subject.index') }}" id="serach_form">
            <input type="text" name="search" placeholder="请输入科目编码或名称" value="{{ $data['old_search'] }}">
            {{--<i class="icon iconfont icon-search" id="search_button"></i>--}}
                <span class="searchBtn" id="search_button">搜索</span>
            </form>
        </div>
        <div class="accountTable" id="accountTable">
            <div class="fixTableHeader">
                <table border="0">
                    <thead>
                    <tr>
                        <th class="width10">科目编码</th>
                        <th class="width30">
                            科目名称
                            <div class="dataIndex">
                                <span class="iconfont" style="vertical-align: middle; cursor: pointer;">&#xe625;</span>
                                <ul class="anchorList">
                                    <li data-id="1">
                                        <a href="#kjkm1">资产</a>
                                    </li>
                                    <li data-id="2">
                                        <a href="#kjkm2">负债</a>
                                    </li>
                                    <li data-id="3">
                                        <a href="#kjkm3">权益</a>
                                    </li>
                                    <li data-id="4">
                                        <a href="#kjkm4">成本</a>
                                    </li>
                                    <li data-id="5">
                                        <a href="#kjkm5">损益</a>
                                    </li>
                                </ul>
                            </div>
                        </th>
                        <th class="width10">余额方向</th>
                        {{--<th class="width10">数量核算</th>--}}
                        {{--<th class="width10">外币</th>--}}
                        <th class="width10">状态</th>
                        <th class="width15">操作</th>
                    </tr>
                    </thead>
                </table>
            </div>
            <div class="tableScroll" id="tableScroll" style="overflow:auto">
                <table border="0">
                    <tbody>
                    @foreach($data['list'] as $v)
                        <tr subject_id={{$v['id']}} subject_level={{$v['level']}} subject_name={{$v['name']}} subject_number={{$v['number']}}
                                subject_status={{$v['status']}} balance_direction={{$v['balance_direction']}} company_id={{$v['company_id']}}
                                pid={{$v['pid']}} level={{$v['level']}} type={{$v['type']}}>
                            <td class="width10">{{ $v['number'] or '-' }}</td>
                            <td class="width30">{{ str_repeat('　　', $v['level']) }}{{$v['name']}}</td>
                            <td class="width10">{{ $v['balance_direction'] or '-' }}</td>
                            <td class="width10"><a href="javascript:;" class="orangeBtn" @if($v['status'] == 0) style="background: #ccc;color: #333" @endif>{{ $status[$v['status']] or '-' }}</a></td>
                            <td class="width15">
                                <div class="fs0">
                                    <a href="javascript:;" class="iconfont icon-xinzeng borderBlure"></a>
                                    <a href="javascript:;" class="iconfont icon-bianji borderBlure"></a>
                                    <a href="javascript:;" class="iconfont icon-shanchu borderBlure del"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
        layui.use(['layer', 'jquery'], function () {
            //搜索
            $('#search_button').click(function () {
                $('#serach_form').submit();
            });

            //状态修改
            $('.orangeBtn').click(function () {
                $this = $(this);
                id = $this.closest('tr').attr('subject_id');
                if ($this.html() == '已启用'){
                    status = '{{ \App\Models\AccountSubject::FREEZR }}';
                } else {
                    status = '{{ \App\Models\AccountSubject::USED }}';
                }
                $.ajax({
                    type : 'post',
                    url  : '{{ route('account_subject.freeze') }}',
                    data : {'id':id,'status':status},
                    success:function (data) {
                        if (data.success == '操作成功') {
                            if ($this.html() == '已启用'){
                                $this.css({"background":"#ccc","color":"#333"});
                                $this.html('已冻结')
                            } else {
                                $this.css({"background":"#ec7638","color":"#fff"});
                                $this.html('已启用')
                            }
                            layer.msg(data.success, {icon:1,time:1000});
                        } else {
                            layer.msg(data.success, {icon:2,time:1000});
//                            window.location.reload();
                        }
                    },
                    error:function () {
                        layer.msg('操作失败', {icon:2,time:1000});
                    }
                });
            });

            $('.icon-xinzeng').click(function () {
                $this = $(this);
                id = $this.closest('tr').attr('subject_id');
                name = $this.closest('tr').attr('subject_name');
                number = $this.closest('tr').attr('subject_number');
                balance_direction = $this.closest('tr').attr('balance_direction');
                status = $this.closest('tr').attr('subject_status');
                company_id = $this.closest('tr').attr('company_id');
                pid = $this.closest('tr').attr('pid');
                level = $this.closest('tr').attr('level');
                level_new = Number(level)+Number(1);
                type = $this.closest('tr').attr('type');
                if (balance_direction == '借'){
                    balance_direction_j = true;
                    balance_direction_d = false;
                }else{
                    balance_direction_j = false;
                    balance_direction_d = true;
                }
                layer.open({
                    type : 1,
                    title : '新增科目',
                    skin: 'subjectNewAdd',
                    content : '<div>' +
                    '<form action="" id="create_subject">'+
                    '<input type="hidden" name="company_id" value='+company_id+'>' +
                    '<input type="hidden" name="pid" value='+id+'>' +
                    '<input type="hidden" name="type" value='+type+'>' +
                    '<input type="hidden" name="level" value='+level_new+'>' +
//                    '<div class="subject_item menu"><label>科目编码:</label><input type="text" name="number"></div>' +
                    '<div class="subject_item menu"><label>科目名称:</label><input type="text" name="name"></div>' +
                    '<div class="subject_item wrapper"><label>余额方向:</label><input type="radio" name="balance_direction" value="借" '+(balance_direction_j?'checked':'')+'>借' +
                    '<input type="radio" name="balance_direction" value="贷" '+(balance_direction_d?'checked':'')+'>贷</div>' +
                    '<div class="subject_item wrapper"><label>状态:</label><input type="radio" name="status" value="1" checked>启用' +
                    '<input type="radio" name="status" value="0">冻结</div>' +
                    '</form>' +
                    '</div>',
                    btn: ['保存', '取消'],
                    yes: function(index, layero){
                        formdata = $('#create_subject').serialize();
                        console.log(formdata);
                        $.ajax({
                            type : 'post',
                            url  : 'account_subject',
                            data : formdata,
                            success:function (data) {
                                if (data.success == '操作成功') {
                                    layer.msg(data.success, {icon:1,time:1000});
                                    window.location.reload();
                                } else {
                                    layer.msg(data.success, {icon:2,time:1000});
                                }
                            },
                            error:function () {
                                layer.msg('操作失败', {icon:2,time:1000});
                            }
                        });
                    },
                    btn2: function(index, layero){

                    }
                });
            });

            $('.icon-bianji').click(function () {
                $this = $(this);
                id = $this.closest('tr').attr('subject_id');
                name = $this.closest('tr').attr('subject_name');
                number = $this.closest('tr').attr('subject_number');
                balance_direction = $this.closest('tr').attr('balance_direction');
                status = $this.closest('tr').attr('subject_status');
                company_id = $this.closest('tr').attr('company_id');
                pid = $this.closest('tr').attr('pid');
                level = $this.closest('tr').attr('level');
                layer.open({
                    type : 1,
                    title : '编辑科目',
                    skin: 'subjectNewAdd',
                    area: ['310px', 'auto'],
                    content : '<div>' +
                            '<form action="" id="edit_subject">'+
                            '<input type="hidden" name="company_id" value='+company_id+'>' +
                            '<input type="hidden" name="pid" value='+pid+'>' +
                            '<input type="hidden" name="level" value='+level+'>' +
                            '<div class="subject_item menu"><label>科目编码:</label><input type="text" name="number" value='+number+' disabled></div>' +
                            '<div class="subject_item menu"><label>科目名称:</label><input type="text" name="name" value='+name+'></div>' +
                            '<div class="subject_item wrapper"><label>余额方向:</label><input type="radio" name="balance_direction" value="借">借' +
                            '<input type="radio" name="balance_direction" value="贷">贷</div>' +
                            '<div class="subject_item wrapper"><label>状态:</label><input type="radio" name="status" value="1">启用' +
                            '<input type="radio" name="status" value="0">冻结</div>' +
                            '</form>' +
                            '</div>',
                    btn: ['保存', '取消'],
                    yes: function(index, layero){
                        formdata = $('#edit_subject').serialize();
                        $.ajax({
                            type : 'patch',
                            url  : 'account_subject/'+id,
                            data : formdata,
                            success:function (data) {
                                if (data.success == '操作成功') {
                                    layer.msg(data.success, {icon:1,time:1000});
                                    window.location.reload();
                                } else {
                                    layer.msg(data.success, {icon:2,time:1000});
                                }
                            },
                            error:function () {
                                layer.msg('操作失败', {icon:2,time:1000});
                            }
                        });
                    },
                    btn2: function(index, layero){

                    }
                });
                $('input[name=balance_direction]').each(function () {
                    if (balance_direction == $(this).val()){
                        $(this).attr('checked',true);
                    }
                });
                $('input[name=status]').each(function () {
                    if (status == $(this).val()){
                        $(this).attr('checked',true);
                    }
                });
            });

            $('.icon-shanchu').click(function () {
                $this = $(this);
                id = $this.closest('tr').attr('subject_id');
                layer.confirm(
                    '科目删除后无法恢复，您确定要删除该科目吗？', {icon: 3, title:'提示',skin:'subjectNewAdd'},
                    function () {
                        $.ajax({
                            type : 'delete',
                            url  : 'account_subject/'+id,
                            data : {'id':id},
                            success:function (data) {
                                if (data.success == '操作成功') {
                                    layer.msg(data.success, {icon:1,time:1000});
                                    window.location.reload();
                                } else {
                                    layer.msg(data.success, {icon:2,time:1000});
                                    window.location.reload();
                                }
                            },
                            error:function () {
                                layer.msg('操作失败', {icon:2,time:1000});
                            }
                        });
                    }
                );
            });

            $('#accountTable .dataIndex').hover(function () {
                $('.anchorList').show();
            }, function () {
                $('.anchorList').hide();
            })

            $('.anchorList').click(function () {
                $('.anchorList').css("display", "none")
            })


            for(var i = 0; i < $('#tableScroll tr').length; i++) {
                    if ($('#tableScroll tr').eq(i)[0].getAttribute('subject_number').indexOf(1) == 0 && $('#tableScroll tr').eq(i)[0].previousElementSibling == null) {
                        $('#tableScroll tr').eq(i)[0].setAttribute('id', 'kjkm1');
                        // console.log($('#tableScroll tr').eq(i)[0].previousElementSibling.getAttribute('subject_number'))
                        // console.log($($('#tableScroll tr').eq(i)[0]).find('.anchorPoint'));
                    } else if ($('#tableScroll tr').eq(i)[0].getAttribute('subject_number').indexOf(2) == 0 && $('#tableScroll tr').eq(i)[0].previousElementSibling.getAttribute('subject_number').indexOf(1) == 0) {
                        // console.log($('#tableScroll tr').eq(i)[0].previousElementSibling.getAttribute('subject_number').indexOf(1) == 0)
                        $('#tableScroll tr').eq(i)[0].setAttribute('id', 'kjkm2');
                    } else if ($('#tableScroll tr').eq(i)[0].getAttribute('subject_number').indexOf(3) == 0 && $('#tableScroll tr').eq(i)[0].previousElementSibling.getAttribute('subject_number').indexOf(2) == 0) {
                        $('#tableScroll tr').eq(i)[0].setAttribute('id', 'kjkm3');
                    } else if ($('#tableScroll tr').eq(i)[0].getAttribute('subject_number').indexOf(4) == 0 && $('#tableScroll tr').eq(i)[0].previousElementSibling.getAttribute('subject_number').indexOf(3) == 0) {
                        $('#tableScroll tr').eq(i)[0].setAttribute('id', 'kjkm4');
                    } else if ($('#tableScroll tr').eq(i)[0].getAttribute('subject_number').indexOf(5) == 0 && $('#tableScroll tr').eq(i)[0].previousElementSibling.getAttribute('subject_number').indexOf(4) == 0) {
                        $('#tableScroll tr').eq(i)[0].setAttribute('id', 'kjkm5');
                    }
                    // console.log($('#tableScroll tr').eq(i)[0].getAttribute('subject_number'))
                    // console.log($('#tableScroll tr').eq(i)[0])
                }



        });
    </script>
    <script src="/js/book/table.js"></script>
@endsection