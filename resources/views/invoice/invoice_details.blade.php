<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Casabella</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
        integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        input[type="checkbox"] {
            background-color: #FF2929 !important;
            color: FF2929;
        }

        input.small {
            width: 9px;
            height: 9px;
        }

        .table td,
        .table th {
            font-size: 12px;
            /* color: #76453B; */
        }

        #adjustment {
            font-size: 14px;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        @media print {
            .badge-btn {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            body {
                font-size: 20px;
                padding: 0;
                margin: 0;
                /* color: #76453B; */
            }

            .container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
                margin-top: 0;
                /* Set margin-top to 0 */
                padding-top: 0;
                /* Set padding-top to 0 */
                /* color: #76453B; */
            }

            .table {
                margin: 0;
                padding: 0;
                border-collapse: collapse;
                /* color: #76453B; */
            }

            table td,
            table th {
                font-size: 17px;
                padding: 2px;
                line-height: 1;
                /* color: #76453B; */
            }

            table,
            tr,
            td,
            th {
                page-break-inside: avoid;
                /* color: #76453B; */
            }

            .no-print {
                display: none;
            }

            .footer {
                font-size: 13px;
            }

            .print {
                display: block !important;
            }

            .change_font {
                font-size: 17px;
            }
        }
    </style>
</head>

<body>
    <div class="container custom-container">
        <!-- Invoice Header -->
        <div class="invoice-header d-flex justify-content-center align-items-center flex-column">
            <div>
                <img src="{{ asset('images/treasure.png') }}" alt="Company Logo" style="height: 80px;">
            </div>
            <div class="text-center">
                <p>
                    No.127(B),West Shwe Gone Dine Rd,Bahan Township ,Yangon, Myanmar. <br>
                    Tel : 95-9776127384,09-778494052 <br>
                    E-mail : treasurestar.co@gmail.com
                </p>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div class="">
                <span>Name :</span><br>
                <span>Address :</span><br>
                <span>Tel :</span>
            </div>
            <div class="">
                <span>Delivery Date :</span><br>
                <span>Invoice No :</span>
            </div>
        </div>

        <table class="text-center" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid black; width: 5%;">SR.<br>No.</th>
                    <th style="border: 1px solid black; width: 15%;">Product<br>Code</th>
                    <th style="border: 1px solid black; width: 10%;">Design</th>
                    <th style="border: 1px solid black;">Description</th>
                    <th style="border: 1px solid black; width: 5%;">Qty</th>
                    <th style="border: 1px solid black; width: 15%;">Unit Price</th>
                    <th style="border: 1px solid black; width: 15%;">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sellsGrouped = $sells->groupBy('exp_date');
                    $itemDiscount = 0;
                    $no = 1;
                @endphp
                @foreach ($sells as $sell)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $sell->part_number }}</td>
                        <td></td>
                        <td>{{ $sell->description }}</td>
                        <td>{{ $sell->product_qty }}</td>

                        <td>
                            @if ($invoice->sale_price_category == 'Default')
                                @if ($invoice->type == 'Whole Sale')
                                    {{ number_format($sell->product_price) }}
                                @else
                                    {{ number_format($sell->retail_price) }}
                                @endif
                            @elseif ($invoice->sale_price_category == 'Whole Sale')
                                {{ number_format($sell->product_price) }}
                            @elseif ($invoice->sale_price_category == 'Retail')
                                {{ number_format($sell->retail_price) }}
                            @else
                                {{ number_format($sell->retail_price) }}
                            @endif
                        </td>

                        <td>
                            <span class="currenty"></span>
                            <span class='ttlText'>
                                @if ($invoice->sale_price_category == 'Default')
                                    @if ($invoice->type == 'Whole Sale')
                                        {{ number_format($sell->product_price * $sell->product_qty) }}
                                    @else
                                        {{ number_format($sell->retail_price * $sell->product_qty) }}
                                    @endif
                                @elseif ($invoice->sale_price_category == 'Whole Sale')
                                    {{ number_format($sell->product_price * $sell->product_qty) }}
                                @elseif ($invoice->sale_price_category == 'Retail')
                                    {{ number_format($sell->retail_price * $sell->product_qty) }}
                                @else
                                    {{ number_format($sell->retail_price * $sell->product_qty) }}
                                @endif
                            </span>
                        </td>
                    </tr>

                    @php
                        $no++;
                        $itemDiscount += $sell->discount;
                    @endphp
                @endforeach


                <!-- Repeat other rows here -->
            </tbody>
        </table>
        <div class="d-flex justify-content-between change_font">
            <div class="mt-3">
                <p>
                    <i><strong>**HAVE A NICE DAY**</strong></i><br>
                    <span>CASH:</span><br>
                    <span>BANK:</span><br>
                    <span>CARD:</span><br>
                    <span>OTHER:</span>
                </p>
            </div>
            <div class="me-5 no-print">
                <span>SUB
                    TOTAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->sub_total) }}
                    &nbsp;&nbsp;</span><br>
                <span>DISCOUNT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->discount_total) }}&nbsp;&nbsp;</span><br>
                <span>TAX:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->sub_total) }}&nbsp;&nbsp;</span><br>
                <span>PAID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->deposit) }}&nbsp;&nbsp;</span><br>
                <span>BALANCE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->remain_balance) }}&nbsp;&nbsp;</span>
            </div>
            <div class="me-3 print" style="display: none">
                <span>SUB
                    TOTAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15,000</span><br>
                <span>DISCOUNT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15,000</span><br>
                <span>TAX:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15,000</span><br>
                <span>PAID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15,000</span><br>
                <span>BALANCE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;15,000</span>
            </div>
        </div>
        <table style="width: 100%;" class="change_font">
            <thead style="border: 1px solid black;">
                <tr>
                    <th>Balance Payment due date: {{ $invoice->overdue_date104.. }}</th>
                    <th style="width: 35%;border-left: 1px solid black">GRAND TOTAL:Kyats</th>
                </tr>
            </thead>
        </table>
        <div class="d-flex justify-content-between change_font">
            <div class="mt-5">
                <span>Customer Signature</span>
            </div>
            <div class="">
                <span>
                    <h6>FOR TREASURE STAR</h6>
                </span><br>
                <span>
                    <p>Authorized Signature</p>
                </span>
            </div>
        </div>
        <div class="mt-2 row">
            <div class="col-10">
                <span style="text-decoration: underline;color: blue;">Tems & Conditions</span> <br>
                <span>
                    <p class="ms-5 footer">1. 5% deduction on the diamond item invoice value at
                        the
                        time of exchange. <br>
                        စိန်ထည်ပစ္စည်းများကို ပြန်လဲလျှင် မူလပစ္စည်းအတိုင်းမူလဈေး၏ 5% လျှော့၍လက်ခံပါမည်။ <br>
                        2. 10% deduction on the diamond item invoice value at the time of cash-back/buy-back. <br>
                        စိန်ထည်ပစ္စည်းများကို ပြန်သွင်းလျှင် မူလ ပစ္စည်းအတိုင်းမူလဈေး၏ 10% လျှော့၍လက်ခံပါမည်။ <br>
                        3. 10% deduction on the stone item invoice value at the time of exchange. <br>
                        ကျောက်ထည်ပစ္စည်းများကို ပြန်လဲလျှင် မူလပစ္စည်းအတိုင်းမူလဈေး၏ 10% လျှော့၍လက်ခံပါမည်။ <br>
                        4. 15% deduction on the stone item invoice value at the time of cash-back/buy-back. <br>
                        ကျောက်ထည်ပစ္စည်းများကို ပြန်သွင်းလျှင် မူလပစ္စည်းအတိုင်းမူလဈေး၏ 15% လျှော့၍လက်ခံပါမည်။ <br>
                    </p>
                </span>
            </div>
            <div class="col-2 text-center" style="margin-top: 60px;">
                <img src="{{ asset('images/treasure_logo.jpg') }}" alt="" style="width: 100%;height: 100px;">
            </div>
        </div>
        <div class="text-start mt-4 no-print">
            <button class="btn text-white btn-primary" onclick="window.print()">Print
                Invoice</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ZQXYuPBBvP43v8RlaOUwaCTGSOH6UJOM2Gxj67J72IkF75Be32QZrUm9WPH5k7yc" crossorigin="anonymous">
    </script>
</body>

</html>
