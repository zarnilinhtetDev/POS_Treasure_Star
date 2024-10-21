<!DOCTYPE html>
<HTML>

<head>
    <link rel="stylesheet" href="{{ asset('locallink/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        input {
            position: relative;
            width: 150px;
            height: 40px;
            color: white;
        }

        input:before {
            position: absolute;
            top: 3px;
            left: 3px;
            content: attr(data-date);
            display: inline-block;
            color: black;
        }

        input::-webkit-datetime-edit,
        input::-webkit-inner-spin-button,
        input::-webkit-clear-button {
            display: none;
        }

        input::-webkit-calendar-picker-indicator {
            position: absolute;
            top: 3px;
            right: 0;
            color: black;
            opacity: 1;
        }
    </style>
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

            #print1 {
                display: none;
            }

            #print2 {
                display: none;
            }

            #print3 {
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


</head>

<body>

    <div class="container mx-auto">
        <div class="row">
            <div class="col-6">
            </div>
            <div class="gap-2 pt-2 col-6 d-flex align-items-center justify-content-end">
                <a onclick="printPage()" id="printButton" class=" btn btn-success" class="btn btn-primary"
                    style="border-radius:10px;"><i class="fa-solid fa-print text-white"></i> Print</a>
                <a id="test" href="{{ url('invoice') }}" class="btn btn-danger" style="border-radius:10px;"><i
                        class="fa-solid fa-backward text-white"></i> Back</a>
            </div>
        </div>
        @foreach ($profile as $pic)
            @if ($invoice->branch == $pic->branch)
                <div class="row" style="margin-top: 30px;">
                    <h4 class="text-center fw-bold">{{ $pic->name }}</h4>

                    <p class="text-center fw-bold" style="font-size: 14px;">
                        {{ $pic->address }}
                        <br>
                        {{ $pic->phno1 ? $pic->phno1 : $pic->phno2 }}

                    </p>
                </div>
            @endif
        @endforeach
        {{-- <div class="mt-3 row">
            <h6 class="text-center">
                <?= $currentDate = date('d-m-Y') ?></h6>
        </div> --}}




        <div class="row">

            <div style="display: flex; justify-content: space-between; font-size: 12px;" class="fw-bold">
                <span>Sale ID: {{ $invoice->invoice_no }}</span>
                <span><?= date('d-m-Y') ?></span>
            </div>
            <p style="font-size: 12px;" class="fw-bold">
                Employee: {{ auth()->user()->name }}
                <br>Customer: {{ $invoice->customer_name }}
                <br>Phone: {{ $invoice->phno }}
                <br>Address: {{ $invoice->address }}
            </p>



        </div>
        <div class="mt-1 table-responsive">
            <table class="mt-1" style="font-size: 14px;width:100%">
                <thead>
                    <tr class="text-left">
                        <th style="width: 40%;font-size: 10px;">Item Name.</th>
                        <th style="width: 10%;font-size: 10px;">Qty</th>
                        <th style="width: 20%;font-size: 10px;">Price</th>
                        <th style="width: 20%;font-size: 10px;">Discounts</th>
                        <th class="text-end" style="width: 10%;font-size: 10px;">Total</th>
                    </tr>
                </thead>
                <tbody class="text-center" style="height:30px">

                    @foreach ($invoices as $invoice)
                        @php
                            $groupedSells = $invoice->sells->groupBy('exp_date');
                            $totalAmount = 0;
                            $totalDiscount = 0;
                        @endphp


                        @foreach ($invoice->sells as $sell)
                            <tr class="text-start">
                                <td style="font-size: 10px;">{{ $sell->part_number }}</td>
                                <td style="font-size: 10px;">{{ $sell->product_qty }}</td>
                                <td style="font-size: 10px;">
                                    @if ($invoice->sale_price_category == 'Default')
                                        @if ($invoice->type == 'Whole Sale')
                                            {{ number_format($sell->product_price ?? 0, 2) }}
                                        @else
                                            {{ number_format($sell->retail_price ?? 0, 2) }}
                                        @endif
                                    @elseif ($invoice->sale_price_category == 'Whole Sale')
                                        {{ number_format($sell->product_price ?? 0, 2) }}
                                    @elseif ($invoice->sale_price_category == 'Retail')
                                        {{ number_format($sell->retail_price ?? 0, 2) }}
                                    @else
                                        {{ number_format($sell->retail_price ?? 0, 2) }}
                                    @endif
                                </td>
                                <td style="font-size: 10px;">{{ number_format($sell->discount ?? 0, 2) }}

                                    @php
                                        $totalDiscount += $sell->discount;
                                    @endphp
                                </td>
                                <td class="text-end" style="font-size: 10px;">
                                    @if ($invoice->sale_price_category == 'Default')
                                        @if ($invoice->type == 'Whole Sale')
                                            {{ number_format($sell->product_price * $sell->product_qty - $sell->discount ?? 0, 2) }}
                                        @else
                                            {{ number_format($sell->retail_price * $sell->product_qty - $sell->discount ?? 0, 2) }}
                                        @endif
                                    @elseif ($invoice->sale_price_category == 'Whole Sale')
                                        {{ number_format($sell->product_price * $sell->product_qty - $sell->discount ?? 0, 2) }}
                                    @elseif ($invoice->sale_price_category == 'Retail')
                                        {{ number_format($sell->retail_price * $sell->product_qty - $sell->discount ?? 0, 2) }}
                                    @else
                                        {{ number_format($sell->retail_price * $sell->product_qty - $sell->discount ?? 0, 2) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach

                </tbody>
                <tfoot style="border-top: 2px solid black !important;font-size: 10px;">

                    <tr style="line-height: 20px;">
                        <td colspan="4" class="text-end fw-bold">Total</td>
                        <td class="text-end fw-bold">{{ number_format($invoice->total ?? 0, 2) }}</td>
                    </tr>
                    {{-- <tr style="line-height: 20px;">
                        <td colspan="4" class="text-end fw-bold">Overall Discount</td>
                        <td class="text-end fw-bold">{{ number_format($invoice->discount_total ?? 0, 2) }}</td>
                    </tr>
                    <tr style="line-height: 20px;">
                        <td colspan="4" class="text-end fw-bold">Item Discount</td>
                        <td class="text-end fw-bold">{{ number_format($totalDiscount ?? 0, 2) }}</td>
                    </tr> --}}
                    @foreach ($payment_methods as $index => $payment_method)
                        <tr style="line-height: 20px;">

                            <td colspan="4" class="text-end fw-bold">
                                @if ($index == 0)
                                    Payment -
                                @endif
                                {{ $payment_method->payment_method }}
                            </td>

                            <td class="text-end fw-bold">
                                {{ number_format($payment_method->payment_amount ?? 0, 2) }}

                            </td>
                        </tr>
                    @endforeach

                    <tr style="line-height: 20px;">
                        <td colspan="4" class="text-end fw-bold">Remaining Balance</td>
                        <td class="text-end fw-bold">{{ number_format($invoice->remain_balance ?? 0, 2) }}</td>
                    </tr>

                </tfoot>
            </table>
        </div>

    </div>
    </div>

    <script>
        function printPage() {
            window.print();
        }
    </script>


</body>

</HTML>
