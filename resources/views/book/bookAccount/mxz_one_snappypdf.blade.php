<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>{{$company_name}}_{{$period}}_{{$km_name}}_明细账打印</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <style>
        html, body {height: 297mm; }
        .ledger-pdf { width: 100%; height: auto; min-height: 30px; margin: 0 auto; clear: both;}
        .title { height: 50px; line-height: 50px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 2px;}
        .fu-title { height: 44px; line-height: 44px;}
        .fu-title ul { list-style-type:none;padding:0;margin:0; }
        .fu-title ul li { list-style-type:none;padding:0;margin:0; float: left; width: 33.3%; display: block;}
        .fu-title .fl { text-align: left;}
        .fu-title .md { text-align: center;}
        .fu-title .fr { text-align: right;}
        .list .pdf-items { width: 100%; border: 1px solid #eee; border-collapse: collapse;}
        .list .pdf-items tr { width: auto; margin: 0 auto; clear: both;}
        .list .pdf-items th { text-align: center; padding: .5em .5em; height: 30px; line-height: 30px; font-weight: bold; color: #000;}
        .list .pdf-items td { padding: .3em .5em; border-bottom: solid 1px #ccc; height: 22px; line-height: 22px;}
        .list .pdf-items, .list .pdf-items tr th, .list .pdf-items tr td { border:1px solid #000; }
        .list .pdf-items thead th {text-align: center;}
        .list .pdf-items .money { text-align: right;}
        .list .pdf-items .fx { text-align: center;}
    </style>
</head>
<body>
    <div class="ledger-pdf title">{{$company_name}}_明细账</div>
    <div class="ledger-pdf fu-title">
        <ul>
            <li class="fl">科目: {{$km_name}}</li>
            <li class="md">{{$period}}</li>
            <li class="fr">单位/元</li>
        </ul>
    </div>
    <div class="ledger-pdf list">
        <table class="pdf-items" border="1">
            {{--<thead>
            </thead>
            <tbody>
            </tbody>--}}
            <tr>
                <th style="width: 30mm">日期</th>
                <th style="width: 20mm">凭证字号</th>
                <th>摘要</th>
                <th style="width: 28mm">借方</th>
                <th style="width: 28mm">贷方</th>
                <th style="width: 15mm">方向</th>
                <th style="width: 30mm">余额</th>
            </tr>
            @foreach($list as $key => $item)
                <tr>
                    <td>{{$item['date']}}</td>
                    <td>{{$item['marks']}}</td>
                    <td>{{$item['zy']}}</td>
                    <td class="money">{{$item['debit']}}</td>
                    <td class="money">{{$item['credit']}}</td>
                    <td class="fx">{{$item['direction']}}</td>
                    <td class="money">{{$item['ye']}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>