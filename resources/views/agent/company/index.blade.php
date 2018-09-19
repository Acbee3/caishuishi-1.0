@extends('agent.company.app_company')

@section('content')
    <div class="container_agerant">
        <div class="menu_bread" style="display: none;">
            <a href="{{url("/agent/system")}}" class="home">代账中心</a><i>&gt</i><a href="{{url("/agent/companies")}}" class="cur">客户信息管理</a>
        </div>
        <div class="customerWrapper">
            <div class="customerTop">
                <ul class="list">
                    <li class="listActive">客户列表</li>
                    <li><a href="{{url("/agent/companies/freezlist")}}">停用列表</a></li>
                </ul>
            </div>
            <div class="customerContainer">
                <div class="customerMenu">
                    <div class="search">
                        <form method="get" action="?">
                            <input type="hidden" name="s" value="1">
                            <div class="selectFilter">
                                <div class="selectHead curList" @click="codeList = !codeList">
                                    <span class="titleTop">@{{getDate}}</span>
                                    <i class="icon iconfont downTip">&#xe620;</i>
                                </div>
                                <ul class="showTitle" v-show="codeList">
                                    <li v-for="item in options" :key="item.index" @click="getNewAdds(item.label)">
                                        @{{item.label}}
                                    </li>
                                </ul>
                            </div>
                            <div class="searchContainer">
                                <input type="text" name="q" placeholder="请输入企业编码或名称" value="{{ old('company_name')}}">
                                <span id="icon-search">搜索</span>
                                {{--<i class="icon iconfont icon-search" id="icon-search"></i>--}}
                                <button type="submit" id="search_btn" style="display: none;" class="icon iconfont icon-search">搜索</button>
                            </div>
                        </form>
                        <ul class="customerBtn">
                            <li class="newAdd"><a href="{{url("/agent/companies/create")}}" >新增</a></li>
                            {{--<li class="page_loading">导入</li>--}}
                            <!--  <li><a href="javascript:;">采集</a></li>-->
                            <li @click="customerMore = !customerMore" class="moreBtn curList">
                                更多
                                <i class="iconfont downTip">&#xe620;</i>
                                <dl v-show="customerMore" class="customerBatch">
                                    <dt class="page_loading">批量删除</dt>
                                    <dt class="page_loading">批量启用</dt>
                                </dl>
                            </li>
                        </ul>
                    </div>
                    <div class="customerTable">
                        <table>
                            <thead>
                            <tr>
                                <th class="width5"><input type="checkbox"></th>
                                <th class="width15">编码</th>
                                <th class="width20">企业名称</th>
                                <th class="15">纳税人资格</th>
                                <th class="width10">企业状态</th>
                                <th class="width10">停用账期</th>
                                <th class="width25">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $v)
                                <tr>
                                    <td class="width5"><input type="checkbox" value="{{$v->id}}"></td>
                                    <td class="width15">{{$v->company_code}}</td>
                                    <td class="width20">{{$v->company_name}}</td>
                                    <td class="15">@if( $v->taxpayer_rights == '0') 一般纳税人 @else 小规模纳税人 @endif</td>
                                    <td class="width10 status_row">@if( $v->status == 'yes') 正常 @else <i>停用</i> @endif</td>
                                    <td class="width10">{{$v->stop_using}}</td>
                                    <td class="width25">
                                        <a style="display: none;" @click="changeStatus({{$v->id}})" class="change_status" href="javascript:void(0);" vid="{{$v->id}}">停用测试</a>
                                        <a class="status_book" href="javascript:void(0);" vid="{{$v->id}}" v_status="{{$v->status}}" vname="{{$v->company_name}}" vhref="{{URL::route("agent.companies.freez",array('id'=>$v->id))}}">停用</a>
                                        <a class="edit_book" href="javascript:void(0);" vhref="{{URL::route("agent.companies.edit",array('id'=>$v->id))}}">编辑</a>
                                        <a class="view_book" href="javascript:void(0);" vhref="{{URL::route("agent.companies.view",array('id'=>$v->id))}}">查看</a>
                                        <a class="enter_book" href="javascript:void(0);" vid="{{$v->id}}" vencode="{{$v->company_encode}}" vac="{{$v->accounting_system}}">进入账簿</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <!-- 分页 -->
                        <nav>
                            @if (!empty($data))
                                {{ $data->links() }}
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('agent.common.footer')
@endsection