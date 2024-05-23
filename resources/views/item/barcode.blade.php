<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SSE POS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<style>
    @media print {

        /* Define styles for printing */
        body {
            -webkit-print-color-adjust: exact;
            /* Chrome, Safari */
            color-adjust: exact;
            /* Firefox */
        }

        .page-tools {
            display: none;
        }
    }
</style>

<body>
    <div class="mt-4 container-fluid barcode-container">
        <div class="barcode">

            <table border="0">
                <tr>


                    <td style="font-size: 10px;font-weight:bolder" class="text-center">
                        {{ $productCode->item_name  }}

                <tr>

                    {{-- <td> {!! DNS1D::getBarcodeHTML(
                        $productCode->barcode ?  $productCode->barcode : (string) $productCode->id,
                        'EAN13',
                        ) !!}</td> --}}

                    <td> {!! DNS1D::getBarcodeHTML($productCode->barcode ,'EAN13') !!}

                       </td>

                </tr>
                <tr>
                <td style="font-size: 10px;font-weight:bolder " class="text-center">{{ $productCode->barcode  }} </td></tr>
                <tr>
                    <td style="font-size: 10px;font-weight:bolder" class="text-center">{{ $productCode->retail_price }} Kyats</td>

                </tr>
            </table>

        </div>
    </div>
    </div>
</body>

</html>
