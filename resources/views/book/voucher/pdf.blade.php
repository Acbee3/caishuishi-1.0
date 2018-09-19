<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>打印凭证</title>
    <style>
        @font-face {
            font-family: 'msyh';
            font-style: normal;
            font-weight: normal;
            src: url(http://css.web.com/fonts/simsun.ttf?v=20180823) format('truetype');
        }

        html, body {
            height: 297mm;
        }

        body {
            /*margin-top: -3mm;*/
            padding: 0;
            width: 100%;
            /*display: table;  */
            font-weight: 100;
            font-family: 'msyh';
            font-size: 3mm;
        }

        .textL {
            text-align: left;
        }

        .textC {
            text-align: center;
        }

        .textR {
            text-align: right;
        }

        .fl {
            float: left;
        }

        .fr {
            float: right;
        }

        table {
            border-collapse: collapse;
        }

        .pingzhengForm {
            height: 148.5mm;
            width: 176mm;
            margin: 0 auto;
        }

        .form {
            width: 176mm;
            /*margin-top: -8mm;*/
            /*margin-bottom: 6mm;*/
        }

        .form .header {
            width: 176mm;
            text-align: center;
            position: relative;
        }

        .form .header span {
            display: inline-block;
            /*height: 3mm;*/
            /*line-height: 3mm;*/
        }

        .form .header .title {
            font-size: 6mm;
            display: inline-block;
            /*margin: 0;*/
            width: 176mm;
            /*position: absolute;*/
            /*left: 30%;*/
            /*transform: translateX(300%);*/
            text-align: center;
        }

        .form .pzMsg {
            /*margin: 2mm 0;*/
            width: 176mm;
            position: relative;
            height: 10mm;
        }

        .form .pzMsg .dateRz {
            position: absolute;
            /*left: 50%;*/
            transform: translateX(275%);
        }

        .form table td, .form table th {
            border: 0.3mm solid #000;
            height: 12mm;
            font-size: 3mm;
        }

        .form {
            /*margin-bottom: 8mm;*/
        }

        .footer {
            width: 176mm;
            position: relative;
            height: 4mm;
            line-height: 4mm;
        }

        .footer .check {
            position: absolute;
            left: 26%;
        }

        .footer .cashier {
            position: absolute;
            left: 55%;
        }

        .formOdd {
            margin-top: -100mm;
        }

        .formEven {
            /*margin-bottom: 100mm;*/
        }

        @page {
            margin: 0px;
        }
    </style>
</head>
<body>
<div class="pingzhengForm">
    @foreach($list as $key => $item)
        <div class="form form1">
            <div class="header">
                <p class="title">记账凭证</p>
                <span class="fr" style="margin-top: 3mm;">附件数：{{ $item['attach']  }}</span>
            </div>
            <div class="pzMsg">
                <span class="company_name fl">{{ $item['company_name']  }}</span>
                <span class="dateRz">日期：{{ $item['voucher_date']  }}</span>
                <span class="pzNum fr">凭证号：{{ $item['voucher_num']  }}</span>
            </div>
            <table>
                <tr>
                    <td style="width: 60mm; text-align: center">摘要</td>
                    <td style="width: 74.8mm; text-align: center">科目</td>
                    <td style="width: 20.6mm; text-align: center">借方</td>
                    <td style="width: 20.6mm; text-align: center">贷方</td>
                </tr>
                @foreach($item['item'] as $sub_item)
                    <tr>
                        <td class="textL">{{ $sub_item['zhaiyao']  }}</td>
                        <td class="textL">{{ $sub_item['kemu']  }}</td>
                        <td class="textR">{{ $sub_item['jiefang']  }}</td>
                        <td class="textR">{{ $sub_item['daifang']  }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2">{{ $item['total_money_cn']  }}</td>
                    <td class="textR">{{ $item['total_debit_money']  }}</td>
                    <td class="textR">{{ $item['total_credit_money']  }}</td>
                </tr>
            </table>
            <div class="footer">
                <span class="admin fl">主管：</span>
                <span class="check">审核：</span>
                <span class="cashier">出纳：</span>
                <span class="tablemake fr">制单：</span>
            </div>
        </div>

        @if($key % 2 == 0)
            <br><br><br>
            <br><br><br>
        @endif

    @endforeach
</div>
</body>
</html>