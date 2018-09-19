<html>
    <head>
        <style>
           .tableExcle td{
               text-align: center;
               font-weight:bold;
           }
            .cur{
                text-align: left;
            }
        </style>
    </head>
    <body>
        <table>
            <tr class="tableExcle">
                <td valign="middle" width="30" rowspan="2">科目编码</td>
                <td valign="middle" width="30" rowspan="2">科目名称</td>
                <td width="30" colspan="2">期初余额</td>
                <td width="30" colspan="2">本期发生额</td>
                <td width="30" colspan="2">本年累计发生额</td>
                <td width="30" colspan="2">期末余额</td>
            </tr>
            <tr class="tableExcle">
                <td></td>
                <td></td>
                <td width="15">借方</td>
                <td width="15">贷方</td>
                <td width="15">借方</td>
                <td width="15">贷方</td>
                <td width="15">借方</td>
                <td width="15">贷方</td>
                <td width="15">借方</td>
                <td width="15">贷方</td>
            </tr>
            @if($list && $list['result'])
                @foreach($list['result'] as $k=>$v)
                    <tr>
                        <td class="cur">{{ $v['account_subject_number'] or '' }}</td>
                        <td>{{ $v['account_subject_name'] or '' }}</td>
                        <td>{{ $v['qcye_j'] or '' }}</td>
                        <td>{{ $v['qcye_d'] or '' }}</td>
                        <td>{{ $v['bqfse_j'] or '' }}</td>
                        <td>{{ $v['bqfse_d'] or '' }}</td>
                        <td>{{ $v['bnljfse_j'] or '' }}</td>
                        <td>{{ $v['bnljfse_d'] or '' }}</td>
                        <td>{{ $v['qmye_j'] or '' }}</td>
                        <td>{{ $v['qmye_d'] or '' }}</td>
                    </tr>
                @endforeach
            @endif
        </table>
    </body>
</html>