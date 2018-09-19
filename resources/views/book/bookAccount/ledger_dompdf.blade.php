<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{$company_name}}_{{$period}}_总账打印</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <style>
        @font-face {
            font-family: msyh;
            font-style: normal;
            font-weight: normal;
            src: url("{{asset("fonts/msyh.ttf")}}") format('truetype');
        }
        html, body {
            height: 297mm;
        }
        body {
            padding: 0;
            width: 100%;
            font-weight: 100;
            font-family: msyh;
            font-size: 3mm;
        }
        .ledger-pdf { width: 100%; height: auto; min-height: 30px; margin: 0 auto; clear: both;}
        .ledger-title { width: 100%; height: 50px; border-color: #0e90d2; display: block; margin: 0 auto; clear: both; font-size: 5mm; text-align: center; }
        .fu-title { height: 44px; line-height: 44px;}
        .fu-title ul { list-style-type:none;padding:0;margin:0; }
        .fu-title ul li { list-style-type:none;padding:0;margin:0; float: left; width: 33.3%; display: block;}
        .fu-title .fl { text-align: left;}
        .fu-title .md { text-align: center;}
        .fu-title .fr { text-align: right;}
        .list .pdf-items { width: 100%; border: 1px solid #eee; border-collapse: collapse;}
        .list .pdf-items tr { width: auto; margin: 0 auto; clear: both;}
        .list .pdf-items th { text-align: left; padding: .5em .5em; height: 30px; line-height: 22px; color: #000;}
        .list .pdf-items td { padding: .3em .5em; border-bottom: solid 1px #ccc; height: 22px; line-height: 22px;}
        .list .pdf-items, .list .pdf-items tr th, .list .pdf-items tr td { border:1px solid #000; }
        .list .pdf-items thead th {text-align: center;}
        .list .pdf-items .money { text-align: right;}
        .list .pdf-items .fx { text-align: center;}
    </style>
</head>
<body>
    <div class="ledger-title">
        总账
    </div>
    <div class="ledger-pdf fu-title">
        <ul>
            <li class="fl">{{$company_name}}</li>
            <li class="md">{{$period}}</li>
            <li class="fr">单位/元</li>
        </ul>
    </div>

    <div class="ledger-pdf list">

        <table class="pdf-items" border="1">
            <tr class="bd">
                <td>科目编码</td>
                <td>科目名称</td>
                <td width="60">期间</td>
                <td>摘要</td>
                <td>借方</td>
                <td>贷方</td>
                <td>方向</td>
                <td>余额</td>
            </tr>
            @foreach($list as $key => $item)
                @foreach($item['item'] as $v)
                    <tr>
                        <td rowspan="3">{{$v['account_subject_number']}}</td>
                        <td rowspan="3">{{$v['account_subject_name']}}</td>
                        <td rowspan="3">{{$v['fiscal_period']}}</td>
                        <td>期初余额</td>
                        <td class="money">{{$v['qcye_j']}}</td>
                        <td class="money">{{$v['qcye_d']}}</td>
                        <td class="fx">{{$v['balance_direction']}}</td>
                        <td class="money">{{$v['qcye']}}</td>
                    </tr>
                    <tr>
                        <td>本期合计</td>
                        <td class="money">{{$v['bqhj_j']}}</td>
                        <td class="money">{{$v['bqhj_d']}}</td>
                        <td class="fx">{{$v['balance_direction']}}</td>
                        <td class="money">{{$v['bqhj']}}</td>
                    </tr>
                    <tr>
                        <td>本年累计</td>
                        <td class="money">{{$v['bnlj_j']}}</td>
                        <td class="money">{{$v['bnlj_d']}}</td>
                        <td class="fx">{{$v['balance_direction']}}</td>
                        <td class="money">{{$v['bnlj']}}</td>
                    </tr>
                @endforeach
            @endforeach
        </table>

    </div>

</body>
</html>