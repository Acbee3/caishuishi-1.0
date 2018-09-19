@extends('book.layout.base')
<!--公用-->
@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/zc/zcbd.css">
@endsection

@section('content')
<div class="zcbd">
    <div class="zcType">
        <div class="zcType-item">
            <span>资产类型:</span>
            <div class="selectZc">
                <div class="selectHead" @click="zcList = !zcList">
                <span class="zcTop">@{{getZc}}</span>
                <i class="icon iconfont icon-xialazhishijiantou"></i>
            </div>
            <ul class="showZc" v-show="zcList">
                <li @click="getAssetAlterList()">全部</li>
                <li v-for="item in zcOptions" :key="item" @click="getNewZc(item)">
                @{{item}}
                </li>
            </ul>
        </div>
    </div>
    <div class="zcType-item">
        <span>变动类型:</span>
        <div class="selectZc">
            <div class="selectHead" @click="zcBdList = !zcBdList">
            <span class="zcTop">@{{getZcBd}}</span>
            <i class="icon iconfont icon-xialazhishijiantou"></i>
        </div>
        <ul class="showZc" v-show="zcBdList">
            <li @click="getAssetAlterList()">全部</li>
            <li v-for="item in zcBdOptions" :key="item" @click="getNewZcBd(item)">
            @{{item}}
            </li>
        </ul>
    </div>
</div>
<div class="zcBtn">
    <a href="javascript:;">恢复记账</a>
</div>
</div>
<div>
    <div class="zcTableHead">
        <table>
            <thead>
            <tr>
                <th class="width4"><input type="checkbox" @click="allSelect" v-model="checked"></th>
                <th class="width8">序号</th>
                <th class="width11">资产类型</th>
                <th class="width11">资产名称</th>
                <th class="width11">资产类别</th>
                <th class="width11">变动类型</th>
                <th class="width11">变动项</th>
                <th class="width11">变动金额</th>
                <th class="width11">凭证号</th>
                <th class="width11">操作</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="zcTableBody">
        <table>
            <tbody>
            <tr v-for="(item,index) in zcTable" :key="item">
                <td class="width4"><input type="checkbox" v-model="selected" :value="item.id"></td>
                <td class="width8">@{{index+1}}</td>
                <td class="width11">@{{item.zclx}}</td>
                <td class="width11">@{{item.zcmc}}</td>
                <td class="width11">@{{item.zclb}}</td>
                <td class="width11">@{{item.bdlx}}</td>
                <td class="width11">@{{item.dbx}}</td>
                <td class="width11">@{{item.bdje}}</td>
                <td class="width11">
                    <a href="javascript:;" style="color:#568bfb" @click="addInvoice(item)">@{{item.voucher ? "记-"+item.voucher.voucher_num : ''}}</a>
                </td>
                <td class="width11"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection

@section('script')
    @parent
<script>
    new Vue({
        'el': '.zcbd',
        data: {
            zcList: false,
            zcBdList: false,
            checked: false,
            getZc: '',
            getZcBd: '',
            selected: [],
            zcOptions: [],
            zcBdOptions: [],
            zcTable: []
        },
        created:function () {
            this.getAssetAlterList();
            this.getzcOptions();
            this.getbdOptions();
        },
        methods: {
            /*=-------点击生成的凭证---记—12------*/
            addInvoice:function(item) {
                var items = item.id;
                //console.log(items);
                localStorage.setItem('invoiceId',items);
                layer.open({
                    type: 2,
                    title: '凭证信息',
                    shadeClose: true,
                    shade: 0.2,
                    maxmin: true, //开启最大化最小化按钮
                    area: ['1200px', '96%'],
                    content: ['{{ url('book/voucher/add') }}', 'yes']
                });
            },
            //列表
            getAssetAlterList: function () {
                this.getZc = '';
                this.getZcBd = '';
                this.zcList = false;
                this.zcBdList = false;
                data = {zclx:this.getZc, bdlx:this.getZcBd};
                this.$http.get('{{route('assetalert.assetAlterList')}}', {params:data}).then(function (response) {
                    //this.zcTable = response.body.data;
                })
            },
            //资产类型选中值
            getNewZc: function (value) {
                this.getZc = value;
                this.getZcBd = '';
                data = {zclx:this.getZc, bdlx:this.getZcBd};
                this.$http.get('{{route('assetalert.assetAlterList')}}', {params:data}).then(function (response) {
                    this.zcTable = response.body.data
                })
                this.zcList = false;
            },
            //资产类型下拉列表
            getzcOptions: function () {
                this.$http.get('{{route('asset.type')}}').then(function (response) {
                    this.zcOptions = response.body.data
                    this.zcList = false;
                })
            },
            //变动类型选中值
            getNewZcBd: function (value) {
                this.zcBdList = false;
                this.getZcBd = value;
                data = {zclx:this.getZc, bdlx:this.getZcBd};
                this.$http.get('{{route('assetalert.assetAlterList')}}', {params:data}).then(function (response) {
                    this.zcTable = response.body.data
                })
            },
            //资产变动下拉列表
            getbdOptions: function () {
                this.$http.get('{{route('assetalter.getAssetBdlx')}}').then(function (response) {
                    this.zcBdOptions = response.body.data
                    this.zcBdList = false;
                })
            },
            //全选
            allSelect: function () {
                if (this.selected.length != this.zcTable.length) {
                    this.selected = []
                    for (var i in this.zcTable) {
                        this.selected.push(this.zcTable[i].id)
                    }
                } else {
                    this.selected = []
                }
            }
        },
        watch: {
            "selected": function () {
                if (this.selected.length == this.zcTable.length) {
                    this.checked = true
                } else {
                    this.checked = false
                }
            }
        }
    })
</script>
@endsection