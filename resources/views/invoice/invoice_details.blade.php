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
                font-size: 12px;
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
                font-size: 12px;
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
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="container custom-container">
        <!-- Invoice Header -->




        @foreach ($profile as $pic)
            @if ($invoice->branch == $pic->branch)
                {{-- <div class="row" style="margin-top: 30px;">
                    <h4 class="text-center fw-bold">{{ $pic->name }}</h4>

                    <p class="text-center fw-bold" style="font-size: 14px;">
                        {{ $pic->address }}
                        <br>
                        {{ $pic->phno1 ? $pic->phno1 : $pic->phno2 }}

                    </p>
                </div> --}}

                <div class="invoice-header d-flex justify-content-center align-items-center flex-column">
                    <div>
                        <img src="{{ asset('logos/' . ($pic->logos ?? '')) }}" alt="Company Logo" style="height: 80px;">
                    </div>
                    <div class="text-center">
                        <p>
                            {{ $pic->address ?? '' }} <br>
                            Tel : {{ $pic->phno1 ?? '' }} @if (isset($pic->phno2))
                                ,
                                {{ $pic->phno2 ?? '' }}
                            @endif <br>
                            E-mail : {{ $pic->email ?? '' }}
                        </p>
                    </div>
                </div>
            @endif
        @endforeach



        <div class="d-flex justify-content-between">
            <div class="">
                <span>Name : {{ $invoice->customer_name }}</span><br>
                <span>Address : {{ $invoice->address }}</span><br>
                <span>Tel : {{ $invoice->phno }}</span>
            </div>
            <div class="">
                <span>Delivery Date :</span><br>
                <span>Invoice No : {{ $invoice->invoice_no }}</span>
            </div>
        </div>

        <table class="text-center" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border-left: 2px solid black; border: 2px solid black; width: 5%;">SR.<br>No.</th>
                    <th style="border-left: 2px solid black; border: 2px solid black; width: 15%;">Product<br>Code
                    </th>
                    <th style="border-left: 2px solid black; border: 2px solid black; width: 10%;">Design</th>
                    <th style="border-left: 2px solid black; border: 2px solid black;">Description</th>
                    <th style="border-left: 2px solid black; border: 2px solid black; width: 5%;">Qty</th>
                    <th style="border-left: 2px solid black; border: 2px solid black; width: 15%;">Unit Price</th>
                    <th style="border-left: 2px solid black; border: 2px solid black; width: 15%;">Total</th>
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
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
                            {{ $no }}
                        </td>
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
                            {{ $sell->part_number }}
                        </td>
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
                            <a href="{{ asset($sell->item->item_image ? 'item_images/' . $sell->item->item_image : 'img/default.png') }}"
                                target="_blank" id="logoLink">
                                <img src="{{ asset($sell->item->item_image ? 'item_images/' . $sell->item->item_image : 'img/default.png') }}"
                                    id="logoPreview" class="img-thumbnail" style="max-width: 80px; max-height: 100px;"
                                    alt="Item Image Preview">
                            </a>
                        </td>
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
                            {{ $sell->description }}
                        </td>
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
                            {{ $sell->product_qty }}
                        </td>
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
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
                        <td
                            style="border-left: 2px solid black; border-right: 2px solid black; border-top: none; border-bottom: none;">
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
            </tbody>

            <tfoot>
                <tr>
                    <td colspan="7"
                        style="border-bottom: 2px solid black; border-top: none; border-left: none; border-right: none;">
                    </td>
                </tr>
            </tfoot>


        </table>

        <div class="d-flex justify-content-between change_font">
            <div class="mt-3">
                <p>
                    <i><strong>**HAVE A NICE DAY**</strong></i><br>
                    <span style="font-weight: bold;">CASH:</span><br>
                    <span style="font-weight: bold;">BANK:</span><br>
                    <span style="font-weight: bold;">CARD:</span><br>
                    <span style="font-weight: bold;">OTHER:</span>
                </p>
            </div>
            <div class="me-5 no-print">
                <span style="font-weight: bold;">SUB
                    TOTAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->sub_total) }}
                    &nbsp;&nbsp;</span><br>
                <span
                    style="font-weight: bold;">DISCOUNT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->discount_total) }}&nbsp;&nbsp;</span><br>
                <span
                    style="font-weight: bold;">TAX:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    0
                    &nbsp;&nbsp;</span><br>
                <span
                    style="font-weight: bold;">PAID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->deposit) }}&nbsp;&nbsp;</span><br>
                <span
                    style="font-weight: bold;">BALANCE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->remain_balance) }}&nbsp;&nbsp;</span>
            </div>
            <div class="me-5 print" style="display: none">
                <span style="font-weight: bold;">SUB
                    TOTAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($invoice->sub_total) }}</span><br>
                <span
                    style="font-weight: bold;">DISCOUNT:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->discount_total) }}</span><br>
                <span
                    style="font-weight: bold;">TAX:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    {{ number_format($invoice->sub_total) }}</span><br>
                <span
                    style="font-weight: bold;">PAID:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($invoice->deposit) }}</span><br>
                <span
                    style="font-weight: bold;">BALANCE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($invoice->remain_balance) }}</span>
            </div>
        </div>
        <table style="width: 100%;height: 40px;" class="change_font">
            <thead style="border: 2px solid black;">
                <tr>
                    <th>Balance Payment due date: {{ $invoice->overdue_date }}</th>
                    <th style="width: 35%;border-left: 2px solid black">GRAND
                        TOTAL: {{ number_format($invoice->total) }}</th>
                </tr>
            </thead>
        </table>
        <div class="d-flex justify-content-between change_font">
            <div class="mt-3">
                <strong>Customer Signature</strong>
            </div>
            <div class="">
                <strong>
                    <h6 class="fw-bold">FOR TREASURE STAR</h6>
                </strong><br><br>
                <strong style="margin-top: -30px;display: block;">
                    <p>Authorized Signature</p>
                </strong>
            </div>
        </div>
        <div class="mt-2 row">
            <div class="col-10">
                <span style="text-decoration: underline;color: blue;">Tems & Conditions</span> <br>
                <span>
                    <p class="ms-s footer">1. 5% deduction on the diamond item invoice value at
                        the
                        time of exchange. <br>
                        &nbsp;&nbsp;&nbsp; စိန်ထည်ပစ္စည်းများကို ပြန်လဲလျှင် မူလပစ္စည်းအတိုင်းမူလဈေး၏ 5%
                        လျှော့၍လက်ခံပါမည်။ <br>
                        2. 10% deduction on the diamond item invoice value at the time of cash-back/buy-back. <br>
                        &nbsp;&nbsp;&nbsp;စိန်ထည်ပစ္စည်းများကို ပြန်သွင်းလျှင် မူလ ပစ္စည်းအတိုင်းမူလဈေး၏ 10%
                        လျှော့၍လက်ခံပါမည်။ <br>
                        3. 10% deduction on the stone item invoice value at the time of exchange. <br>
                        &nbsp;&nbsp;&nbsp;ကျောက်ထည်ပစ္စည်းများကို ပြန်လဲလျှင် မူလပစ္စည်းအတိုင်းမူလဈေး၏ 10%
                        လျှော့၍လက်ခံပါမည်။
                        <br>
                        4. 15% deduction on the stone item invoice value at the time of cash-back/buy-back. <br>
                        &nbsp;&nbsp;&nbsp;ကျောက်ထည်ပစ္စည်းများကို ပြန်သွင်းလျှင် မူလပစ္စည်းအတိုင်းမူလဈေး၏ 15%
                        လျှော့၍လက်ခံပါမည်။ <br>
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
