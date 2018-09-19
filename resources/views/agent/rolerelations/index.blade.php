@extends('agent.rolerelations.app_rolerelations')

@section('content')
    <div class="container_agerant">

        <div class="officerContainer">
            <div class="officerLeft">
                <p class="text">角色</p>
                <div class="officeBtn" style="display: none">
                    <input type="checkbox" id="callbackTrigger" checked style="display:none"/>
                    <a id="addLeaf" href="#" title="新增" onclick="return false;" class="officerNewadd treeNewAdd" >新增</a>
                    <a id="edit" href="#" title="修改" onclick="return false;" class="officerNewadd treeNewAdd" >修改</a>
                    <a id="remove" href="#" title="删除" onclick="return false;" class="officerNewadd officerNewaddDel" >删除</a>
                </div>
                <div class="officeTree" id="officeTree" style="display: none">
                    <ul id="treeDemo" class="ztree"></ul>
                </div>

                <div class="officeBtn">
                    <a id="addnewrole" href="javascript:void(0);" title="新增" class="officerNewadd treeNewAdd" >新增</a>
                </div>
                <div class="base_roles_list">
                    <h2>{{$agent_name}}</h2>
                    <ul>
                        @foreach($roles as $key => $v)
                            <li>
                                <a href="javascript:void(0);" vid="{{$v->id}}" class="change_role role_name @if($v->id == $id)role_cur @endif">{{$v->role_name}}</a>
                                @if($v->add_by == 'agent')
                                    <a href="javascript:void(0);" vid="{{$v->id}}" class="del_role opt_btns">删除</a>
                                    <a href="javascript:void(0);" vid="{{$v->id}}" class="edit_role opt_btns">修改</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="officerRight">
                <p class="officerCustomer">角色权限</p>
                <div class="officerTable" id="officerTable">
                    <table>
                        <thead>
                        <tr>
                            <th class="width40">菜单/功能</th>
                            <th class="width20">查看</th>
                            <th class="width20">操作</th>
                            <th class="width20">无权限</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- 三级循环调用菜单处理角色权限 -->
                        @foreach($menu_data as $key => $v)
                        <tr data_row_id="{{$key+1}}">
                            <td style="text-align: left; text-indent: 2em;" vid="{{$v['id']}}">{{$v['action_name']}}</td>
                            @if ($v['child_arr'])
                                <td></td><td></td><td></td>
                            @else
                                <td><input name="permission{{$v['id']}}" value="0" onclick="saveAuth({{$v['id']}},1,1,0,{{$id}})" type="radio" {{$v['disabled0']}} @if($v['val'] == 0) checked @endif></td>
                                <td><input name="permission{{$v['id']}}" value="1" onclick="saveAuth({{$v['id']}},1,1,1,{{$id}})" type="radio" {{$v['disabled1']}} @if($v['val'] == 1) checked @endif></td>
                                <td><input name="permission{{$v['id']}}" value="2" onclick="saveAuth({{$v['id']}},1,1,2,{{$id}})" type="radio" {{$v['disabled2']}} @if($v['val'] == 2) checked @endif></td>
                            @endif
                        </tr>
                            @if ($v['child_arr'])
                                @foreach($v['child_arr'] as $key => $v1)
                                    <tr data_row_id="c1_{{$v1['id']}}_{{$key+1}}">
                                        <td style="text-align: left; text-indent: 4em;">{{$v1['action_name']}}</td>
                                        @if ($v1['child_arr'])
                                            <td></td><td></td><td></td>
                                        @else
                                            <td><input name="permission{{$v1['id']}}" value="0" onclick="saveAuth({{$v1['id']}},1,1,0,{{$id}})" type="radio" {{$v1['disabled0']}} @if($v1['val'] == 0) checked @endif></td>
                                            <td><input name="permission{{$v1['id']}}" value="1" onclick="saveAuth({{$v1['id']}},1,1,1,{{$id}})" type="radio" {{$v1['disabled1']}} @if($v1['val'] == 1) checked @endif></td>
                                            <td><input name="permission{{$v1['id']}}" value="2" onclick="saveAuth({{$v1['id']}},1,1,2,{{$id}})" type="radio" {{$v1['disabled2']}} @if($v1['val'] == 2) checked @endif></td>
                                        @endif
                                    </tr>
                                    @if ($v1['child_arr'])
                                        @foreach($v1['child_arr'] as $key => $v2)
                                            <tr data_row_id="c2_{{$v2['id']}}_{{$key+1}}">
                                                <td style="text-align: left; text-indent: 6em;">{{$v2['action_name']}}</td>
                                                @if ($v1['child_arr'])
                                                    <td></td><td></td><td></td>
                                                @else
                                                    <td><input name="permission{{$v2['id']}}" value="0" onclick="saveAuth({{$v2['id']}},1,1,0,{{$id}})" type="radio" {{$v2['disabled0']}} @if($v2['val'] == 0) checked @endif></td>
                                                    <td><input name="permission{{$v2['id']}}" value="1" onclick="saveAuth({{$v2['id']}},1,1,1,{{$id}})" type="radio" {{$v2['disabled1']}} @if($v2['val'] == 1) checked @endif></td>
                                                    <td><input name="permission{{$v2['id']}}" value="2" onclick="saveAuth({{$v2['id']}},1,1,2,{{$id}})" type="radio" {{$v2['disabled2']}} @if($v2['val'] == 2) checked @endif></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection