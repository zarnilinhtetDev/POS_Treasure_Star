<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cash Voucher</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .underline-dashed {
            text-decoration: underline;
            text-decoration-style: dashed;
            text-underline-offset: 5px;
            /* Optional: Adjusts the distance of the underline */
        }

        /* Print-specific styles */
        @media print {
            @page {
                size: A4 landscape;
                /* Set the page to A4 size in landscape */
                /* margin: 10mm; */
                /* Add some margins for the printer */
            }

            body {
                margin: 0;
                padding: 0;
            }

            #no-print {
                display: none;
                /* Hide elements like buttons on print */
            }
        }

        /* Container for the voucher */
        .voucher {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            /* border: 1px solid #000; */
            /* padding: 20px; */
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            text-align: center;
            margin-top: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 10px;
            /* text-align: left; */
        }

        .table th {
            background-color: #f0f0f0;
        }

        .adjustment {
            width: 10%;
        }

        .adjustment2 {
            width: 35%;
        }

        p {
            font-size: 20px;
        }
    </style>
</head>

<body>
    <div class="voucher">
        <p class="text-end" id="no-print">
            <button class="btn btn-primary" onclick="window.print()">Print Voucher</button>
            <a class="btn btn-danger" style="border-radius:10px;" onclick="window.history.back()"><i
                    class="fa-solid fa-backward text-white"></i> Back</a>
        </p>
        <div class="header">
            @foreach ($profile as $pic)
                @if ($invoice->branch == $pic->branch)
                    <div class=" text-center">
                        <img src="{{ asset('logos/' . ($pic->logos ?? 'null')) }}" width="180" height="100">
                    </div>
                    <div class="row" style="margin-top: 5px;">
                        <h4 class="text-center fw-bold">{{ $pic->name }}</h4>

                        <p class="text-center fw-bold" style="font-size: 14px;">
                            {{ $pic->address }}
                            <br>
                            {{ $pic->phno1 }}, {{ $pic->phno2 }}
                        </p>
                    </div>
                @endif
            @endforeach
            <!-- <div class="d-flex justify-content-center align-items-center" style="margin-bottom: 5px;">
                <img src="{{ asset('img/netray_logo.png') }}" alt="Company Logo" style="height: 60px; margin-right: 10px;">
            </div> -->
            <div class="d-flex justify-content-between">
                <p class="mt-4"><strong>HO No:</strong><span
                        class="underline-dashed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                </p>
                <h3 class="mt-0 mb-0 text-center" style="padding: 10px;text-decoration: underline"><strong>Cash
                        Voucher</strong></h3>
                <div class="mt-4">
                    <p><strong>Voucher No:</strong><span
                            class="underline-dashed">&nbsp;&nbsp;&nbsp;{{ $invoice->invoice_no }}&nbsp;&nbsp;&nbsp;</span>
                    </p>
                    <p><strong>Date:</strong><span
                            class="underline-dashed">&nbsp;&nbsp;&nbsp;{{ date_format(new DateTime(), 'd-m-Y') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="content text-center">
            <p><strong>Received From:</strong><span
                    class="underline-dashed">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $invoice->customer_name }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
            </p>
        </div>

        <table class="table">
            <thead style="font-size: 20px">
                <tr>
                    <th class="text-center" colspan="2">Description</th>
                    <th class="text-center adjustment2" colspan="2">Amount</th> <!-- Amount spans two columns -->
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2">{{ $make_payment->note }}</td>
                    <td>{{ $make_payment->amount }}</td>
                    <td>{{ $make_payment->amount }}</td>
                </tr>
                <tr>
                    <td><strong>A/C</strong></td>
                    <td class="text-center adjustment"><strong>Cash/Cheque</strong></td>
                    <td><strong>{{ $make_payment->amount }}</strong></td>
                    <td><strong>{{ $make_payment->amount }}</strong></td>
                </tr>
            </tbody>
        </table>
        <div class="">
            <p><strong>The Sum of Kyats :</strong>
                _____________________________________________________________________<br>
                ______________________________________________________________________________________
            </p>
        </div>

        <div class="footer">
            <p><strong>Recept Approved by:</strong> _________________________</p>
            <p><strong>Payment by:</strong> _________________________</p>
        </div>
    </div>
</body>

</html>
