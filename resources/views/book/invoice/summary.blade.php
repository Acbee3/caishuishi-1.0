@extends('book.layout.base')

@section('css')
    @parent
    <link rel="stylesheet" href="/css/book/listProcess/receipts.css?v=20180821">
@endsection

@section('content')
    <div class="fphz" v-cloak>
        <div class="fphz-head">
            <table border="0">
                <thead>
                <tr>
                    <th class="width28">发票类型</th>
                    <th class="width12">抵扣状态</th>
                    <th class="width12">发票张数</th>
                    <th class="width16">金额合计</th>
                    <th class="width16">税额合计</th>
                    <th class="width16">价税合计</th>
                </tr>
                </thead>
            </table>
        </div>
        <div class="fphz-body">
            <table border="0">
                <tbody>
                <tr v-for="item in fpTables" :key="item">
                    <td class="width28">@{{item.type}}</td>
                    <td class="width12">@{{item.status}}</td>
                    <td class="width12">@{{item.num}}</td>
                    <td class="width16">@{{item.moneyTotal}}</td>
                    <td class="width16">@{{item.seTotal}}</td>
                    <td class="width16">@{{item.jsTotal}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="fpMoney">
            <span>即征即退:</span>
            <i>@{{ jzjt }}</i>
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
        new Vue({
            'el': '.fphz',
            data: {
                /*
                fpTables: [
                    {
                        type:'增值税专用发票',
                        status: '不可抵扣',
                        num: '8',
                        moneyTotal: '10,666.00',
                        seTotal: '12,666.00',
                        jsTotal: '13,666.00'
                    },
                    {
                        type:'增值税专用发票3',
                        status: '抵扣',
                        num: '8',
                        moneyTotal: '10,666.00',
                        seTotal: '12,666.00',
                        jsTotal: '13,666.00'
                    }
                ],
                */
                jzjt: '0.00',
                fpTables: [],
            },
            created: function () {
                var tmp_list = JSON.parse('{!! json_encode($list) !!}');
                this.fpTables = [];
                for (var i in tmp_list) {
                    this.fpTables.push({
                        type: tmp_list[i]['label'],
                        status: tmp_list[i]['dkzt'],
                        num: tmp_list[i]['fpzs_sum'],
                        moneyTotal: tmp_list[i]['money_sum'],
                        seTotal: tmp_list[i]['tax_money_sum'],
                        jsTotal: tmp_list[i]['fee_tax_money_sum'],
                    });
                }
            }

        })
    </script>
@endsection
