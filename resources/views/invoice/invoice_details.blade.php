<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>
<style>
    @media print {
        body {
            color: black;
            /* Set text color for printing */
        }

        /* Add any other styles you want to modify for printing */
    }

    @media print {

        #test,
        #printButton,
        .excelButton {
            display: none;
        }

        @page {
            size: auto;
            margin: 0;
        }
    }

    @media print {
        body {
            -webkit-print-color-adjust: exact;
        }
    }
</style>

<body style="margin:25px;">

    <div>
        @foreach ($profile as $pic)
            @if ($invoice->branch == $pic->branch)
                <div class="mt-1 text-center">
                    <img src="{{ asset('logos/' . ($pic->logos ?? 'null')) }}" width="180" height="120">
                </div>
                <div class="row" style="margin-top: 10px;">
                    <h4 class="text-center fw-bold">{{ $pic->name }}</h4>

                    <p class="text-center fw-bold" style="font-size: 14px;">
                        {{ $pic->address }}
                        <br>
                        {{ $pic->phno1 }}, {{ $pic->phno2 }}
                    </p>
                </div>
            @endif
        @endforeach

        {{-- <h1>
            Invoice
        </h1> --}}

        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-content">

                        <div class="card-body">

                            <div class="row" style="display:flex;position:relative">

                                <div style="width:40%;">
                                    <h4>Invoice to</h4>
                                    <div class="input-group"><span style="font-weight:bolder"> Name :&nbsp;
                                        </span>{{ $invoice->customer_name }} </div>

                                    <div class="input-group"><span style="font-weight:bolder"> Sale Return :&nbsp;
                                        </span>{{ $invoice->balance_due }} </div>
                                    <br>
                                    <br>
                                </div>


                                <div style="width:30%;position:absolute;right:0px;top:0px;">

                                    <label for="invociedate" class="caption"
                                        style="font-weight:bolder">{{ trans('Invoices Number') }}</label>
                                    <div class="input-group"> {{ $invoice->invoice_no }} </div>
                                    <label for="invociedate" class="caption"
                                        style="font-weight:bolder">{{ trans('Invoice Date') }}</label>
                                    <div class="input-group"> {{ $invoice->invoice_date }}</div>

                                </div>

                            </div>
                            <br>
                            <div class="row" style="margin: 5;margin-top: -50px;">
                                <h3>Information</h3>
                                <div class="table-responsive">
                                    <table class="table text-center table-bordered">
                                        <thead class="bg-primary" style="color: black;">
                                            <tr class="text-white">
                                                <th style="width: 25%;">Phone Number</th>
                                                <th style="width: 25%;">Address</th>
                                                <th style="width: 25%;">Customer Type</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $invoice->phno }}</td>
                                                <td>{{ $invoice->address }}</td>
                                                <td>{{ $invoice->type }}</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 1vh;">
                                <div class="table-responsive">
                                    <table class="table text-center table-bordered" style="width: 100%;">
                                        <thead class="bg-primary" style="color: black;">
                                            <tr class="text-white">
                                                <th>{{ trans('No') }}</th>
                                                <th>{{ trans('Item Name') }}</th>
                                                <th>{{ trans('Qty') }}</th>
                                                <th>{{ trans('Unit') }}</th>
                                                <th>{{ trans('Price') }}</th>
                                                <th>{{ trans('Discounts') }}</th>

                                                {{-- <th style="width: 3%;"></th> --}}
                                                <th>{{ trans('Total') }}
                                                    {{-- ({{ config('currency.symbol') }}) --}}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $no = 1;
                                            @endphp
                                            @foreach ($sells as $sell)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $sell->part_number }}</td>
                                                    <td>{{ $sell->product_qty }}</td>
                                                    <td>{{ $sell->unit }}</td>
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
                                                    <td>{{ number_format($sell->discount) }}</td>

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
                                                @endphp
                                            @endforeach

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Sub Total
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($invoice->sub_total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder; ">Overall Discount
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($invoice->discount_total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder; ">Item Discount
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($sells->sum('discount')) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Total
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($invoice->total) }}
                                                </td>
                                            </tr>

                                            @foreach ($payment_methods as $index => $payment_method)
                                                <tr>
                                                    <td colspan="5" class="text-right"></td>
                                                    @if ($index == 0)
                                                        <td style="font-weight: bolder;">Payment Method
                                                            - {{ $payment_method->payment_method }}
                                                        </td>
                                                    @else
                                                        <td style="font-weight: bolder;">
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            {{ $payment_method->payment_method }}
                                                        </td>
                                                    @endif

                                                    <td style="font-weight: bolder;">
                                                        {{ number_format($payment_method->payment_amount) }}
                                                    </td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Deposit
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($invoice->deposit) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Remaining Balance
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($invoice->remain_balance) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>




                            <br><br>

                            <table width="60%" class="">
                                <tr>
                                    <td style="font-weight: bolder">Remark - {{ $invoice->remark }}
                                    </td>
                                </tr>
                            </table>

                            {{-- <a href="{{ URL('/invoice/pdf', $idd) }}"> Print</a> --}}
                        </div>

                    </div>
                </div>
            </div>
            <a onclick="printPage()" id="printButton" class="mt-4 btn btn-success">Print</a>

        </div>

    </div>


</body>

</HTML>
<script>
    function printPage() {
        window.print();
    }
</script>
