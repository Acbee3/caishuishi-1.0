<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{$company_name}}_{{$period}}_明细账打印</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <style>
        @font-face {
            font-family: msyh;
            font-style: normal;
            font-weight: normal;
            src: url("{{asset("fonts/msyh.ttf")}}") format('truetype');
        }
        body {height: 297mm; font-family: msyh; font-size: 3mm; page-break-inside:avoid;}
        .ledger-pdf { width: 100%; height: auto; min-height: 30px; margin: 0 auto; clear: both;}
        .ledger-title { width: 100%; height: 50px; border-color: #0e90d2; display: block; margin: 0 auto; clear: both; font-size: 5mm; text-align: center; }
        .fu-title { height: 44px; line-height: 44px;}
        .fu-title ul { list-style-type:none;padding:0;margin:0; }
        .fu-title ul li { list-style-type:none;padding:0;margin:0; float: left; width: 33.3%; display: block;}
        .fu-title .fl { text-align: left;}
        .fu-title .md { text-align: center;}
        .fu-title .fr { text-align: right;}
        .ledger-pdf .pdf-items { width: 100%; height: auto; border: 1px solid #eee; border-collapse: collapse; }
        .ledger-pdf .pdf-items tr { margin: 0 auto; clear: both;}
        .ledger-pdf .pdf-items th { text-align: left; padding: .5em .5em; height: 30px; line-height: 22px; color: #000;}
        .ledger-pdf .pdf-items td { padding: .3em .5em; border-bottom: solid 1px #ccc; height: 22px; line-height: 22px;}
        .ledger-pdf .pdf-items, .list .pdf-items tr th, .list .pdf-items tr td { border:1px solid #000; }
        .ledger-pdf .pdf-items thead th {text-align: center;}
        .ledger-pdf .pdf-items .money { text-align: right;}
        .ledger-pdf .pdf-items .fx { text-align: center;}
        .ledger-pdf .pdf-items .bd { font-family: "msyh", "Microsoft YaHei"; }
        .ledger-pdf .pdf-items .fx { text-align: center;}
        .item-footer { width: 100%; height: 30px; display: flow; margin: 0 auto; clear: both;}
    </style>
</head>
<body>
    <div class="ledger-pdf ledger-title">{{$company_name}}_明细账</div>
    @foreach($list as $key => $v)
        <div class="ledger-pdf fu-title">
            <ul>
                <li class="fl">科目: {{$v['km_name']}}</li>
                <li class="md">{{$period}}</li>
                <li class="fr">单位/元</li>
            </ul>
        </div>
    <div class="ledger-pdf">
        <table class="pdf-items" border="1">
            {{--<thead>
            </thead>
            <tbody>
            </tbody>--}}
            <thead>
            <tr class="bd">
                <td width="100">日期</td>
                <td>凭证字号</td>
                <td width="200">摘要</td>
                <td>借方</td>
                <td>贷方</td>
                <td>方向</td>
                <td>余额</td>
            </tr>
            </thead>
            <tbody>
            @foreach($v['items'] as $key => $item)
                <tr style="width: 100%; margin: 0 auto;">
                    <td>{{$item['date']}}</td>
                    <td>{{$item['marks']}}</td>
                    <td>{{$item['zy']}}</td>
                    <td class="money">{{$item['debit']}}</td>
                    <td class="money">{{$item['credit']}}</td>
                    <td class="fx">{{$item['direction']}}</td>
                    <td class="money">{{$item['ye']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="ledger-pdf item-footer"></div>
    </div>

    @endforeach
<style>
    .pdf-items { height: auto!important;}
</style>
</body>
</html>