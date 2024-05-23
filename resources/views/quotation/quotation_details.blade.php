<!DOCTYPE html>
<HTML>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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

        <h1>
            Quotation
        </h1>

        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-content">

                        <div class="card-body">

                            <div class="row" style="display:flex;position:relative">

                                <div style="width:40%;">
                                    <h4>Quotation to</h4>
                                    <div class="input-group"><span style="font-weight:bolder">Customer Name :&nbsp;
                                        </span>{{ $quotation->customer_name }} </div>
                                    <br>
                                    <br>
                                </div>
                                <div style="width:30%;position:absolute;right:0px;top:0px;">

                                    <label for="invociedate" class="caption" style="font-weight:bolder">{{ trans('Quotations Number') }}</label>
                                    <div class="input-group"> {{ $quotation->quote_no }} </div>
                                    <label for="invociedate" class="caption" style="font-weight:bolder">{{ trans('Quotation Date') }}</label>
                                    <div class="input-group"> {{ $quotation->quote_date }}</div>

                                </div>

                            </div>
                            <br>
                            <div class="row" style="margin: 5;">
                                <h3>Information</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center">
                                        <thead class="bg-primary" style="color: black;">
                                            <tr class="text-white">
                                                <th style="width: 25%;">Phone Number</th>
                                                <th style="width: 25%;">Address</th>
                                                <th style="width: 25%;">Customer Type</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $quotation->phno }}</td>
                                                <td>{{ $quotation->address }}</td>
                                                <td>{{ $quotation->type }}</td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row" style="margin-top: 1vh;">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" style="width: 100%">
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
                                            @foreach ($sells as $sell)
                                            <tr>
                                                <td>{{ $no }}</td>
                                                <td>{{ $sell->part_number }}</td>
                                                <td>{{ $sell->description }}</td>
                                                <td>{{ $sell->product_qty }}</td>
                                                @if ($sell->product_price)
                                                <td>{{ $sell->product_price }}</td>
                                                @elseif($sell->retail_price)
                                                <td>{{ $sell->retail_price }}</td>
                                                @endif
                                                <td>{{ $sell->unit }}</td>

                                                <td>
                                                    <span class="currenty"></span>
                                                    <span class='ttlText'>{{ $sell->product_qty * ($sell->product_price ?? $sell->retail_price) }}</span>
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
                                                <td style="font-weight: bolder; ">
                                                    {{ number_format($quotation->sub_total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Discount
                                                </td>
                                                <td style="font-weight: bolder; ">
                                                    {{ number_format($quotation->discount_total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Total
                                                </td>
                                                <td style="font-weight: bolder; ">
                                                    {{ number_format($quotation->total) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Deposit
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($quotation->deposit) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"></td>
                                                <td style="font-weight: bolder;">Remaining Balance
                                                </td>
                                                <td style="font-weight: bolder;">
                                                    {{ number_format($quotation->remain_balance) }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>


                            <table width="60%" class="">
                                <tr>
                                    <td style="font-weight: bolder">Remark - {{ $quotation->remark }}
                                    </td>
                                </tr>
                            </table>


                            <br><br>



                            {{-- <a href="{{ URL('/quotation/pdf', $idd) }}"> Print</a> --}}
                        </div>

                    </div>
                </div>
            </div>
            <a onclick="printPage()" id="printButton" class="btn btn-success mt-4">Print</a>

        </div>

    </div>


</body>

</HTML>
<script>
    function printPage() {
        window.print();
    }
</script>
