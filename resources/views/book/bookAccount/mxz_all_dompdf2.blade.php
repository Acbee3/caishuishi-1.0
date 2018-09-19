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
        html body {height: 297mm; font-family: msyh; font-size: 3mm; page-break-inside:avoid;}
        .ledger-pdf { width: 100%; height: auto; display: block; min-height: 30px; margin: 0 auto; clear: both;}
        .ledger-title { width: 100%; height: 50px; border-color: #0e90d2; display: block; margin: 0 auto; clear: both; font-size: 5mm; text-align: center; }
        .fu-title { height: 44px; line-height: 44px;}
        .fu-title ul { list-style-type:none;padding:0;margin:0;}
        .fu-title ul li { list-style-type:none;padding:0;margin:0; float: left; width: 33.3%; display: block;}
        .fu-title .fl { text-align: left;}
        .fu-title .md { text-align: center;}
        .fu-title .fr { text-align: right;}
        .list {
            margin: 0 auto;
            clear:both;
            display: block;
            background-color: #fff;
        }
        .header .txt {
            height: 36px;
            line-height: 20px;
            display: block;
            float: left;
            text-align: center!important;
            border-left: 1px solid #333;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
        }
        .cen-list .txt {
            height: 30px;
            float: left;
            border-left: 1px solid #333;
            border-top: none;
            border-bottom: 1px solid #333;
            display: inline-block;
            text-indent: 3px;
            padding-top: 2px;
        }
        .w_rq { width: 10%}
        .w_pz { width: 10%; }
        .w_zy { width: 25%; }
        .w_fx { width: 10%; text-align: center; }
        .w_m { width: 15%; text-align: right; }
        .last { border-right: 1px solid #333;}
        .item-footer { width: 100%; height: 15px; margin: 0 auto; clear: both;}
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
        <div class="pdf-items">
            <div class="list header">
                <div class="txt w_rq">日期</div>
                <div class="txt w_pz">凭证字号</div>
                <div class="txt w_zy">摘要</div>
                <div class="txt w_m">借方</div>
                <div class="txt w_m">贷方</div>
                <div class="txt w_fx">方向</div>
                <div class="txt w_m last">余额</div>
            </div>
            @foreach($v['items'] as $key => $item)
                <div class="list cen-list">
                    <div class="txt w_rq">{{$item['date']}}</div>
                    <div class="txt w_pz">{{$item['marks']}}</div>
                    <div class="txt w_zy">{{$item['zy']}}</div>
                    <div class="txt money w_m">{{$item['debit']}}&nbsp;&nbsp;</div>
                    <div class="txt money w_m">{{$item['credit']}}&nbsp;&nbsp;</div>
                    <div class="txt w_fx">{{$item['direction']}}</div>
                    <div class="txt money w_m last">{{$item['ye']}}&nbsp;&nbsp;</div>
                </div>
            @endforeach
        </div>

    <div class="ledger-pdf item-footer"></div>
    @endforeach
<style>
    .pdf-items { height: auto!important;}
</style>
</body>
</html>