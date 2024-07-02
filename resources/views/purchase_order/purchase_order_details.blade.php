<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

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
        @endforeach

        {{-- <h1>
            Purchase Order
        </h1> --}}

        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-content">

                        <div class="card-body">

                            <div class="row" style="display:flex;position:relative">

                                <div style="width:40%;">

                                    @if ($purchase_order->balance_due == 'PO')
                                        <h4>Purchase Order to</h4>
                                        <div class="input-group"><span style="font-weight:bolder"> Supplier Name :&nbsp;
                                            </span><span
                                                style="font-weight:bolder">{{ $purchase_order->supplier->name ?? 'N/A' }}
                                            </span></div>
                                    @endif

                                    <div class="input-group"><span style="font-weight:bolder"> Receiving Mode :&nbsp;
                                        </span><span style="font-weight:bolder">{{ $purchase_order->balance_due }}
                                        </span></div>
                                    <br>
                                    <br>
                                </div>
                                <div style="width:30%;position:absolute;right:0px;top:0px;">

                                    <label for="invociedate" class="caption"
                                        style="font-weight:bolder">{{ trans('Purchase Order Number') }}</label>
                                    <div class="input-group"><span
                                            style="font-weight:bolder">{{ $purchase_order->quote_no }}</span> </div>
                                    <label for="invociedate" class="caption"
                                        style="font-weight:bolder">{{ trans('Purchase Order Date') }}</label>
                                    <div class="input-group"><span
                                            style="font-weight:bolder">{{ $purchase_order->po_date }}</span> </div>

                                </div>

                            </div>
                            <br>


                            <div class="row" style="margin-top: 1vh;">
                                <div class="table-responsive">
                                    <table class="table text-center table-bordered" style="width: 100%">
                                        <thead class="bg-primary" style="color: black;">
                                            <tr class="text-white">
                                                <th>{{ trans('No') }}</th>
                                                <th>{{ trans('Item Name') }}</th>

                                                <th>{{ trans('Description') }}</th>
                                                <th>{{ trans('Qty') }}</th>
                                                <th>{{ trans('Price') }}</th>
                                                <th>{{ trans('Unit') }}</th>

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
                                            @foreach ($purchase_sells as $sell)
                                                <tr>
                                                    <td>{{ $no }}</td>
                                                    <td>{{ $sell->part_number }}</td>

                                                    <td>{{ $sell->description }}</td>
                                                    <td>{{ $sell->product_qty }}</td>
                                                    <td>{{ $sell->product_price }}</td>
                                                    <td>{{ $sell->unit }}</td>

                                                    <td>
                                                        <span class="currenty"></span>
                                                        <span
                                                            class='ttlText'>{{ $sell->product_qty * $sell->product_price }}</span>
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
                                                <td style="font-weight: bolder; ">Sub Total
                                                </td>
                                                <td style="font-weight: bolder; ">
                                                    {{ number_format($purchase_order->sub_total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder; ">Discount
                                                </td>
                                                <td style="font-weight: bolder; ">
                                                    {{ number_format($purchase_order->discount_total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder; ">Total
                                                </td>
                                                <td style="font-weight: bolder; ">
                                                    {{ number_format($purchase_order->total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Deposit
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($purchase_order->deposit) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Remaning Balance
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($purchase_order->remain_balance) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>




                            <br><br>

                            <table width="60%" class="">
                                <tr>
                                    <td style="font-weight: bolder">Remark - {{ $purchase_order->remark }}
                                    </td>
                                </tr>
                            </table>
                            {{-- <a href="{{ URL('/purchase_order/pdf', $idd) }}"> Print</a> --}}
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
